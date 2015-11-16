<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Prepare a proper icon classname from user's custom input
 *
 * @param {String} $icon_class
 *
 * @return mixed|string
 */
function us_prepare_icon_class( $icon_class ) {
	// TODO Add a special hook, move mdfi declaration bound by a theme-hook
	// mdfi-toggle-check-box => mdfi_toggle_check_box
	if ( substr( $icon_class, 0, 4 ) == 'mdfi' ) {
		return str_replace( '-', '_', $icon_class );
	} // fa-check => fa fa-check
	elseif ( substr( $icon_class, 0, 3 ) == 'fa-' ) {
		return 'fa ' . $icon_class;
	} // check => fa fa-check
	else {
		return 'fa fa-' . $icon_class;
	}
}

/**
 * Search for some file in child theme, in parent theme and in framework
 *
 * @param string $filename Relative path to filename with extension
 *
 * @return mixed Full path to file or FALSE if no file was found
 */
function us_locate_file( $filename ) {
	global $us_template_directory, $us_stylesheet_directory, $us_file_paths;
	if ( ! isset( $us_file_paths ) ) {
		$us_file_paths = apply_filters( 'us_file_paths', array() );
	}
	$filename = untrailingslashit( $filename );
	if ( ! isset( $us_file_paths[ $filename ] ) ) {
		if ( is_child_theme() AND file_exists( $us_stylesheet_directory . '/' . $filename ) ) {
			// Searching in child theme first
			$us_file_paths[ $filename ] = $us_stylesheet_directory . '/' . $filename;
		} elseif ( file_exists( $us_template_directory . '/' . $filename ) ) {
			// Searching in parent theme
			$us_file_paths[ $filename ] = $us_template_directory . '/' . $filename;
		} elseif ( file_exists( $us_template_directory . '/framework/' . $filename ) ) {
			// Searching in theme framework
			$us_file_paths[ $filename ] = $us_template_directory . '/framework/' . $filename;
		} else {
			// Found nothing
			$us_file_paths[ $filename ] = FALSE;
		}
	}

	return $us_file_paths[ $filename ];
}

/**
 * Search for some file in framework, in parent theme and in child theme
 *
 * @param string $filename
 *
 * @return array Found files in their reversed priority order
 */
function us_locate_files( $filename ) {
	global $us_template_directory, $us_stylesheet_directory;
	$filename = untrailingslashit( $filename );
	$found = array();
	if ( file_exists( $us_template_directory . '/framework/' . $filename ) ) {
		// Searching in theme framework
		$found[] = $us_template_directory . '/framework/' . $filename;
	}
	if ( file_exists( $us_template_directory . '/' . $filename ) ) {
		// Searching in parent theme
		$found[] = $us_template_directory . '/' . $filename;
	}
	if ( is_child_theme() AND file_exists( $us_stylesheet_directory . '/' . $filename ) ) {
		// Searching in child theme first
		$found[] = $us_stylesheet_directory . '/' . $filename;
	}

	return $found;
}

/**
 * Load some specified template and pass variables to it's scope.
 *
 * (!) If you create a template that is loaded via this method, please describe the variables that it should receive.
 *
 * @param string $template_name Template name to include (ex: 'templates/form/form')
 * @param array $vars Array of variables to pass to a included templated
 */
function us_load_template( $template_name, $vars = NULL ) {

	// Searching for the needed file in a child theme, in the parent theme and, finally, in the framework
	$file_path = us_locate_file( $template_name . '.php' );

	// Template not found
	if ( $file_path === FALSE ) {
		do_action( 'us_template_not_found:' . $template_name, $vars );

		return;
	}

	$vars = apply_filters( 'us_template_vars:' . $template_name, (array) $vars );
	if ( is_array( $vars ) AND count( $vars ) > 0 ) {
		extract( $vars, EXTR_SKIP );
	}

	do_action( 'us_before_template:' . $template_name, $vars );

	include $file_path;

	do_action( 'us_after_template:' . $template_name, $vars );
}

/**
 * Get some specified template output with variables passed to it's scope.
 *
 * (!) If you create a template that is loaded via this method, please describe the variables that it should receive.
 *
 * @param string $template_name Template name to include (ex: 'templates/form/form')
 * @param array $vars Array of variables to pass to a included templated
 *
 * @return string
 */
