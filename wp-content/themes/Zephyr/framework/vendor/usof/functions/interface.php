<?php

add_action( 'admin_menu', 'us_options_admin_menu' );
function us_options_admin_menu() {
	$usof_page = add_submenu_page( 'us-home', US_THEMENAME, __( 'Theme Options', 'us' ), 'edit_theme_options', 'us-theme-options', 'us_theme_options_page' );
	add_action( 'admin_print_scripts-' . $usof_page, 'usof_print_scripts' );
	add_action( 'admin_print_styles-' . $usof_page, 'usof_print_styles' );
}

function us_theme_options_page() {

	// For notices
	echo '<div class="wrap"><h2 class="hidden"></h2>';

	global $usof_directory, $usof_options;
	usof_load_options_once();
	$usof_options = array_merge( usof_defaults(), $usof_options );

	$config = us_config( 'theme-options', array() );
	echo '<div class="usof-container" data-ajaxurl="' . esc_attr( admin_url( 'admin-ajax.php' ) ) . '">';
	echo '<form class="usof-form" method="post" action="#" autocomplete="off">';
	// Output _nonce and _wp_http_referer hidden fields for ajax secuirity checks
	wp_nonce_field( 'usof-actions' );
	echo '<div class="usof-header"><div class="usof-header-logo"><div class="usof-header-logo-text">';
	echo US_THEMENAME . ' <span>' . US_THEMEVERSION . '</span></div></div>';
	echo '<div class="usof-header-title"><h2>' . __( 'General Settings', 'us' ) . '</h2></div>';
	echo '<div class="usof-control for_save status_clear">';
	echo '<button class="usof-button type_save" type="button"><span>' . __( 'Save Changes', 'us' ) . '</span>';
	echo '<span class="usof-preloader"></span></button>';
	echo '<div class="usof-control-message"></div></div></div>';

	// Main menu
	echo '<div class="usof-nav layout_desktop"><div class="usof-nav-control"></div><ul class="usof-nav-list level_1">';
	foreach ( $config as $section_id => &$section ) {
		if ( isset( $section['place_if'] ) AND ! $section['place_if'] ) {
			continue;
		}
		if ( ! isset( $active_section ) ) {
			$active_section = $section_id;
		}
		echo '<li class="usof-nav-item level_1 id_' . $section_id . ( ( $section_id == $active_section ) ? ' current' : '' ) . '"';
		echo ' data-id="' . $section_id . '">';
		echo '<a class="usof-nav-anchor level_1" href="#' . $section_id . '">';
		echo '<span class="usof-nav-icon" style="background-image: url(' . $section['icon'] . ')"></span>';
		echo '<span class="usof-nav-title">' . $section['title'] . '</span>';
		echo '<span class="usof-nav-arrow"></span></a></li>';
	}
	echo '<ul></div>';

	// Content
	echo '<div class="usof-content">';
	foreach ( $config as $section_id => &$section ) {
		if ( isset( $section['place_if'] ) AND ! $section['place_if'] ) {
			continue;
		}
		echo '<section class="usof-section ' . ( ( $section_id == $active_section ) ? 'current' : '' ) . '" data-id="' . $section_id . '">';
		echo '<div class="usof-section-header" data-id="' . $section_id . '">';
		echo '<h3>' . $section['title'] . '</h3><span class="usof-section-header-control"></span></div>';
		echo '<div class="usof-section-content" style="display: ' . ( ( $section_id == $active_section ) ? 'block' : 'none' ) . '">';
		if ( isset( $section['fields'] ) ) {
			foreach ( $section['fields'] as $field_id => &$field ) {
				if ( isset( $field['place_if'] ) AND ! $field['place_if'] ) {
					continue;
				}
				if ( ! isset( $field['type'] ) ) {
					if ( WP_DEBUG ) {
						wp_die( $field_id . ' has no defined type' );
					}
					continue;
				}
				$show_field = ( ! isset( $field['show_if'] ) OR usof_execute_show_if( $field['show_if'] ) );
				if ( $field['type'] == 'wrapper_start' ) {
					echo '<div class="usof-form-wrapper ' . $field_id . '" data-id="' . $field_id . '" ';
					echo 'style="display: ' . ( $show_field ? 'block' : 'none' ) . '">';
					if ( isset( $field['show_if'] ) AND is_array( $field['show_if'] ) AND ! empty( $field['show_if'] ) ) {
						// Showing conditions
						echo '<div class="usof-form-wrapper-showif"' . us_pass_data_to_js( $field['show_if'] ) . '></div>';
					}
					continue;
				} elseif ( $field['type'] == 'wrapper_end' ) {
					echo '</div>';
					continue;
				}
				if ( ! file_exists( $usof_directory . '/fields/' . $field['type'] . '.php' ) ) {
					continue;
				}
				$field['std'] = isset( $field['std'] ) ? $field['std'] : NULL;
				$value = isset( $usof_options[ $field_id ] ) ? $usof_options[ $field_id ] : $field['std'];
				$row_classes = ' type_' . $field['type'];
				if ( $field['type'] != 'message' AND ( ! isset( $field['classes'] ) OR strpos( $field['classes'], 'desc_' ) === FALSE ) ) {
					$row_classes .= ' desc_3';
				}
				if ( isset( $field['classes'] ) AND ! empty( $field['classes'] ) ) {
					$row_classes .= ' ' . $field['classes'];
				}
				echo '<div class="usof-form-row' . $row_classes . '" data-id="' . $field_id . '" ';
				echo 'style="display: ' . ( $show_field ? 'block' : 'none' ) . '">';
				if ( isset( $field['title'] ) AND ! empty( $field['title'] ) ) {
					echo '<div class="usof-form-row-title"><span>' . $field['title'] . '</span></div>';
				}
				echo '<div class="usof-form-row-field">';
				// Including the field control itself
				include $usof_directory . '/fields/' . $field['type'] . '.php';
				if ( isset( $field['description'] ) AND ! empty( $field['description'] ) ) {
					echo '<div class="usof-form-row-desc">';
					echo '<div class="usof-form-row-desc-icon"></div>';
					echo '<div class="usof-form-row-desc-text">' . $field['description'] . '</div>';
					echo '</div>';
				}
				echo '<div class="usof-form-row-state"></div>';
				echo '</div>'; // .usof-form-row-field
				if ( isset( $field['show_if'] ) AND is_array( $field['show_if'] ) AND ! empty( $field['show_if'] ) ) {
					// Showing conditions
					echo '<div class="usof-form-row-showif"' . us_pass_data_to_js( $field['show_if'] ) . '></div>';
				}
				echo '</div>'; // .usof-form-row
			}
		}
		echo '</div></section>';
	}
	echo '</div>';

	// Footer
	echo '<div class="usof-footer"><div class="usof-control for_save status_clear">';
	echo '<button class="usof-button type_save" type="button"><span>' . __( 'Save Changes', 'us' ) . '</span>';
	echo '<span class="usof-preloader"></span></button>';
	echo '<div class="usof-control-message"></div></div>';
	echo '<div class="usof-control for_reset status_clear">';
	echo '<button class="usof-button type_reset" type="button"><span>' . __( 'Reset Options', 'us' ) . '</span>';
	echo '<span class="usof-preloader"></span></button>';
	echo '<div class="usof-control-message"></div></div>';
	echo '<div class="usof-footer-bgscroll"></div></div>';

	echo '</form>';
	echo '</div>';

	echo '</div>';
}

