<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

class US_Migration {

	/**
	 * @var US_Migration
	 */
	protected static $instance;

	/**
	 * Singleton pattern: US_Migration::instance()->do_something()
	 *
	 * @return US_Migration
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new US_Migration;
		}

		return self::$instance;
	}

	/**
	 * @var array List of migration classes instances $version => $instance
	 */
	public $translators = array();

	protected function __construct() {

		global $us_template_directory;

		// Checking if the theme was just installed
		$theme = wp_get_theme();
		if ( is_child_theme() ) {
			$theme = wp_get_theme( $theme->get( 'Template' ) );
		}
		$theme_name = $theme->get( 'Name' );
		if ( ! get_option( $theme_name . '_options' ) ) {
			$this->set_db_version();

			return;
		}

		// Get the current DB version
		$db_version = $this->get_db_version();

		// Get available migrations (should be set by the theme's us_config_migrations filter) and keep only the needed ones
		$migrations = array();
		foreach ( us_config( 'migrations' ) as $migration_version => $migration_file ) {
			if ( version_compare( $db_version, $migration_version, '<' ) ) {
				$class = basename( $migration_file, '.php' );
				if ( file_exists( $us_template_directory . '/' . $migration_file ) ) {
					include $us_template_directory . '/' . $migration_file;
				} elseif ( WP_DEBUG ) {
					wp_die( 'Defined migration file not found: ' . $us_template_directory . '/' . $migration_file );
				}
				if ( class_exists( $class ) ) {
					$this->translators[ $migration_version ] = new $class;
				}
			}
		}
		if ( empty( $this->translators ) ) {
			return;
		}

		if ( ! is_admin() OR ( defined( 'DOING_AJAX' ) AND DOING_AJAX ) ) {
			// Providing fall-back compatibility for the previous website db versions
			$this->provide_fallback( $migrations );
		} else {
			// Admin panel
			if ( $this->should_be_manual() ) {
				if ( isset( $_GET['us-migration'] ) AND wp_verify_nonce( $_GET['us-migration'], 'us-migration' ) ) {
					// Performing the migration
					add_action( 'admin_init', array( $this, 'perform_migration' ), 1 );
					add_action( 'admin_notices', array( $this, 'display_migration_completed' ), 1 );
				} else {
					// Notifying about the needed migrations
					add_action( 'admin_notices', array( $this, 'display_migration_needed' ), 1 );
				}
			} else {
				// Performing the migration silently
				add_action( 'admin_init', array( $this, 'perform_migration' ), 1 );
			}
		}
	}

	/**
	 * Check if the current set of migrations should be manual
	 */
	protected function should_be_manual() {
		$should_be_manual = FALSE;
		foreach ( $this->translators as $version => $translator ) {
			$should_be_manual = ( $should_be_manual OR $translator->should_be_manual );
		}

		return $should_be_manual;
	}

	/**
	 * Get the theme's current database version
	 *
	 * @return string
	 */
	public function get_db_version() {
		// Getting from global options, not from the theme mods, as it affects content and general options (not theme mods)
		$result = get_option( 'us_db_version' );

		return $result ? $result : '0';
	}

	/**
	 * Set the current database version
	 *
	 * @param string $version If not set will be updated to the current theme's version
	 */
	public function set_db_version( $version = NULL ) {
		if ( $version === NULL ) {
			$version = $this->get_theme_version();
		}
		update_option( 'us_db_version', $version, TRUE );
	}

	public function get_theme_version() {
		$theme = wp_get_theme();
		if ( is_child_theme() ) {
			$theme = wp_get_theme( $theme->get( 'Template' ) );
		}

		return $theme->get( 'Version' );
	}

	public function provide_fallback( $migrations ) {
		// For both frontend and ajax requests
		add_action( 'init', array( $this, 'fallback_theme_options' ), 12 );
		add_filter( 'meta', array( $this, 'fallback_meta' ), 10, 4 );
		if ( ! is_admin() ) {
			// For frontend requests only
			add_filter( 'theme_mod_nav_menu_locations', array( $this, 'fallback_menus' ), 5 );
			add_filter( 'the_content', array( $this, 'fallback_content' ), 5 );
		}
	}

