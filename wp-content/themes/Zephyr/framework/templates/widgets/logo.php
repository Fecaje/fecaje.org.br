<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Header logo
 *
 * (!) Important: this file is not intended to be overloaded, so use the below hooks for customizing instead
 *
 * @action Before the template: 'us_before_template:templates/widgets/contacts'
 * @action After the template: 'us_after_template:templates/widgets/contacts'
 */

$class_name = '';
$images_html = '';
$logo_alt = get_bloginfo( 'name' );
if ( us_get_option( 'logo_type', 'text' ) == 'text' ) {
	$class_name .= ' with_title';
} else {
	// Logo type => option name
	$images_config = array(
		'default' => 'logo_image',
		'transparent' => 'logo_image_transparent',
	);
	global $usof_options;
	foreach ( $images_config as $logo_type => $option_name ) {
		if ( ! ( $logo = us_get_option( $option_name ) ) ) {
			continue;
		}
		if ( ! empty( $logo ) AND $logo_type != 'default' ) {
			$class_name = ' with_' . $logo_type;
		}
		$img = usof_get_image_src( $logo );
		if ( $img ) {
			$images_html .= '<img class="for_' . $logo_type . '" src="' . esc_url( $img[0] ) . '"';
			if ( ! empty( $img[1] ) AND ! empty( $img[2] ) ) {
				// Image sizes may be missing when logo is a direct URL
				$images_html .= ' width="' . $img[1] . '" height="' . $img[2] . '"';
			}
			$images_html .= ' alt="' . esc_attr( $logo_alt ) . '" />';
		}
	}
}
$home_url = function_exists( 'icl_get_home_url' ) ? icl_get_home_url() : esc_url( home_url( '/' ) );

$output = '<div class="w-logo ' . $class_name . '"><a class="w-logo-link" href="' . $home_url . '">';
if ( us_get_option( 'logo_type', 'text' ) == 'img' ) {
	$output .= '<span class="w-logo-img">' . $images_html . '</span>';
} else {
	$logo_text = us_get_option( 'logo_text' ) ? us_get_option( 'logo_text' ) : $logo_alt;
	$output .= '<span class="w-logo-title">' . $logo_text . '</span>';
}
$output .= '</a></div>';

echo $output;
