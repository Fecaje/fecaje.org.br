<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_pricing
 *
 * @var $shortcode {String} Current shortcode name
 * @var $shortcode_base {String} The original called shortcode name (differs if called an alias)
 * @var $atts {Array} Shortcode attributes
 * @var $content {String} Shortcode's inner content
 */

$atts = shortcode_atts( array(
	/**
	 * @var string Table style: '1' / '2'
	 */
	'style' => '1',
	/**
	 * @var string Pricing table items
	 */
	'items' => '',
	/**
	 * @var string Extra class name
	 */
	'el_class' => '',
), $atts );

if ( empty( $atts['items'] ) ) {
	$atts['items'] = array();
} else {
	$atts['items'] = json_decode( urldecode( $atts['items'] ), TRUE );
	if ( ! is_array( $atts['items'] ) ) {
		$atts['items'] = array();
	}
}

$classes = ' style_' . $atts['style'];
$items_html = '';

foreach ( $atts['items'] as $index => $item ) {
	// Filtering the included items
	$item = shortcode_atts( array(
		/**
		 * @var string Item title
		 */
		'title' => '',
		/**
		 * @var string Item type: 'default' / 'featured'
		 */
		'type' => 'default',
		/**
		 * @var string Item price
		 */
		'price' => '',
		/**
		 * @var string Price substring
		 */
		'substring' => '',
		/**
		 * @var string Comma-separated list of features
		 */
		'features' => '',
		/**
		 * @var string Button label
		 */
		'btn_text' => '',
		/**
		 * @var string Button color: 'primary' / 'secondary' / 'light' / 'contrast' / 'black' / 'white'
		 */
		'btn_color' => '',
		/**
		 * @var string Button background color
		 */
		'btn_bg_color' => '',
		/**
		 * @var string Button text color
		 */
		'btn_text_color' => '',
		/**
		 * @var string Button size: 'small' / 'medium' / 'large'
		 */
		'btn_size' => '',
		/**
		 * @var string Button style: 'raised' / 'flat'
		 */
		'btn_style' => '',
		/**
		 * @var string Button icon
		 */
		'btn_icon' => '',
		/**
		 * @var string Icon position: 'left' / 'right'
		 */
		'btn_iconpos' => '',
		/**
		 * @var string Button link in a serialized format: 'url:http%3A%2F%2Fwordpress.org|title:WP%20Website|target:%20_blank'
		 */
		'btn_link' => '',
	), array_filter( $item ) );
	$items_html .= '<div class="w-pricing-item type_' . $item['type'] . '"><div class="w-pricing-item-h"><div class="w-pricing-item-header">';
	if ( ! empty( $item['title'] ) ) {
		$items_html .= '<h5 class="w-pricing-item-title">' . $item['title'] . '</h5>';
	}
	if ( ! empty( $item['price'] ) OR ! empty( $item['substring'] ) ) {
		$items_html .= '<div class="w-pricing-item-price">' . $item['price'];
		if ( ! empty( $item['substring'] ) ) {
			$items_html .= '<small>' . $item['substring'] . '</small>';
		}
		$items_html .= '</div>';
	}
	$items_html .= '</div>';
	if ( ! empty( $item['features'] ) ) {
		$items_html .= '<ul class="w-pricing-item-features">';
		$features = explode( "\n", trim( $item['features'] ) );
		foreach ( $features as $feature ) {
			$items_html .= '<li class="w-pricing-item-feature">' . $feature . '</li>';
		}
		$items_html .= '</ul>';
	}
	if ( ! empty( $item['btn_text'] ) ) {
		$btn_classes = ' style_' . $item['btn_style'] . ' size_' . $item['btn_size'];
		$btn_classes .= ' color_' . $item['btn_color'];
		$btn_inner_css = '';
		if ( $item['btn_color'] == 'custom' ) {
			if ( $item['btn_bg_color'] != '' ) {
				$btn_inner_css .= 'background-color: ' . $item['btn_bg_color'] . ';';
			}
			if ( $item['btn_text_color'] != '' ) {
				$btn_inner_css .= 'color: ' . $item['btn_text_color'] . ';';
			}
		}
		$icon_html = '';
		$item['btn_icon'] = trim( $item['btn_icon'] );
		if ( $item['btn_icon'] != '' ) {
			$icon_html = '<i class="' . us_prepare_icon_class( $item['btn_icon'] ) . '"></i>';
			$btn_classes .= ' icon_at' . $item['btn_iconpos'];
		} else {
			$btn_classes .= ' icon_none';
		}
		$btn_link = us_vc_build_link( $item['btn_link'] );
		$btn_link_target = ( $btn_link['target'] == '_blank' ) ? ' target="_blank"' : '';
		$btn_link_title = empty( $btn_link['title'] ) ? '' : ( ' title="' . esc_attr( $btn_link['title'] ) . '"' );
		$items_html .= '<div class="w-pricing-item-footer">';
		$items_html .= '<a class="w-btn' . $btn_classes . '" href="' . esc_url( $btn_link['url'] ) . '"' . $btn_link_target . $btn_link_title;
		if ( ! empty( $btn_inner_css ) ) {
			$items_html .= ' style="' . $btn_inner_css . '"';
		}
		$items_html .= '>';
		$items_html .= $icon_html . '<label>' . $item['btn_text'] . '</label></a>';
		$items_html .= '</div>';
	}
	$items_html .= '</div></div>';
}

$output = '<div class="w-pricing' . $classes . '">' . $items_html . '</div>';
echo $output;