	/**
	 * Method that is bound to 'theme_mod_nav_menu_locations' filter to provide live fallback compatibility for menus migrations
	 *
	 * @param string $locations
	 *
	 * @return mixed
	 */
	public function fallback_menus( $locations ) {
		foreach ( $this->translators as $version => $translator ) {
			if ( method_exists( $translator, 'translate_menus' ) ) {
				$translator->translate_menus( $locations );
			}
		}

		return $locations;
	}

	/**
	 * Method for providing live fallback compatibility for options migrations
	 */
	public function fallback_theme_options() {
		global $smof_data, $of_options;
		foreach ( $this->translators as $version => $translator ) {
			if ( method_exists( $translator, 'translate_theme_options' ) ) {
				$translator->translate_theme_options( $smof_data );
			}
		}
		$options_machine = new Options_Machine( $of_options );
		$smof_data = array_merge( $options_machine->Defaults, $smof_data );

		$smof_data['generate_css_file'] = FALSE;
	}

	/**
	 * Method for providing live fallback compatibility for metas migrations
	 *
	 * @param mixed $meta_val
	 * @param string $key
	 * @param array $args
	 * @param int $post_id
	 *
	 * @return mixed
	 */
	public function fallback_meta( $meta_val, $key, $args, $post_id ) {
		$meta = get_post_meta( $post_id );
		$post_type = get_post_type( $post_id );
		foreach ( $this->translators as $version => $translator ) {
			if ( method_exists( $translator, 'translate_meta' ) ) {
				$translator->translate_meta( $meta, $post_type );
			}
		}
		if ( isset( $meta[ $key ] ) ) {
			if ( ! $args['multiple'] ) {
				return $meta[ $key ][0];
			} else {
				return $meta[ $key ];
			}
		} else {
			return $meta_val;
		}
	}

	/**
	 * Method that is bound to 'the_content' filter to provide live fallback compatibility for content migrations
	 *
	 * @param $content
	 *
	 * @return mixed
	 */
	public function fallback_content( $content ) {
		foreach ( $this->translators as $version => $translator ) {
			if ( method_exists( $translator, 'translate_content' ) ) {
				$translator->translate_content( $content );
			}
		}

		return $content;
	}