function us_get_template( $template_name, $vars = NULL ) {
	ob_start();
	us_load_template( $template_name, $vars );

	return ob_get_clean();
}

/**
 * Get theme option or return default value
 *
 * @param string $name
 * @param mixed $default_value
 *
 * @return mixed
 */
function us_get_option( $name, $default_value = NULL ) {
	return usof_get_option( $name, $default_value );
}

/**
 * @var $us_query array Allows to use different global $wp_query in different context safely
 */
$us_wp_queries = array();

/**
 * Opens a new context to use a new custom global $wp_query
 *
 * (!) Don't forget to close it!
 */
function us_open_wp_query_context() {
	array_unshift( $GLOBALS['us_wp_queries'], $GLOBALS['wp_query'] );
}

/**
 * Closes last context with a custom
 */
function us_close_wp_query_context() {
	if ( count( $GLOBALS['us_wp_queries'] ) > 0 ) {
		$GLOBALS['wp_query'] = array_shift( $GLOBALS['us_wp_queries'] );
		wp_reset_postdata();
	} else {
		// In case someone forgot to open the context
		wp_reset_query();
	}
}

/**
 * Load and return some specific config or it's part
 *
 * @param string $path <config_name>[.<name1>[.<name2>[...]]]
 *
 * @oaram mixed $default Value to return if no data is found
 *
 * @return mixed
 */
function us_config( $path, $default = NULL ) {
	global $us_template_directory;
	// Caching configuration values in a inner static value within the same request
	static $configs = array();
	// Defined paths to configuration files
	$path = explode( '.', $path );
	$config_name = $path[0];
	if ( ! isset( $configs[ $config_name ] ) ) {
		$config_paths = us_locate_files( 'config/' . $config_name . '.php' );
		if ( empty( $config_paths ) ) {
			if ( WP_DEBUG ) {
				wp_die( 'Config not found: ' . $config_name );
			}
			$configs[ $config_name ] = array();
		} else {
			us_maybe_load_theme_textdomain();
			// Parent $config data may be used from a config file
			$config = array();
			foreach ( $config_paths as $config_path ) {
				$config = require $config_path;
				// Config may be forced not to be overloaded from a config file
				if ( isset( $final_config ) AND $final_config ) {
					break;
				}
			}
			$configs[ $config_name ] = apply_filters( 'us_config_' . $config_name, $config );
		}
	}
	$value = $configs[ $config_name ];
	for ( $i = 1; $i < count( $path ); $i ++ ) {
		if ( is_array( $value ) AND isset( $value[ $path[ $i ] ] ) ) {
			$value = $value[ $path[ $i ] ];
		} else {
			$value = $default;
			break;
		}
	}

	return $value;
}

/**
 * Get image size information as an array
 *
 * @param string $size_name
 *
 * @return array
 */
function us_get_intermediate_image_size( $size_name ) {
	global $_wp_additional_image_sizes;
	if ( isset( $_wp_additional_image_sizes[ $size_name ] ) ) {
		// Getting custom image size
		return $_wp_additional_image_sizes[ $size_name ];
	} else {
		// Getting standard image size
		return array(
			'width' => get_option( "{$size_name}_size_w" ),
			'height' => get_option( "{$size_name}_size_h" ),
			'crop' => get_option( "{$size_name}_crop" ),
		);
	}
}

/**
 * Transform some variable to elm's onclick attribute, so it could be obtained from JavaScript as:
 * var data = elm.onclick()
 *
 * @param mixed $data Data to pass
 *
 * @return string Element attribute ' onclick="..."'
 */
function us_pass_data_to_js( $data ) {
	return ' onclick=\'return ' . str_replace( "'", '&#39;', json_encode( $data ) ) . '\'';
}

/**
 * Try to get variable from JSON-encoded post variable
 *
 * Note: we pass some params via json-encoded variables, as via pure post some data (ex empty array) will be absent
 *
 * @param string $name $_POST's variable name
 *
 * @return array
 */
function us_maybe_get_post_json( $name = 'template_vars' ) {
	if ( isset( $_POST[ $name ] ) AND is_string( $_POST[ $name ] ) ) {
		$result = json_decode( stripslashes( $_POST[ $name ] ), TRUE );
		if ( ! is_array( $result ) ) {
			$result = array();
		}

		return $result;
	} else {
		return array();
	}
}

