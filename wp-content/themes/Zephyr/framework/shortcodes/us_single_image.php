<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_single_image
 *
 * @var $shortcode {String} Current shortcode name
 * @var $shortcode_base {String} The original called shortcode name (differs if called an alias)
 * @var $atts {Array} Shortcode attributes
 * @var $content {String} Shortcode's inner content
 */

$atts = shortcode_atts( array(
	/**
	 * @var int WordPress media library image ID
	 */
	'image' => '',
	/**
	 * @var string Image size: 'large' / 'medium' / 'thumbnail' / 'full'
	 */
	'size' => 'large',
	/**
	 * @var string Image alignment: '' / 'left' / 'center' / 'right'
	 */
	'align' => '',
	/**
	 * @var bool Enable lignbox with th original image on click
	 */
	'lightbox' => FALSE,
	/**
	 * @var string Image link in a serialized format: 'url:http%3A%2F%2Fwordpress.org|title:WP%20Website|target:%20_blank'
	 */
	'link' => '',
	/**
	 * @var string Animation type: '' / 'fade' / 'afc' / 'afl' / 'afr' / 'afb' / 'aft' / 'hfc' / 'wfc'
	 */
	'animate' => '',
	/**
	 * @var float Animation delay (in seconds)
	 */
	'animate_delay' => 0,
	/**
	 * @var string Extra class name
	 */
	'el_class' => '',
	/**
	 * @var string Custom CSS
	 */
	'css' => '',
), $atts );

$classes = '';

// Link attributes' values
$link = array();

$img_id = intval( $atts['image'] );
if ( ! $img_id OR ! ( $image = wp_get_attachment_image_src( $img_id, $atts['size'] ) ) ) {
	// In case of any image issue using placeholder so admin could understand it quickly
	// TODO Move placeholder URL to some config
	global $us_template_directory_uri;
	$placeholder_url = $us_template_directory_uri . '/img/placeholder/500x500.gif';
	$image_html = '<img src="' . $placeholder_url . '" width="500" height="500" alt="">';
} else {
	$image_html = '<img src="' . $image[0] . '" width="' . $image[1] . '" height="' . $image[2] . '" alt="">';
	if ( $atts['lightbox'] ) {
		$link['url'] = wp_get_attachment_image_src( $img_id, 'full' );
		$link['url'] = ( $link['url'] ) ? $link['url'][0] : $image[0];
		$link['ref'] = 'magnificPopup';
	}
}

$link_target = '';
if ( ! $atts['lightbox'] AND ! empty( $atts['link'] ) ) {
	// Passing params from vc_link field type
	$link = array_merge( $link, us_vc_build_link( $atts['link'] ) );
}

if ( ! empty( $link['url'] ) ) {
	$link_html = '<a href="' . esc_url( $link['url'] ) . '"';
	unset( $link['url'] );
	foreach ( $link as $key => $value ) {
		$link_html .= ' ' . $key . '="' . esc_attr( $value ) . '"';
	}
	$link_html .= '>';
	$image_html = $link_html . $image_html . '</a>';
}

if ( $atts['align'] != '' ) {
	$classes .= ' align_' . $atts['align'];
}
if ( $atts['animate'] != '' ) {
	$classes .= ' animate_' . $atts['animate'];
	if ( ! empty( $atts['animate_delay'] ) ) {
		$atts['animate_delay'] = floatval( $atts['animate_delay'] );
		$classes .= ' d' . intval( $atts['animate_delay'] * 5 );
	}
}

if ( ! empty( $atts['css'] ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $atts['css'] );
}

if ( $atts['el_class'] != '' ) {
	$classes .= ' ' . $atts['el_class'];
}

$output = '<div class="w-image ' . $classes . '">' . $image_html . '</div>';

echo $output;
