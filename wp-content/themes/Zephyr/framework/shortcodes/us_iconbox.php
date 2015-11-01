<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_iconbox
 *
 * @var $shortcode {String} Current shortcode name
 * @var $shortcode_base {String} The original called shortcode name (differs if called an alias)
 * @var $atts {Array} Shortcode attributes
 * @var $content {String} Shortcode's inner content
 */

$atts = shortcode_atts( array(
	/**
	 * @var string Icon
	 */
	'icon' => 'star',
	/**
	 * @var string Icon style: 'default' / 'circle'
	 */
	'style' => 'default',
	/**
	 * @var string Icon color: 'primary' / 'secondary' / 'light' / 'contrast' / 'custom'
	 */
	'color' => 'primary',
	/**
	 * @var string Icon color value
	 */
	'icon_color' => FALSE,
	/**
	 * @var string Icon circle color
	 */
	'bg_color' => FALSE,
	/**
	 * @var string Icon position: 'top' / 'left'
	 */
	'iconpos' => 'top',
	/**
	 * @var string Icon size: 'tiny' / 'small' / 'medium' / 'large' / 'huge'
	 */
	'size' => 'medium',
	/**
	 * @var string Title
	 */
	'title' => '',
	/**
	 * @var string Link in a serialized format: 'url:http%3A%2F%2Fwordpress.org|title:WP%20Website|target:%20_blank'
	 */
	'link' => '',
	/**
	 * @var int Icon image (from WordPress media)
	 */
	'img' => '',
	/**
	 * @var string Extra class name
	 */
	'el_class' => '',
), $atts );

$classes = '';
$icon_inner_css = '';

$classes .= ' iconpos_' . $atts['iconpos'];
$classes .= ' size_' . $atts['size'];
$classes .= ' style_' . $atts['style'];

$classes .= ' color_' . $atts['color'];
if ( $atts['color'] == 'custom' ) {
	if ( $atts['bg_color'] != '' ) {
		$icon_inner_css .= 'background-color: ' . $atts['bg_color'] . ';border-color: ' . $atts['bg_color'] . ';';
	}
	if ( $atts['icon_color'] != '' ) {
		$icon_inner_css .= 'color: ' . $atts['icon_color'] . ';';
	}
}

if ( $atts['title'] == '' AND $content == '' ) {
	$classes .= ' no_text';
}

// If image is set, using it as an icon
$icon_html = '';
if ( $atts['img'] != '' ) {
	$classes .= ' icontype_img';
	if ( is_numeric( $atts['img'] ) ) {
		$img = wp_get_attachment_image_src( intval( $atts['img'] ), 'full' );
		if ( $img !== FALSE ) {
			$icon_html = '<img src="' . $img[0] . '" width="' . $img[1] . '" height="' . $img[2] . '" alt="">';
		}
	} else {
		// Direct link to image is set in the shortcode attribute
		$icon_html = '<img src="' . $atts['img'] . '" alt="">';
	}
} else {
	$atts['icon'] = trim( $atts['icon'] );
	if ( $atts['icon'] != '' ) {
		$icon_html = '<i class="' . us_prepare_icon_class( $atts['icon'] ) . '"></i>';
	}
}

$link_opener = '';
$link_closer = '';
$link = us_vc_build_link( $atts['link'] );
if ( ! empty( $link['url'] ) ) {
	$link_target = ( $link['target'] == '_blank' ) ? ' target="_blank"' : '';
	$link_title = empty( $link['title'] ) ? '' : ( ' title="' . esc_attr( $link['title'] ) . '"' );
	$link_opener = '<a class="w-iconbox-link" href="' . esc_url( $link['url'] ) . '"' . $link_target . $link_title . '>';
	$link_closer = '</a>';
}

if ( ! empty( $atts['el_class'] ) ) {
	$classes .= ' ' . $atts['el_class'];
}

if ( $icon_inner_css != '' ) {
	$icon_inner_css = ' style="' . $icon_inner_css . '"';
}

$output = '<div class="w-iconbox' . $classes . '">';
$output .= $link_opener;
$output .= '<div class="w-iconbox-icon"' . $icon_inner_css . '>' . $icon_html . '</div>';
if ( $atts['title'] != '' ) {
	$output .= '<h4 class="w-iconbox-title">' . $atts['title'] . '</h4>';
}
$output .= $link_closer;
if ( $content != '' ) {
	$output .= '<div class="w-iconbox-text">' . do_shortcode( $content ) . '</div>';
}
$output .= '</div>';

echo $output;
