<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_testimonial
 *
 * @var $shortcode {String} Current shortcode name
 * @var $shortcode_base {String} The original called shortcode name (differs if called an alias)
 * @var $atts {Array} Shortcode attributes
 * @var $content {String} Shortcode's inner content
 */

$atts = shortcode_atts( array(
	/**
	 * @var string Quote style: '1' / '2'
	 */
	'style' => '1',
	/**
	 * @var string Author name
	 */
	'author' => __( 'Jon Show', 'us' ),
	/**
	 * @var string Author subtitle
	 */
	'company' => __( 'Lord Commander', 'us' ),
	/**
	 * @var int Author photo (ID from WP media library)
	 */
	'img' => '',
	/**
	 * @var string Extra class name
	 */
	'el_class' => '',
), $atts );

$classes = '';

if ( $atts['style'] == '' ) {
	$atts['style'] = '1';
}
$classes .= ' style_' . $atts['style'];

if ( $atts['el_class'] != '' ) {
	$classes .= ' ' . $atts['el_class'];
}

$img_id = intval( $atts['img'] );
$image_html = '';
if ( $img_id AND ( $image = wp_get_attachment_image_src( $img_id, 'thumbnail' ) ) ) {
	$image_html = '<img src="' . $image[0] . '" width="' . $image[1] . '" height="' . $image[2] . '" alt="">';
}

$output = '<div class="w-testimonial' . $classes . '"><blockquote>';
$output .= '<q class="w-testimonial-text">' . do_shortcode( $content ) . '</q>';
if ( ! empty( $image_html ) OR ! empty( $atts['author'] ) OR ! empty( $atts['company'] ) ) {
	$output .= '<div class="w-testimonial-person">' . $image_html;
	if ( ! empty( $atts['author'] ) ) {
		$output .= '<span class="w-testimonial-person-name">' . $atts['author'] . '</span>';
	}
	if ( ! empty( $atts['company'] ) ) {
		$output .= '<span class="w-testimonial-person-meta">' . $atts['company'] . '</span>';
	}
	$output .= '</div>';
}
$output .= '</blockquote></div>';

echo $output;
