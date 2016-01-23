<?php defined('ABSPATH') OR die('This script cannot be accessed directly.');

/**
 * Shortcode: vc_row_inner
 *
 * Overloaded by UpSolution custom implementation.
 *
 * @var $shortcode {String} Current shortcode name
 * @var $shortcode_base {String} The original called shortcode name (differs if called an alias)
 * @var $atts {Array} Shortcode attributes
 * @var $content {String} Shortcode's inner content
 */

$atts = shortcode_atts( array(
	/**
	 * @var string Columns type: 'small' / 'medium' / 'large' / 'none'
	 */
	'columns_type' => 'medium',
	/**
	 * @var string
	 */
	'el_id' => '',
	/**
	 * @var string
	 */
	'el_class' => '',
	/**
	 * @var string
	 */
	'css' => '',
), $atts );

$class_name = '';

// Offset modificator
$class_name .= ' offset_' . $atts['columns_type'];

// Preserving additional class for inner VC rows
if ( $shortcode_base == 'vc_row_inner' ) {
	$class_name .= ' vc_inner';
}

// Additional class set by a user in a shortcode attributes
if ( ! empty( $atts['el_class'] ) ) {
	$class_name .= ' ' . sanitize_html_class( $atts['el_class'] );
}

// Special VC hooks
if ( function_exists( 'get_row_css_class' ) ) {
	$class_name .= ' ' . get_row_css_class();
}
if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$class_name .= ' ' . vc_shortcode_custom_css_class( $atts['css'], ' ' );
}
$class_name = apply_filters( 'vc_shortcodes_css_class', $class_name, $shortcode_base, $atts );

$row_id_param = '';

$output = '<div class="g-cols wpb_row' . $class_name . '"';
if ( ! empty( $atts['el_id'] ) ) {
	$output .= ' id="' . $atts['el_id'] . '"';
}
$output .= '>';
$output .= do_shortcode( $content );
$output .= '</div>';

echo $output;
