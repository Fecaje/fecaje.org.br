<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_person
 *
 * @var $shortcode {String} Current shortcode name
 * @var $shortcode_base {String} The original called shortcode name (differs if called an alias)
 * @var $atts {Array} Shortcode attributes
 * @var $content {String} Shortcode's inner content
 */

$atts = shortcode_atts( array(
	/**
	 * @var string Name
	 */
	'name' => __( 'Jon Snow', 'us' ),
	/**
	 * @var string Role
	 */
	'role' => __( 'Lord Commander', 'us' ),
	/**
	 * @var int Photo (from WP Media Library)
	 */
	'image' => '',
	/**
	 * @var string Layout style: '1' / '2'
	 */
	'style' => '1',
	/**
	 * @var string Link in a serialized format: 'url:http%3A%2F%2Fwordpress.org|title:WP%20Website|target:%20_blank'
	 */
	'link' => '',
	/**
	 * @var string Email
	 */
	'email' => '',
	/**
	 * @var string Facebook link
	 */
	'facebook' => '',
	/**
	 * @var string Twitter link
	 */
	'twitter' => '',
	/**
	 * @var string Google+ link
	 */
	'google_plus' => '',
	/**
	 * @var string LinkedIn link
	 */
	'linkedin' => '',
	/**
	 * @var string Skype link
	 */
	'skype' => '',
	/**
	 * @var string Custom icon
	 */
	'custom_icon' => '',
	/**
	 * @var string Custom link
	 */
	'custom_link' => '',
	/**
	 * @var string Extra class name
	 */
	'el_class' => '',
), $atts );

$classes = '';

$classes .= ' style_' . $atts['style'];

$img_html = '';
if ( is_numeric( $atts['image'] ) ) {
	$img = wp_get_attachment_image_src( intval( $atts['image'] ), 'tnail-1x1' );
	if ( $img !== FALSE ) {
		$img_html = '<img src="' . $img[0] . '" width="' . $img[1] . '" height="' . $img[2] . '" alt="">';
	}
} else {
	// Direct link to image is set in the shortcode attribute
	$img_html = '<img src="' . $atts['image'] . '" alt="">';
}

$links_html = '';
if ( ! empty( $atts['email'] ) ) {
	$links_html .= '<a class="w-person-links-item" href="mailto:' . $atts['email'] . '"><i class="fa fa-envelope"></i></a>';
}
if ( ! empty( $atts['facebook'] ) ) {
	$links_html .= '<a class="w-person-links-item" href="' . esc_url( $atts['facebook'] ) . '" target="_blank"><i class="fa fa-facebook"></i></a>';
}
if ( ! empty( $atts['twitter'] ) ) {
	$links_html .= '<a class="w-person-links-item" href="' . esc_url( $atts['twitter'] ) . '" target="_blank"><i class="fa fa-twitter"></i></a>';
}
if ( ! empty( $atts['google_plus'] ) ) {
	$links_html .= '<a class="w-person-links-item" href="' . esc_url( $atts['google_plus'] ) . '" target="_blank"><i class="fa fa-google-plus"></i></a>';
}
if ( ! empty( $atts['linkedin'] ) ) {
	$links_html .= '<a class="w-person-links-item" href="' . esc_url( $atts['linkedin'] ) . '" target="_blank"><i class="fa fa-linkedin"></i></a>';
}
if ( ! empty( $atts['skype'] ) ) {
	$links_html .= '<a class="w-person-links-item" href="' . esc_url( $atts['skype'] ) . '" target="_blank"><i class="fa fa-skype"></i></a>';
}
$atts['custom_icon'] = trim( $atts['custom_icon'] );
if ( ! empty( $atts['custom_icon'] ) AND ! empty( $atts['custom_link'] ) ) {
	$links_html .= '<a class="w-person-links-item" href="' . esc_url( $atts['custom_link'] ) . '" target="_blank"><i class="' . us_prepare_icon_class( $atts['custom_icon'] ) . '"></i></a>';
}
if ( ! empty( $links_html ) ) {
	$classes .= ' with_icons';
	$links_html = '<div class="w-person-links"><div class="w-person-links-list">' . $links_html . '</div></div>';
}

$link_start = $link_end = '';
$link = us_vc_build_link( $atts['link'] );

if ( ! empty( $link['url'] ) ) {
	$link_target = ( $link['target'] == '_blank' ) ? ' target="_blank"' : '';
	$link_title = empty( $link['title'] ) ? '' : ( ' title="' . esc_attr( $link['title'] ) . '"' );
	$link_start = '<a class="w-person-link" href="' . esc_url( $link['url'] ) . '"' . $link_target . $link_title . '>';
	$link_end = '</a>';
}

$role_part = '';

if ( ! empty( $atts['el_class'] ) ) {
	$classes .= ' ' . $atts['el_class'];
}

$output = '<div class="w-person' . $classes . '"><div class="w-person-image">';
$output .= $link_start . $img_html . $link_end . '</div><div class="w-person-content">';
if ( ! empty( $atts['name'] ) ) {
	$output .= $link_start . '<h4 class="w-person-name"><span>' . $atts['name'] . '</span></h4>' . $link_end;
}
if ( ! empty( $atts['role'] ) ) {
	$output .= '<div class="w-person-role">' . $atts['role'] . '</div>';
}
if ( ! empty( $content ) ) {
	$output .= '<div class="w-person-description">' . do_shortcode( $content ) . '</div>';
}
$output .= $links_html . '</div></div>';

echo $output;
