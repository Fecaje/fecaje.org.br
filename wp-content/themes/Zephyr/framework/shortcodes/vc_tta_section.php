<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: vc_tta_section
 *
 * Overloaded by UpSolution custom implementation.
 *
 * @var $shortcode {String} Current shortcode name
 * @var $shortcode_base {String} The original called shortcode name (differs if called an alias)
 * @var $atts {Array} Shortcode attributes
 * @var $content {String} Shortcode's inner content
 */

// .w-tabs-section container additional classes and inner CSS-styles
$classes = '';
$inner_css = '';

global $us_tabs_atts, $us_tab_index;
// Tab indexes start from 1
$us_tab_index = isset( $us_tab_index ) ? ( $us_tab_index + 1 ) : 1;

// We could overload some of the atts at vc_tabs implementation, so apply them in here as well
if ( isset( $us_tab_index ) AND isset( $us_tabs_atts[ $us_tab_index - 1 ] ) ) {
	$atts = array_merge( $atts, $us_tabs_atts[ $us_tab_index - 1 ] );
}

$atts = shortcode_atts( array(
	/**
	 * @var string Section title
	 */
	'title' => '',
	/**
	 * @var string Icon
	 */
	'icon' => '',
	/**
	 * @var string Icon position: 'left' / 'right'
	 */
	'i_position' => 'left',
	/**
	 * @var bool Tab is opened when page loads
	 */
	'active' => FALSE,
	/**
	 * @var string Indents type: '' / 'none'
	 */
	'indents' => '',
	/**
	 * @var string Background color
	 */
	'bg_color' => '',
	/**
	 * @var string Text color
	 */
	'text_color' => '',
	/**
	 * @var string Control position (inherited from wrapping vc_tta_tabs shortcode): 'left' / 'right'
	 */
	'c_position' => 'right',
	/**
	 * @var string Extra class name
	 */
	'el_class' => '',
), $atts );

if ( ! empty( $atts['bg_color'] ) ) {
	$inner_css .= 'background-color: ' . $atts['bg_color'] . ';';
}
if ( ! empty( $atts['text_color'] ) ) {
	$inner_css .= 'color: ' . $atts['text_color'] . ';';
}
if ( $inner_css != '' ) {
	$inner_css = ' style="' . $inner_css . '"';
	$classes .= ' color_custom';
}
if ( $atts['icon'] ) {
	$classes .= ' with_icon';
}
if ( $atts['indents'] == 'none' ) {
	$classes .= ' no_indents';
}
if ( $atts['active'] ) {
	$classes .= ' active';
}
if ( ! empty( $atts['el_class'] ) ) {
	$classes .= ' ' . $atts['el_class'];
}

$output = '<div class="w-tabs-section' . $classes . '"' . $inner_css . '>';

// In-tab header (for certain states)
$output .= '<div class="w-tabs-section-header"><div class="w-tabs-section-header-h">';
if ( $atts['c_position'] == 'left' ) {
	$output .= '<div class="w-tabs-section-control"></div>';
}
if ( $atts['icon'] AND $atts['i_position'] == 'left' ) {
	$output .= '<i class="' . us_prepare_icon_class( $atts['icon'] ) . '"></i>';
}
$output .= '<h5 class="w-tabs-section-title">' . $atts['title'] . '</h5>';
if ( $atts['icon'] AND $atts['i_position'] == 'right' ) {
	$output .= '<i class="' . us_prepare_icon_class( $atts['icon'] ) . '"></i>';
}
if ( $atts['c_position'] == 'right' ) {
	$output .= '<div class="w-tabs-section-control"></div>';
}
$output .= '</div></div>';
$output .= '<div class="w-tabs-section-content"><div class="w-tabs-section-content-h i-cf">' . do_shortcode( $content ) . '</div></div>';
$output .= '</div>';

echo $output;
