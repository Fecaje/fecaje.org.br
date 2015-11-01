<?php

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
 * Load some specified template and pass variables to it's scope.
 *
 * (!) If you create a template that is loaded via this method, please describe the variables that it should receive.
 *
 * @param string $template_name Template name to include (ex: 'single-post')
 * @param array $vars Array of variables to pass to a included templated
 */
function us_load_template( $template_name, $vars = NULL ) {

	// Storing known paths within single app launch to reduce file_exists checks
	static $template_paths = array();
	if ( empty( $template_paths ) ) {
		// First function execution
		$template_paths = apply_filters( 'us_template_paths', array() );
	}

	// Searching for the needed file in a child theme, in the parent theme and, finally, in the framework
	if ( ! isset( $template_paths[ $template_name ] ) ) {
		$template_paths[ $template_name ] = '';
		if ( is_child_theme() AND file_exists( STYLESHEETPATH . '/' . $template_name . '.php' ) ) {
			$template_paths[ $template_name ] = STYLESHEETPATH . '/' . $template_name . '.php';
		} elseif ( file_exists( TEMPLATEPATH . '/' . $template_name . '.php' ) ) {
			$template_paths[ $template_name ] = TEMPLATEPATH . '/' . $template_name . '.php';
		} elseif ( file_exists( TEMPLATEPATH . '/framework/' . $template_name . '.php' ) ) {
			$template_paths[ $template_name ] = TEMPLATEPATH . '/framework/' . $template_name . '.php';
		}
	}

	// Template not found
	if ( empty( $template_paths[ $template_name ] ) ) {
		do_action( 'us_template_not_found:' . $template_name, $vars );

		return;
	}

	$vars = apply_filters( 'us_template_vars:' . $template_name, (array) $vars );
	if ( is_array( $vars ) AND count( $vars ) > 0 ) {
		extract( $vars, EXTR_SKIP );
	}

	do_action( 'us_before_template:' . $template_name, $vars );

	include $template_paths[ $template_name ];

	do_action( 'us_after_template:' . $template_name, $vars );
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
	global $smof_data;
	$value = isset( $smof_data[ $name ] ) ? $smof_data[ $name ] : $default_value;

	return apply_filters( 'us_get_option_' . $name, $value );
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
 * @oaram mixed $default Value to return if no data is found
 *
 * @return mixed
 */
function us_config( $path, $default = NULL ) {
	global $us_template_directory;
	// Caching configuration values in a inner static value within the same request
	static $configs = array();
	// Defined paths to configuration files
	$configs_paths = apply_filters( 'us_configs_paths', array() );
	$path = explode( '.', $path );
	$config_name = $path[0];
	if ( ! isset( $configs[ $config_name ] ) ) {
		if ( ! isset( $configs_paths[ $config_name ] ) ) {
			$configs_paths[ $config_name ] = $us_template_directory . '/framework/config/' . $config_name . '.php';
		}
		us_maybe_load_theme_textdomain();
		$configs[ $config_name ] = require $configs_paths[ $config_name ];
		$configs[ $config_name ] = apply_filters( 'us_config_' . $config_name, $configs[ $config_name ] );
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

function us_get_main_theme_version() {
	$theme = wp_get_theme();
	if ( is_child_theme() ) {
		$theme = wp_get_theme( $theme->get( 'Template' ) );
	}

	return $theme->get( 'Version' );
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
	$texdomain_loaded = is_textdomain_loaded( $domain );
	if ( ! $texdomain_loaded AND is_child_theme() ) {
		$texdomain_loaded = load_theme_textdomain( $domain, STYLESHEETPATH . $path );
	}
	if ( ! $texdomain_loaded ) {
		$texdomain_loaded = load_theme_textdomain( $domain, TEMPLATEPATH . $path );
	}

	return $texdomain_loaded;
}
