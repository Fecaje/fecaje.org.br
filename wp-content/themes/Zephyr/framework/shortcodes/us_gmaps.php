<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_gmaps
 *
 * @var $shortcode {String} Current shortcode name
 * @var $shortcode_base {String} The original called shortcode name (differs if called an alias)
 * @var $atts {Array} Shortcode attributes
 * @var $content {String} Shortcode's inner content
 *
 * @filter 'us_gmaps_js_options' Allows to filter options, passed to JavaScript
 */
$atts = shortcode_atts( array(
	/**
	 * @var string Marker address
	 */
	'marker_address' => '1600 Amphitheatre Parkway, Mountain View, CA 94043, United States',
	'marker2_address' => '',
	'marker3_address' => '',
	'marker4_address' => '',
	'marker5_address' => '',
	/**
	 * @var string Marker text
	 */
	'marker_text' => '',
	'marker2_text' => '',
	'marker3_text' => '',
	'marker4_text' => '',
	'marker5_text' => '',
	/**
	 * @var bool Add more markers?
	 */
	'add_markers' => FALSE,
	/**
	 * @var int Custom marker image (from WordPress media)
	 */
	'custom_marker_img' => FALSE,
	/**
	 * @var int Custom marker size
	 */
	'custom_marker_size' => 20,
	/**
	 * @var int Map height
	 */
	'height' => 400,
	/**
	 * @var string Map type: 'roadmap' / 'satellite' / 'hybrid' / 'terrain'
	 */
	'type' => 'roadmap',
	/**
	 * @var int Map zoom
	 */
	'zoom' => 14,
	/**
	 * @var float Map latitude
	 */
	'latitude' => '',
	/**
	 * @var float Map longitude
	 */
	'longitude' => '',
	/**
	 * @var string Extra class name
	 */
	'el_class' => '',
), $atts );

// Decoding base64-encoded HTML attributes
foreach ( array( 'marker_text', 'marker2_text', 'marker3_text', 'marker4_text', 'marker5_text' ) as $mkey ) {
	if ( ! empty( $atts[ $mkey ] ) ) {
		$atts[ $mkey ] = rawurldecode( base64_decode( $atts[ $mkey ] ) );
	}
}

$classes = '';
$inner_css = '';
$script_options = array();

if ( $atts['el_class'] != '' ) {
	$classes .= ' ' . $atts['el_class'];
}

if ( ! in_array( $atts['custom_marker_size'], array( 20, 30, 40, 50, 60, 70, 80 ) ) ) {
	$atts['custom_marker_size'] = 20;
}

if ( ! in_array( $atts['zoom'], array( 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20 ) ) ) {
	$atts['zoom'] = 14;
}

global $us_gmaps_index;
// Map indexes start from 1
$us_gmaps_index = isset( $us_gmaps_index ) ? ( $us_gmaps_index + 1 ) : 1;

// Coords-based location
if ( ! empty( $atts['latitude'] ) AND ! empty( $atts['longitude'] ) ) {
	$script_options['latitude'] = $atts['latitude'];
	$script_options['longitude'] = $atts['longitude'];
} elseif ( $atts['marker_address'] != '' ) {
	$script_options['address'] = $atts['marker_address'];
} else {
	return NULL;
}
$script_options['markers'] = array(
	array_merge( $script_options, array(
		'html' => $atts['marker_text'],
	) )
);

if ( $atts['add_markers'] ) {
	foreach ( array( 'marker2', 'marker3', 'marker4', 'marker5' ) as $mkey ) {
		if ( ! empty( $atts[ $mkey . '_text' ] ) AND ! empty( $atts[ $mkey . '_address' ] ) ) {
			$script_options['markers'][] = array(
				'html' => $atts[ $mkey . '_text' ],
				'address' => $atts[ $mkey . '_address' ],
			);
		}
	}
}

if ( ! empty( $atts['zoom'] ) ) {
	$script_options['zoom'] = intval( $atts['zoom'] );
}
if ( ! empty( $atts['type'] ) ) {
	$atts['type'] = strtoupper( $atts['type'] );
	if ( in_array( $atts['type'], array( 'ROADMAP', 'SATELLITE', 'HYBRID', 'TERRAIN' ) ) ) {
		$script_options['maptype'] = $atts['type'];
	}
}

$custom_marker_options = '';

if ( $atts['custom_marker_img'] != '' ) {
	if ( is_numeric( $atts['custom_marker_img'] ) ) {
		$atts['custom_marker_img'] = wp_get_attachment_image_src( intval( $atts['custom_marker_img'] ), 'thumbnail' );
		if ( $atts['custom_marker_img'] != NULL ) {
			$atts['custom_marker_img'] = $atts['custom_marker_img'][0];
		}
	}
	$atts['custom_marker_size'] = intval( $atts['custom_marker_size'] );
	$script_options['icon'] = array(
		'image' => $atts['custom_marker_img'],
		'iconsize' => array( $atts['custom_marker_size'], $atts['custom_marker_size'] ),
		'iconanchor' => array( ceil( $atts['custom_marker_size'] / 2 ), $atts['custom_marker_size'] ),
	);
}

if ( empty( $atts['height'] ) ) {
	$atts['height'] = 400;
}
$inner_css = ' style="height: ' . $atts['height'] . 'px"';

// Enqueued the script only once
wp_enqueue_script( 'us-google-maps' );
wp_enqueue_script( 'us-gmap' );

$script_options = apply_filters( 'us_gmaps_js_options', $script_options, get_the_ID(), $us_gmaps_index );

$output = '<div class="w-map' . $classes . '" id="us_map_' . $us_gmaps_index . '"' . $inner_css . '>';
$output .= '<div class="w-map-h"></div>';
$output .= '<div class="w-map-json"' . us_pass_data_to_js( $script_options ) . '></div>';
$output .= '</div>';
echo $output;