function usof_print_scripts() {
	global $usof_directory_uri, $usof_version;
	if ( ! did_action( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
	}
	wp_enqueue_script( 'usof-colorpicker', $usof_directory_uri . '/js/colpick.js', array( 'jquery' ), '1.0', TRUE );
	wp_enqueue_script( 'usof-select2', $usof_directory_uri . '/js/select2.min.js', array( 'jquery' ), '4.0', TRUE );
	wp_enqueue_script( 'usof-scripts', $usof_directory_uri . '/js/usof.js', array( 'jquery' ), $usof_version, TRUE );
}

function usof_print_styles() {
	global $usof_directory_uri, $usof_version;
	wp_enqueue_style( 'usof-colorpicker', $usof_directory_uri . '/css/colpick.css', array(), '1.0' );
	wp_enqueue_style( 'usof-select2', $usof_directory_uri . '/css/select2.css', array(), '4.0' );
	wp_enqueue_style( 'usof-styles', $usof_directory_uri . '/css/usof.css', array(), $usof_version );
}

/**
 * Checks if the showing condition is true
 *
 * Note: at any possible syntax error we choose to show the field so it will be functional anyway.
 *
 * @param array $condition Showing condition
 *
 * @return bool
 */
function usof_execute_show_if( $condition ) {
	global $usof_options;

	if ( ! is_array( $condition ) OR count( $condition ) < 3 ) {
		// Wrong condition
		$result = TRUE;
	} elseif ( in_array( strtolower( $condition[1] ), array( 'and', 'or' ) ) ) {
		// Complex or / and statement
		$result = usof_execute_show_if( $condition[0] );
		$index = 2;
		while ( isset( $condition[ $index ] ) ){
			$condition[ $index - 1 ] = strtolower( $condition[ $index - 1 ] );
			if ( $condition[ $index - 1 ] == 'and' ) {
				$result = ( $result AND usof_execute_show_if( $condition[ $index ] ) );
			} elseif ( $condition[ $index - 1 ] == 'or' ) {
				$result = ( $result OR usof_execute_show_if( $condition[ $index ] ) );
			}
			$index = $index + 2;
		}
	} else {
		if ( ! isset( $usof_options[ $condition[0] ] ) ) {
			return TRUE;
		}
		$value = $usof_options[ $condition[0] ];
		if ( $condition[1] == '=' ) {
			$result = ( $value == $condition[2] );
		} elseif ( $condition[1] == '!=' OR $condition[1] == '<>' ) {
			$result = ( $value != $condition[2] );
		} elseif ( $condition[1] == 'in' ) {
			$result = ( ! is_array( $condition[2] ) OR in_array( $value, $condition[2] ) );
		} elseif ( $condition[1] == 'not in' ) {
			$result = ( ! is_array( $condition[2] ) OR ! in_array( $value, $condition[2] ) );
		} elseif ( $condition[1] == '<=' ) {
			$result = ( $value <= $condition[2] );
		} elseif ( $condition[1] == '<' ) {
			$result = ( $value < $condition[2] );
		} elseif ( $condition[1] == '>' ) {
			$result = ( $value > $condition[2] );
		} elseif ( $condition[1] == '>=' ) {
			$result = ( $value >= $condition[2] );
		} else {
			$result = TRUE;
		}
	}

	return $result;
}
