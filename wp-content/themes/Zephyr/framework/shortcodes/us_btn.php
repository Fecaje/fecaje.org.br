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
	 * @var string Button label
	 */
	'text' => __( 'Click Me', 'us' ),
	/**
	 * @var string Button link in a serialized format: 'url:http%3A%2F%2Fwordpress.org|title:WP%20Website|target:%20_blank'
	 */
	'link' => '',
	/**
	 * @var string Button color: 'primary' / 'secondary' / 'light' / 'contrast' / 'black' / 'white' / 'custom'
	 */
	'color' => 'primary',
	/**
	 * @var string Button Background Color
	 */
	'bg_color' => '',
	/**
	 * @var string Button Text Color
	 */
	'text_color' => '',
	/**
	 * @var string Button style: 'raised' / 'flat'
	 */
	'style' => 'raised',
	/**
	 * @var string Button icon
	 */
	'icon' => '',
	/**
	 * @var string Icon position: 'left' / 'right'
	 */
	'iconpos' => 'left',
	/**
	 * @var string Button size: 'small' / 'medium' / 'large'
	 */
	'size' => 'medium',
	/**
	 * @var string Button alignment: 'left' / 'center' / 'right'
	 */
	'align' => 'left',
	/**
	 * @var string Extra class name
	 */
	'el_class' => '',
), $atts );

// .w-btn container additional classes and inner CSS-styles
$classes = '';
$inner_css = '';

$classes .= ' style_' . $atts['style'] . ' size_' . $atts['size'];

$classes .= ' color_' . $atts['color'];
if ( $atts['color'] == 'custom' ) {
	if ( $atts['bg_color'] != '' ) {
		$inner_css .= 'background-color: ' . $atts['bg_color'] . ';';
	}
	if ( $atts['text_color'] != '' ) {
		$inner_css .= 'color: ' . $atts['text_color'] . ';';
	}
}

$link = us_vc_build_link( $atts['link'] );

$icon_html = '';
$atts['icon'] = trim( $atts['icon'] );
if ( $atts['icon'] != '' ) {
	$icon_html = '<i class="' . us_prepare_icon_class( $atts['icon'] ) . '"></i>';
	$classes .= ' icon_at' . $atts['iconpos'];
} else {
	$classes .= ' icon_none';
}

$link_target = ( $link['target'] == '_blank' ) ? ' target="_blank"' : '';
$link_title = empty( $link['title'] ) ? '' : ( ' title="' . esc_attr( $link['title'] ) . '"' );

// Additional classes
if ( $atts['el_class'] != '' ) {
	$classes .= ' ' . $atts['el_class'];
}

$output = '<div class="w-btn-wrapper align_' . $atts['align'] . '">';
$output .= '<a class="w-btn' . $classes . '" href="' . esc_url( $link['url'] ) . '"' . $link_target . $link_title;
if ( ! empty( $inner_css ) ) {
	$output .= ' style="' . $inner_css . '"';
}
$output .= '>';
$output .= $icon_html;
$output .= '<label>' . $atts['text'] . '</label>';
$output .= '</a></div>';

echo $output;