/**
 * No js_composer enabled link parsing compatibility
 *
 * @param $value
 *
 * @return array
 */
function us_vc_build_link( $value ) {
	if ( function_exists( 'vc_build_link' ) ) {
		$result = vc_build_link( $value );
	} else {
		$result = array( 'url' => '', 'title' => '', 'target' => '' );
		$params_pairs = explode( '|', $value );
		if ( ! empty( $params_pairs ) ) {
			foreach ( $params_pairs as $pair ) {
				$param = explode( ':', $pair, 2 );
				if ( ! empty( $param[0] ) && isset( $param[1] ) ) {
					$result[ $param[0] ] = rawurldecode( $param[1] );
				}
			}
		}
	}

	// Some of the values may have excess spaces, like the target's ' _blank' value.
	return array_map( 'trim', $result );
}

/**
 * Load theme's textdomain
 *
 * @param string $domain
 * @param string $path Relative path to seek in child theme and theme
 *
 * @return bool
 */
function us_maybe_load_theme_textdomain( $domain = 'us', $path = '/languages' ) {
	if ( is_textdomain_loaded( $domain ) ) {
		return TRUE;
	}
	$locale = apply_filters( 'theme_locale', get_locale(), $domain );
	$filepath = us_locate_file( $path . '/' . $locale . '.mo' );
	if ( $filepath === FALSE ) {
		return FALSE;
	}

	return load_textdomain( $domain, $filepath );
}

/**
 * Merge arrays, inserting $arr2 into $arr1 before/after certain key
 *
 * @param array $arr Modifyed array
 * @param array $inserted Inserted array
 * @param string $position 'before' / 'after' / 'top' / 'bottom'
 * @param string $key Associative key of $arr1 for before/after insertion
 *
 * @return array
 */
function us_array_merge_insert( array $arr, array $inserted, $position = 'bottom', $key = NULL ) {
	if ( $position == 'top' ) {
		return array_merge( $inserted, $arr );
	}
	$key_position = ( $key === NULL ) ? FALSE : array_search( $key, array_keys( $arr ) );
	if ( $key_position === FALSE OR ( $position != 'before' AND $position != 'after' ) ) {
		return array_merge( $arr, $inserted );
	}
	if ( $position == 'after' ) {
		$key_position ++;
	}

	return array_merge( array_slice( $arr, 0, $key_position, TRUE ), $inserted, array_slice( $arr, $key_position, NULL, TRUE ) );
}

/**
 * Recursively merge two or more arrays in a proper way
 *
 * @param array $array1
 * @param array $array2
 * @param array ...
 *
 * @return array
 */
function us_array_merge( $array1, $array2 ) {
	$keys = $keys = array_keys( $array2 );
	// Is associative array?
	if ( array_keys( $keys ) !== $keys ) {
		foreach ( $array2 as $key => $value ) {
			if ( is_array( $value ) AND isset( $array1[ $key ] ) AND is_array( $array1[ $key ] ) ) {
				$array1[ $key ] = us_array_merge( $array1[ $key ], $value );
			} else {
				$array1[ $key ] = $value;
			}
		}
	} else {
		foreach ( $array2 as $value ) {
			if ( ! in_array( $value, $array1, TRUE ) ) {
				$array1[] = $value;
			}
		}
	}

	if ( func_num_args() > 2 ) {
		foreach ( array_slice( func_get_args(), 2 ) as $array2 ) {
			$array1 = us_array_merge( $array1, $array2 );
		}
	}

	return $array1;
}

/**
 * Combine user attributes with known attributes and fill in defaults from config when needed.
 *
 * @param array $atts Passed attributes
 * @param string $shortcode Shortcode name
 * @param string $param_name Shortcode's config param to take pairs from
 *
 * @return array
 */
function us_shortcode_atts( $atts, $shortcode, $param_name = 'atts' ) {
	$pairs = us_config( 'shortcodes.' . $shortcode . '.' . $param_name, array() );

	return shortcode_atts( $pairs, $atts, $shortcode );
}

