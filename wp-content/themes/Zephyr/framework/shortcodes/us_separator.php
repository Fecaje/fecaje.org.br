<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_separator
 *
 * @var $shortcode {String} Current shortcode name
 * @var $shortcode_base {String} The original called shortcode name (differs if called an alias)
 * @var $atts {Array} Shortcode attributes
 * @var $content {String} Shortcode's inner content
 */

$atts = shortcode_atts( array(
	/**
	 * @var string Separator type: 'default' / 'fullwidth' / 'short' / 'invisible'
	 */
	'type' => 'default',
	/**
	 * @var string Separator size: 'small' / 'medium' / 'large' / 'huge'
	 */
	'size' => 'medium',
	/**
	 * @var string Line thickness: '1' / '2' / '3' / '4' / '5'
	 */
	'thick' => '1',
	/**
	 * @var string Line style: 'solid' / 'dashed' / 'dotted' / 'double'
	 */
	'style' => 'solid',
	/**
	 * @var string Color style: 'border' / 'primary' / 'secondary' / 'custom'
	 */
	'color' => 'border',
	/**
	 * @var string Border color value
	 */
	'bdcolor' => '',
	/**
	 * @var string Icon
	 */
	'icon' => '',
	/**
	 * @var string Text
	 */
	'text' => '',
	/**
	 * @var string Extra class name
	 */
	'el_class' => '',
), $atts );

$classes = '';
$inner_css = '';

$classes .= ' type_' . $atts['type'] . ' size_' . $atts['size'] . ' thick_' . $atts['thick'] . ' style_' . $atts['style'];

$classes .= ' color_' . $atts['color'];
if ( $atts['color'] == 'custom' AND ! empty( $atts['bdcolor'] ) ) {
	$inner_css .= 'border-color: ' . $atts['bdcolor'] . '; color: ' . $atts['bdcolor'] . ';';
}

$inner_html = '';
$atts['icon'] = trim( $atts['icon'] );
if ( ! empty( $atts['icon'] ) ) {
	$classes .= ' cont_icon';
	$inner_html = '<i class="' . us_prepare_icon_class( $atts['icon'] ) . '"></i>';
} elseif ( ! empty( $atts['text'] ) ) {
	$classes .= ' cont_text';
	$inner_html = '<h6>' . $atts['text'] . '</h6>';
} else {
	$classes .= ' cont_none';
}

if ( ! empty( $atts['el_class'] ) ) {
	$classes .= ' ' . $atts['el_class'];
}

$output = '<div class="w-separator' . $classes . '"';
if ( ! empty( $inner_css ) ) {
	$output .= ' style="' . $inner_css . '"';
}
$output .= '><span class="w-separator-h">' . $inner_html . '</span></div>';

echo $output;