	public function display_migration_needed() {
		$output = '<div class="error us-migration">';
		$output .= '<h2>You need to migrate your website data to be compatible with <strong>Zephyr ' . $this->get_theme_version() . '</strong></h2>';
		$output .= '<p><strong>Important</strong>: Do not save any changes before the migration, as doing this you may loose some of your website data.<br>Please <a href="https://help.us-themes.com/zephyr/update20/" target="_blank">read the manual how to migrate the website</a> (or how to rollback to the previous version of the theme, if you don\'t want to migrate) carefully.</p>';
		$output .= '<p><label><input type="checkbox" name="allow_migration" id="allow_migration"> I\'ve read the manual, made a full backup and checked the website: everything works fine!</p>';
		$output .= '<p><input disabled type="submit" value="Start the Migration" class="button button-large" id="migration-start"></p>';
		$output .= '</div>';
		$output .= '<script>';
		$output .= 'jQuery(function($){
			$(".us-migration input.button").attr("disabled", "");
			$(".us-migration input[type=\"checkbox\"]").removeAttr("checked").on("click", function(){
				if ($(this).is(":checked")){
					$(".us-migration input.button").removeAttr("disabled");
				}else{
					$(".us-migration input.button").attr("disabled", "");
				}
			});
			$(".us-migration input.button").click(function(){
				if ( ! $(".us-migration input[type=\"checkbox\"]").is(":checked")) return;
				location.assign("?us-migration=' . wp_create_nonce( 'us-migration' ) . '");
			});
		});';
		$output .= '</script>';
		echo $output;
	}

	public function display_migration_completed() {
		$output = '<div class="updated us-migration">';
		$output .= '<p><strong>Congratulations</strong>: Migration to Zephyr ' . $this->get_theme_version() . ' is completed! Now please regenerate thumbnails and check your website once again. If you notice some issues, <a href="https://help.us-themes.com/zephyr/update20/" target="_blank">follow the manual</a>.</p>';
		$output .= '</div>';
		echo $output;
	}

	/**
	 * Should be bound to admin_init action, so all the needed stuff is initalized
	 */
	public function perform_migration() {
		$this->migrate_menus();
		$this->migrate_theme_options();
		$this->migrate_content_and_meta();
		$this->set_db_version();
	}

	public function migrate_menus() {
		$locations = get_theme_mod( 'nav_menu_locations' );

		$menus_changed = FALSE;
		foreach ( $this->translators as $version => $translator ) {
			if ( method_exists( $translator, 'translate_menus' ) ) {
				$menus_changed = ( $translator->translate_menus( $locations ) OR $menus_changed );
			}
		}

		if ( $menus_changed ) {
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}

	public function migrate_theme_options() {
		// Getting Options
		global $smof_data, $of_options;

		if ( ! isset( $of_options ) OR empty( $of_options ) ) {
			// Forcing options to be loaded even before the main init
			remove_action( 'init', 'of_options' );
			of_options();
		}

		$options_changed = FALSE;
		foreach ( $this->translators as $version => $translator ) {
			if ( method_exists( $translator, 'translate_theme_options' ) ) {
				$options_changed = ( $translator->translate_theme_options( $smof_data ) OR $options_changed );
			}
		}
		if ( $options_changed ) {
			// Filling the missed options with default values
			$options_machine = new Options_Machine( $of_options );
			$smof_data = array_merge( $options_machine->Defaults, $smof_data );
			$smof_data['generate_css_file'] = FALSE;
			// Saving the changed options
			of_save_options( $smof_data );
			//update_option( OPTIONS, $smof_data );
			us_save_styles( $smof_data );
		}
	}

	public function migrate_content_and_meta() {
		global $vc_manager;
		if ( class_exists( 'Vc_Manager' ) AND isset( $vc_manager ) ) {
			$vc = $vc_manager->vc();
		}

		$posts_types = array( 'post', 'page', 'us_portfolio', 'us_client', 'product' );

		// Iterating thru needed post types
		foreach ( $posts_types as $post_type ) {
			$args = array(
				'posts_per_page' => -1,
				'post_type' => $post_type,
				'post_status' => 'any',
			);

			// Fetching posts and iterating them
			$posts = get_posts( $args );
			foreach ( $posts as $post ) {
				// Translating Meta fields
				$meta_changed = FALSE;
				$translate_meta_method = 'translate_meta';
				$meta = get_post_meta( $post->ID );
				foreach ( $this->translators as $version => $translator ) {
					if ( method_exists( $translator, $translate_meta_method ) ) {
						$meta_changed = ( $translator->{$translate_meta_method}( $meta, $post_type ) OR $meta_changed );
					}
				}
				if ( $meta_changed ) {
					foreach ( $meta as $key => $value ) {
						update_post_meta( $post->ID, $key, $value[0] );
					}
				}

				// Translating shortcodes
				$content = $post->post_content;
				$content_changed = FALSE;
				foreach ( $this->translators as $version => $translator ) {
					if ( method_exists( $translator, 'translate_content' ) ) {
						$content_changed = ( $translator->translate_content( $content ) OR $content_changed );
					}
				}
				if ( $content_changed ) {
					wp_update_post( array(
						'ID' => $post->ID,
						'post_content' => $content,
					) );
					if ( isset( $vc ) AND method_exists( $vc, 'buildShortcodesCustomCss' ) ) {
						$vc->buildShortcodesCustomCss( $post->ID );
					}
				}
			}
		}
	}
}

abstract class US_Migration_Translator {

	/**
	 * @var bool Possibly dangerous translation that needs to be migrated manually (don't use this too often)
	 */
	public $should_be_manual = FALSE;

	/**
	 * @var string Extra css that will be appended to the end of the body
	 */
	public $_extra_css = '';

	public function __construct() {
		add_action( 'wp_footer', array( $this, 'append_css' ), 20 );
	}

	public function append_css() {
		if ( ! empty( $this->_extra_css ) ) {
			echo '<style type="text/css" id="' . get_class( $this ) . '">' . $this->_extra_css . '</style>';
		}
	}

