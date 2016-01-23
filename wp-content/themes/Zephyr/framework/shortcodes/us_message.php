<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_message
 *
 * @var $shortcode {String} Current shortcode name
 * @var $shortcode_base {String} The original called shortcode name (differs if called an alias)
 * @var $atts {Array} Shortcode attributes
 * @var $content {String} Shortcode's inner content
 */

$atts = shortcode_atts( array(
	/**
	 * @var string Message box color: 'info' / 'attention' / 'success' / 'error' / 'custom'
	 */
	'color' => 'info',
	/**
	 * @var string Background color
	 */
	'bg_color' => '',
	/**
	 * @var string Text color
	 */
	'text_color' => '',
	/**
	 * @var string Icon
	 */
	'icon' => '',
	/**
	 * @var bool Enable closing?
	 */
	'closing' => FALSE,
	/**
	 * @var string Extra class name
	 */
	'el_class' => '',
), $atts );

$classes = '';
$inner_css = '';

$icon_html = '';
$atts['icon'] = trim( $atts['icon'] );
if ( ! empty( $atts['icon'] ) ) {
	$icon_html = '<div class="w-message-icon"><i class="' . us_prepare_icon_class( $atts['icon'] ) . '"></i></div>';
	$classes .= ' with_icon';
}

$closer_html = '';
if ( $atts['closing'] ) {
	$classes .= ' with_close';
	$closer_html = '<div class="w-message-close"> &#10005; </div>';
}

if ( $atts['color'] == 'custom' ) {
	if ( ! empty( $atts['bg_color'] ) ) {
		$inner_css .= 'background-color:' . $atts['bg_color'] . ';';
	}
	if ( ! empty( $atts['text_color'] ) ) {
		$inner_css .= 'color:' . $atts['text_color'] . ';';
	}
}
$classes .= ' type_' . $atts['color'];

if ( ! empty( $atts['el_class'] ) ) {
	$classes .= ' ' . $atts['el_class'];
}

if ( ! empty( $inner_css ) ) {
	$inner_css = ' style="' . $inner_css . '"';
}

$output = '<div class="w-message' . $classes . '"' . $inner_css . '>' . $closer_html . $icon_html;
$output .= '<div class="w-message-body"><p>' . do_shortcode( $content ) . '</p></div></div>';

echo $output;
