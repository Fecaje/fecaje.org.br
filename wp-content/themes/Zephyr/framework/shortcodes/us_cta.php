<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_cta
 *
 * @var $shortcode {String} Current shortcode name
 * @var $shortcode_base {String} The original called shortcode name (differs if called an alias)
 * @var $atts {Array} Shortcode attributes
 * @var $content {String} Shortcode's inner content
 */

$atts = shortcode_atts( array(
	/**
	 * @var string ActionBox title
	 */
	'title' => __( 'This is ActionBox', 'us' ),
	/**
	 * @var string ActionBox text
	 */
	'message' => '',
	/**
	 * @var string ActionBox color style: 'primary' / 'secondary' / 'alternate' / 'custom'
	 */
	'color' => 'alternate',
	/**
	 * @var string Background color
	 */
	'bg_color' => '',
	/**
	 * @var string Text color
	 */
	'text_color' => '',
	/**
	 * @var string Button(s) location: 'right' / 'bottom'
	 */
	'controls' => 'right',
	/**
	 * @var string Button label
	 */
	'btn_label' => __( 'Click Me', 'us' ),
	'btn2_label' => __( 'Or Me', 'us' ),
	/**
	 * @var string Button link in a serialized format: 'url:http%3A%2F%2Fwordpress.org|title:WP%20Website|target:%20_blank'
	 */
	'btn_link' => '',
	'btn2_link' => '',
	/**
	 * @var string Button color: 'primary' / 'secondary' / 'light' / 'contrast' / 'black' / 'white'
	 */
	'btn_color' => 'primary',
	'btn2_color' => 'secondary',
	/**
	 * @var string Button background color
	 */
	'btn_bg_color' => '',
	'btn2_bg_color' => '',
	/**
	 * @var string Button text color
	 */
	'btn_text_color' => '',
	'btn2_text_color' => '',
	/**
	 * @var string Button style: 'raised' / 'flat'
	 */
	'btn_style' => 'raised',
	'btn2_style' => 'raised',
	/**
	 * @var string Button size: '' / 'large'
	 */
	'btn_size' => 'medium',
	'btn2_size' => 'medium',
	/**
	 * @var string Button icon
	 */
	'btn_icon' => '',
	'btn2_icon' => '',
	/**
	 * @var string Button icon position: 'left' / 'right'
	 */
	'btn_iconpos' => 'left',
	'btn2_iconpos' => 'left',
	/**
	 * @var bool Has second button?
	 */
	'second_button' => FALSE,
	/**
	 * @var string Extra class name
	 */
	'el_class' => '',
), $atts );

// .w-actionbox container additional classes and inner CSS-styles
$classes = '';
$inner_css = '';

$classes .= ' color_' . $atts['color'];
if ( $atts['color'] == 'custom' ) {
	if ( $atts['bg_color'] != '' ) {
		$inner_css .= 'background-color:' . $atts['bg_color'] . ';';
	}
	if ( $atts['text_color'] != '' ) {
		$inner_css .= 'color:' . $atts['text_color'] . ';';
	}
}
$classes .= ' controls_' . $atts['controls'];

if ( ! empty( $atts['el_class'] ) ) {
	$classes .= ' ' . $atts['el_class'];
}

// Button keys that will be parsed
$btn_prefixes = array( 'btn' );
if ( $atts['second_button'] ) {
	$btn_prefixes[] = 'btn2';
}

// Preparing buttons
$buttons = array();
foreach ( $btn_prefixes as $prefix ) {
	if ( empty( $atts[ $prefix . '_label' ] ) ) {
		continue;
	}
	$btn_classes = '';
	$btn_inner_css = '';
	$btn_classes .= ' color_' . $atts[ $prefix . '_color' ];
	if ( $atts[ $prefix . '_color' ] == 'custom' ) {
		if ( $atts[ $prefix . '_bg_color' ] != '' ) {
			$btn_inner_css .= 'background-color: ' . $atts[ $prefix . '_bg_color' ] . ';';
		}
		if ( $atts[ $prefix . '_text_color' ] != '' ) {
			$btn_inner_css .= 'color: ' . $atts[ $prefix . '_text_color' ] . ';';
		}
	}
	$btn_classes .= ' style_' . $atts[ $prefix . '_style' ];
	$btn_classes .= ' size_' . $atts[ $prefix . '_size' ];

	$icon_html = '';
	if ( ! empty( $atts[ $prefix . '_icon' ] ) ) {
		$btn_classes .= ' icon_at' . $atts[ $prefix . '_iconpos' ];
		$icon_html = '<i class="' . us_prepare_icon_class( $atts[ $prefix . '_icon' ] ) . '"></i>';
	} else {
		$btn_classes .= ' icon_none';
	}

	$link = us_vc_build_link( $atts[ $prefix . '_link' ] );

	$buttons[ $prefix ] = '<a class="w-btn' . $btn_classes . '" href="' . $link['url'] . '"';
	$buttons[ $prefix ] .= ( $link['target'] == '_blank' ) ? ' target="_blank"' : '';
	$buttons[ $prefix ] .= empty( $link['title'] ) ? '' : ( ' title="' . esc_attr( $link['title'] ) . '"' );
	if ( ! empty( $btn_inner_css ) ) {
		$buttons[ $prefix ] .= ' style="' . $btn_inner_css . '"';
	}
	$buttons[ $prefix ] .= '>' . $icon_html . '<label>' . $atts[ $prefix . '_label' ] . '</label></a>';
}

if ( ! empty( $inner_css ) ) {
	$inner_css = ' style="' . $inner_css . '"';
}

$output = '<div class="w-actionbox' . $classes . '"' . $inner_css . '><div class="w-actionbox-text">';
if ( ! empty( $atts['title'] ) ) {
	$output .= '<h2>' . html_entity_decode( $atts['title'] ) . '</h2>';
}
if ( ! empty( $atts['message'] ) ) {
	$output .= '<p>' . html_entity_decode( $atts['message'] ) . '</p>';
}
$output .= '</div>';

if ( ! empty( $buttons ) ) {
	$output .= '<div class="w-actionbox-controls">' . implode( '', $buttons ) . '</div>';
}

$output .= '</div>';
echo $output;
