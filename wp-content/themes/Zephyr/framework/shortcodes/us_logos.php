<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_logos
 *
 * @var $shortcode {String} Current shortcode name
 * @var $shortcode_base {String} The original called shortcode name (differs if called an alias)
 * @var $atts {Array} Shortcode attributes
 * @var $content {String} Shortcode's inner content
 */

$atts = shortcode_atts( array(
	/**
	 * @var int Quantity of displayed logos
	 */
	'columns' => 5,
	/**
	 * @var string Hover style: '1' / '2'
	 */
	'style' => '1',
	/**
	 * @var bool Show navigation arrows?
	 */
	'arrows' => FALSE,
	/**
	 * @var bool Enable auto rotation?
	 */
	'auto_scroll' => FALSE,
	/**
	 * @var int Rotation interval
	 */
	'interval' => 3,
	/**
	 * @var string Items order: '' / 'rand'
	 */
	'orderby' => '',
	/**
	 * @var string Extra class name
	 */
	'el_class' => '',
), $atts );

$classes = '';

$atts['columns'] = intval( $atts['columns'] );
if ( $atts['columns'] < 1 OR $atts['columns'] > 6 ) {
	$atts['columns'] = 5;
}

$classes .= ' type_carousel style_' . $atts['style'];

$classes .= ' nav_' . ( $atts['arrows'] ? 'arrows' : 'none' );

if ( $atts['el_class'] != '' ) {
	$classes .= ' ' . $atts['el_class'];
}

// We need owl script for this
wp_enqueue_style( 'us-owl' );
wp_enqueue_script( 'us-owl' );

$output = '<div class="w-logos' . $classes . '"><div class="w-logos-list"';
$output .= ' data-items="' . $atts['columns'] . '"';
$output .= ' data-autoplay="' . intval( ! ! $atts['auto_scroll'] ) . '"';
$output .= ' data-timeout="' . intval( $atts['interval'] * 1000 ) . '"';
$output .= ' data-nav="' . intval( ! ! $atts['arrows'] ) . '"';
$output .= '>';

$query_args = array(
	'post_type' => 'us_client',
	'nopaging' => TRUE,
);
if ( $atts['orderby'] == 'rand' ) {
	$query_args['orderby'] = 'rand';
}
us_open_wp_query_context();
global $wp_query;
$wp_query = new WP_Query( $query_args );
while ( have_posts() ){
	the_post();
	if ( has_post_thumbnail() ) {
		$tnail = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'tnail-3x2' );
		if ( $tnail ) {
			$output .= '<div class="w-logos-item">';
			$url = rwmb_meta( 'us_client_url' );
			if ( rwmb_meta( 'us_client_url' ) != '' ) {
				$target = rwmb_meta( 'us_client_new_tab' ) ? ' target="_blank"' : '';
				$output .= '<a class="w-logos-item-h" href="' . esc_url( $url ) . '"' . $target . '>';
				$output .= '<img src="' . $tnail[0] . '" width="' . $tnail[1] . '" height="' . $tnail[2] . '" alt=""></a>';
			} else {
				$output .= '<span class="w-logos-item-h"><img src="' . $tnail[0] . '" width="' . $tnail[1] . '" height="' . $tnail[2] . '" alt=""></span>';
			}
			$output .= '</div>';
		}
	}
}

$output .= '</div></div>';
us_close_wp_query_context();

echo $output;