/**
 * Get number of shares of the provided URL.
 *
 * @param string $url The url to count shares
 * @param array $providers Possible array values: 'facebook', 'twitter', 'linkedin', 'gplus', 'pinterest'
 *
 * @link https://gist.github.com/jonathanmoore/2640302 Great relevant code snippets
 *
 * Dev note: keep in mind that list of providers may differ for the same URL in different function calls.
 *
 * @return array Associative array of providers => share counts
 */
function us_get_sharing_counts( $url, $providers ) {
	// TODO One-end hashing function needed
	$transient = 'us_sharing_count_' . md5( $url );
	// Will be used for array keys operations
	$flipped = array_flip( $providers );
	$cached_counts = get_transient( $transient );
	if ( is_array( $cached_counts ) ) {
		$counts = array_intersect_key( $cached_counts, $flipped );
		if ( count( $counts ) == count( $providers ) ) {
			// The data exists and is complete
			return $counts;
		}
	} else {
		$counts = array();
	}

	// Facebook share count
	if ( in_array( 'facebook', $providers ) AND ! isset( $counts['facebook'] ) ) {
		$fb_query = 'SELECT share_count FROM link_stat WHERE url = "';
		$remote_get_url = 'https://graph.facebook.com/fql?q=' . urlencode( $fb_query ) . $url . urlencode( '"' );
		$result = wp_remote_get( $remote_get_url, array( 'timeout' => 3 ) );
		if( is_array( $result ) ) {
			$data = json_decode($result['body'], TRUE);
		} else {
			$data = null;
		}
		if ( is_array( $data ) AND isset( $data['data'] ) AND isset( $data['data'][0] ) AND isset( $data['data'][0]['share_count'] ) ) {
			$counts['facebook'] = $data['data'][0]['share_count'];
		} else {
			$counts['facebook'] = '0';
		}
	}

	// Twitter share count
	if ( in_array( 'twitter', $providers ) AND ! isset( $counts['twitter'] ) ) {
		$result = wp_remote_get( 'http://cdn.api.twitter.com/1/urls/count.json?url=' . $url, array( 'timeout' => 3 ) );
		if( is_array( $result ) ) {
			$data = json_decode($result['body'], TRUE);
		} else {
			$data = null;
		}
		$counts['twitter'] = ( is_array( $data ) AND isset( $data['count'] ) ) ? $data['count'] : '0';
	}

	// Google+ share count
	if ( in_array( 'gplus', $providers ) AND ! isset( $counts['gplus'] ) ) {
		// Cannot use the official API, as it requires a separate key, and even with this key doesn't work
		$result = wp_remote_get( 'https://plusone.google.com/_/+1/fastbutton?url=' . $url, array( 'timeout' => 3 ) );
		if ( is_array( $result ) AND preg_match( '~\<div[^\>]+id=\"aggregateCount\"[^\>]*\>([^\>]+)\<\/div\>~', $result['body'], $matches ) ) {
			$counts['gplus'] = $matches[1];
		} else {
			$counts['gplus'] = '0';
		}
	}

	// LinkedIn share count
	if ( in_array( 'linkedin', $providers ) AND ! isset( $counts['linkedin'] ) ) {
		$result = wp_remote_get( 'http://www.linkedin.com/countserv/count/share?url=' . $url . '&format=json', array( 'timeout' => 3 ) );
		if( is_array( $result ) ) {
			$data = json_decode($result['body'], TRUE);
		} else {
			$data = null;
		}
		$counts['linkedin'] = isset( $data['count'] ) ? $data['count'] : '0';
	}

	// Pinterest share count
	if ( in_array( 'pinterest', $providers ) AND ! isset( $counts['pinterest'] ) ) {
		$result = wp_remote_get( 'http://api.pinterest.com/v1/urls/count.json?callback=receiveCount&url=' . $url, array( 'timeout' => 3 ) );
		if( is_array( $result ) ) {
			$data = json_decode( rtrim( str_replace( 'receiveCount(', '', $result['body'] ), ')' ), TRUE );
		} else {
			$data = null;
		}
		$counts['pinterest'] = isset( $data['count'] ) ? $data['count'] : '0';
	}

	// Caching the result for the next 2 hours
	set_transient( $transient, $counts, 2 * HOUR_IN_SECONDS );

	return $counts;
}
