<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_btn
 *
 * @var $shortcode {String} Current shortcode name
 * @var $shortcode_base {String} The original called shortcode name (differs if called an alias)
 * @var $atts {Array} Shortcode attributes
 * @var $content {String} Shortcode's inner content
 */

$atts = shortcode_atts( array(
	/**
	 * @var string Video link
	 */
	'link' => 'http://vimeo.com/23237102',
	/**
	 * @var string Ratio: '16x9' / '4x3' / '3x2' / '1x1'
	 */
	'ratio' => '16x9',
	/**
	 * @var string Max width in pixels
	 */
	'max_width' => '',
	/**
	 * @var string Video alignment: 'left' / 'center' / 'right'
	 */
	'align' => 'left',
	/**
	 * @var string Extra css
	 */
	'css' => '',
	/**
	 * @var string Extra class name
	 */
	'el_class' => '',
), $atts );

$classes = '';
$inner_css = '';

if ( ! empty( $atts['ratio'] ) ) {
	$classes .= ' ratio_' . $atts['ratio'];
}

$align_class = '';
if ( $atts['max_width'] != FALSE ) {
	$inner_css = ' style="max-width: ' . $atts['max_width'] . 'px"';
	$classes .= ' align_' . $atts['align'];
}

if ( ! empty( $atts['css'] ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $atts['css'] );
}

if ( $atts['el_class'] != '' ) {
	$classes .= ' ' . $atts['el_class'];
}

$embed_html = '';
foreach ( us_config( 'embeds' ) as $provider => $embed ) {
	if ( $embed['type'] != 'video' OR ! preg_match( $embed['regex'], $atts['link'], $matches ) ) {
		continue;
	}
	$video_id = $matches[ $embed['match_index'] ];
	$embed_html = str_replace('<id>', $matches[ $embed['match_index'] ], $embed['html']);
	break;
}

if ( empty( $embed_html ) ) {
	// Using the default WordPress way
	global $wp_embed;
	$embed_html = $wp_embed->run_shortcode( '[embed]' . $atts['link'] . '[/embed]' );
}

$output = '<div class="w-video' . $classes . '"' . $inner_css . '><div class="w-video-h">' . $embed_html . '</div></div>';

echo $output;