	/**
	 *
	 *
	 * @param array $locations
	 * @param array $rules
	 *
	 * @return bool
	 */
	protected function _translate_menus( &$locations, $rules ) {
		$changed = FALSE;
		$menus = wp_get_nav_menus();
		foreach ( $rules as $old => $new ) {
			if ( isset( $locations[ $old ] ) AND $locations[ $old ] != 0 ) {
				foreach ( $menus as $menu ) {
					if ( $menu->term_id == $locations[ $old ] ) {
						$locations[ $new ] = $locations[ $old ];
						unset( $locations[ $old ] );
						$changed = TRUE;
					}
				}
			}
		}

		return $changed;
	}

	protected function _translate_theme_options( &$options, $rules ) {
		$changed = FALSE;
		foreach ( $rules as $option => $rule ) {
			if ( isset( $options[ $option ] ) ) {
				if ( isset( $rule['values'] ) ) {
					foreach ( $rule['values'] as $old_value => $new_value ) {
						if ( $options[ $option ] == $old_value ) {
							$options[ $option ] = $new_value;
							$changed = TRUE;
							break;
						}
					}
				}

				if ( isset( $rule['new_name'] ) ) {
					if ( ! is_array( $rule['new_name'] ) ) {
						$rule['new_name'] = array( $rule['new_name'] );
					}
					$option_value = $options[ $option ];
					unset( $options[ $option ] );
					foreach ( $rule['new_name'] as $new_name ) {
						if ( ! isset( $options[ $new_name ] ) ) {
							$options[ $new_name ] = $option_value;
						}
					}
					$changed = TRUE;
				}
			}
		}

		return $changed;
	}

	protected function _translate_meta( &$meta, $post_type, $rules ) {
		$changed = FALSE;
		foreach ( $rules as $meta_name => $rule ) {
			if ( isset( $meta[ $meta_name ] ) AND in_array( $post_type, $rule['post_types'] ) ) {
				if ( isset( $rule['values'] ) ) {
					foreach ( $rule['values'] as $old_value => $new_value ) {
						if ( $meta[ $meta_name ][0] == $old_value ) {
							$changed = TRUE;
							$meta[ $meta_name ][0] = $new_value;
							break;
						}
					}
				}

				if ( isset( $rule['new_name'] ) ) {
					if ( ! is_array( $rule['new_name'] ) ) {
						$rule['new_name'] = array( $rule['new_name'] );
					}
					$meta_value = $meta[ $meta_name ];
					//unset( $meta[$meta_name] );
					foreach ( $rule['new_name'] as $new_name ) {
						if ( ! isset( $meta[ $new_name ] ) ) {
							$changed = TRUE;
							$meta[ $new_name ] = $meta_value;
						}
					}
				}
			}
		}

		return $changed;
	}

	public function translate_params( &$params, $rules ) {
		$params_changed = FALSE;

		foreach ( $rules as $param => $rule ) {
			if ( isset( $params[ $param ] ) ) {
				if ( isset( $rule['values'] ) ) {
					foreach ( $rule['values'] as $old_value => $new_value ) {
						if ( $params[ $param ] == $old_value ) {
							if ( $new_value === NULL ) {
								unset( $params[ $param ] );
							} else {
								$params[ $param ] = $new_value;
							}
							$params_changed = TRUE;
							break;
						}
					}
				}

				if ( isset( $rule['new_name'] ) ) {
					$params[ $rule['new_name'] ] = $params[ $param ];
					unset( $params[ $param ] );
					$params_changed = TRUE;
				}
			} elseif ( isset( $rule['values'] ) AND isset( $rule['values'][ NULL ] ) ) {
				$params[ $param ] = $rule['values'][ NULL ];
			}
		}

		return $params_changed;
	}

