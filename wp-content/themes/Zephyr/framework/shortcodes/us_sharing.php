<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_sharing
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var $shortcode string Current shortcode name
 * @var $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var $content string Shortcode's inner content
 * @var $atts array Shortcode attributes
 *
 * @param $atts ['type'] string Type: 'simple' / 'solid' / 'outlined' / 'fixed'
 * @param $atts ['align'] string Alignment: 'left' / 'center' / 'right'
 * @param $atts ['color'] string Color Style: 'default' / 'primary' / 'secondary'
 * @param $atts ['counters'] string Share Counters: 'show' / 'hide'
 * @param $atts ['facebook'] bool Is Facebook button available?
 * @param $atts ['twitter'] bool Is Twitter button available?
 * @param $atts ['gplus'] bool Is Google+ button available?
 * @param $atts ['linkedin'] bool Is LinkedIn button available?
 * @param $atts ['pinterest'] bool Is Pinterest button available?
 * @param $atts ['url'] string Sharing URL
 * @param $atts ['el_class'] string Extra class name
 */

$atts = us_shortcode_atts( $atts, 'us_sharing' );

// The list of available sharing providers and additional in-shortcode data
$providers = array(
	'facebook' => array(
		'title' => __( 'Share this', 'us' ),
	),
	'twitter' => array(
		'title' => __( 'Tweet this', 'us' ),
	),
	'gplus' => array(
		'title' => __( 'Share this', 'us' ),
	),
	'linkedin' => array(
		'title' => __( 'Share this', 'us' ),
	),
	'pinterest' => array(
		'title' => __( 'Pin this', 'us' ),
	),
);
// Keeping only the actually used providers
foreach ( $providers as $provider => $provider_data ) {
	if ( ! $atts[ $provider ] ) {
		unset( $providers[ $provider ] );
	}
}
if ( empty( $providers ) ) {
	return;
}

// .w-sharing container additional classes
$classes = '';
$classes .= ' type_' . $atts['type'] . ' align_' . $atts['align'] . ' color_' . $atts['color'];

if ( empty( $atts['url'] ) ) {
	// Using the current page URL
	$atts['url'] = site_url( $_SERVER['REQUEST_URI'] );
}

if ( $atts['counters'] == 'show' ) {
	$counts = us_get_sharing_counts( $atts['url'], array_keys( $providers ) );
}

$output = '<div class="w-sharing' . $classes . '">';
foreach ( $providers as $provider => $provider_data ) {
	$output .= '<a class="w-sharing-item ' . $provider . '" title="' . esc_attr( $provider_data['title'] ) . '" href="javascript:void(0)">';
	$output .= '<span class="w-sharing-icon"></span>';
	if ( $atts['counters'] == 'show' AND isset( $counts[ $provider ] ) AND $counts[ $provider ] != 0 ) {
		$output .= '<span class="w-sharing-count">' . $counts[ $provider ] . '</span>';
	}
	$output .= '</a>';
}
$output .= '</div>';

echo $output;