	public function _translate_content( &$content ) {
		$content_changed = FALSE;

		// Searching for all shortcodes
		$shortcode_pattern = $this->get_shortcode_regex();
		if ( preg_match_all( '/' . $shortcode_pattern . '/s', $content, $matches ) ) {
			if ( count( $matches[2] ) ) {
				foreach ( $matches[2] as $i => $shortcode_name ) {
					$shortcode_content_changed = $shortcode_changed = FALSE;
					$shortcode_string = $matches[0][ $i ];
					$shortcode_params_string = $matches[3][ $i ];
					$shortcode_content = $matches[5][ $i ];

					// Check if params of this shortcode should be translated
					$translate_shortcode_method = 'translate_' . $shortcode_name;
					if ( method_exists( $this, $translate_shortcode_method ) ) {
						if ( ! empty( $shortcode_params_string ) ) {
							$shortcode_params = shortcode_parse_atts( $shortcode_params_string );
						} else {
							$shortcode_params = array();
						}
						$shortcode_changed = $this->$translate_shortcode_method( $shortcode_name, $shortcode_params, $shortcode_content );
						// If params should be changed, remaking params string
						if ( $shortcode_changed ) {
							$shortcode_params_string = '';
							foreach ( $shortcode_params as $param => $value ) {
								// TODO: $value may have double quotes? Check this case
								$shortcode_params_string .= ' ' . $param . '="' . $value . '"';
							}
						}
					}

					// Using recursion to translate shortcodes inside found shortcode
					if ( ! empty( $shortcode_content ) ) {
						$shortcode_content_changed = $this->translate_content( $shortcode_content );
					}

					// If it is a text containing pricing - leave just pricing
					if ( get_class( $this ) == 'us_migration_2_0' ) {
						if ( $shortcode_name == 'vc_column_text' AND preg_match( '/^' . $this->get_shortcode_regex( array( 'us_pricing' ) ) . '$/s', trim( $shortcode_content ) ) ) {
							$content = str_replace( $shortcode_string, $shortcode_content, $content );
							$content_changed = TRUE;
							continue;
						}
					}

					// If content or params of the shortcode have been changed, making new shortcode string and replacing it in the content
					if ( $shortcode_content_changed OR $shortcode_changed ) {
						$new_shortcode_string = '[' . $shortcode_name . $shortcode_params_string . ']';
						if ( ! empty( $shortcode_content ) ) {
							$new_shortcode_string .= $shortcode_content;
						}
						// TODO: Possible spaces before the ending ']'. Regex check?
						if ( strpos( $shortcode_string, '[/' . $matches[2][ $i ] . ']' ) ) {
							$new_shortcode_string .= '[/' . $shortcode_name . ']';
						}

						$content = str_replace( $shortcode_string, $new_shortcode_string, $content );
						$content_changed = TRUE;
					}
				}
			}
		}

		return $content_changed;
	}

	public function get_shortcode_regex( $tagnames = NULL ) {
		if ( empty( $tagnames ) OR ! is_array( $tagnames ) ) {
			// Retrieving list of possible shortcode translations from the class methods
			$tagnames = array();
			foreach ( get_class_methods( $this ) as $method_name ) {
				if ( substr( $method_name, 0, 10 ) != 'translate_' ) {
					continue;
				}
				$tagname = substr( $method_name, 10 );
				if ( ! in_array( $tagname, array( 'menus', 'params', 'content', 'theme_options', 'meta' ) ) ) {
					$tagnames[] = $tagname;
				}
			}
		}

		$tagregexp = join( '|', array_map( 'preg_quote', $tagnames ) );

		// WARNING! Do not change this regex without changing do_shortcode_tag() and strip_shortcode_tag()
		// Also, see shortcode_unautop() and shortcode.js.
		return '\\[' // Opening bracket
		       . '(\\[?)' // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
		       . "($tagregexp)" // 2: Shortcode name
		       . '(?![\\w-])' // Not followed by word character or hyphen
		       . '(' // 3: Unroll the loop: Inside the opening shortcode tag
		       . '[^\\]\\/]*' // Not a closing bracket or forward slash
		       . '(?:' . '\\/(?!\\])' // A forward slash not followed by a closing bracket
		       . '[^\\]\\/]*' // Not a closing bracket or forward slash
		       . ')*?' . ')' . '(?:' . '(\\/)' // 4: Self closing tag ...
		       . '\\]' // ... and closing bracket
		       . '|' . '\\]' // Closing bracket
		       . '(?:' . '(' // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
		       . '[^\\[]*+' // Not an opening bracket
		       . '(?:' . '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
		       . '[^\\[]*+' // Not an opening bracket
		       . ')*+' . ')' . '\\[\\/\\2\\]' // Closing shortcode tag
		       . ')?' . ')' . '(\\]?)'; // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
	}

}
