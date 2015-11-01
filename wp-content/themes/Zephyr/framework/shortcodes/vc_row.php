<?php defined('ABSPATH') OR die('This script cannot be accessed directly.');

/**
 * Shortcode: vc_row
 *
 * Overloaded by UpSolution custom implementation to allow creating fullwidth sections and provide lots of additional
 * features.
 *
 * @var $shortcode {String} Current shortcode name
 * @var $shortcode_base {String} The original called shortcode name (differs if called an alias)
 * @var $atts {Array} Shortcode attributes
 * @var $content {String} Shortcode's inner content
 */

$atts = shortcode_atts( array(
	/**
	 * @var string Columns type: 'small' / 'medium' / 'large' / 'none'
	 */
	'columns_type' => 'medium',
	/**
	 * @var string Height type. Possible values: 'small' / 'medium' / 'large' / 'auto' /  'full'
	 */
	'height' => 'medium',
	/**
	 * @var string Vertical align for full-height sections: '' / 'center'
	 */
	'valign' => '',
	/**
	 * @var string Section width: '' / 'full'
	 */
	'width' => '',
	/**
	 * @var string Color scheme: '' / 'alternate' / 'primary' / 'secondary' / 'custom'
	 */
	'color_scheme' => '',
	/**
	 * @var string
	 */
	'us_bg_color' => '',
	/**
	 * @var string
	 */
	'us_text_color' => '',
	/**
	 * @var int Background image ID (from WordPress media)
	 */
	'us_bg_image' => '',
	/**
	 * @var string Background size: 'cover' / 'contain' / 'initial'
	 */
	'us_bg_size' => 'cover',
	/**
	 * @var string Parallax type: '' / 'vertical' / 'horizontal' / 'still'
	 */
	'us_bg_parallax' => '',
	/**
	 * @var bool Has theme-defined background video?
	 */
	'us_bg_video' => FALSE,
	/**
	 * @var string Link to mp4 video file
	 */
	'video_mp4' => '',
	/**
	 * @var string Link to ogg video file
	 */
	'video_ogg' => '',
	/**
	 * @var string Link to webm video file
	 */
	'video_webm' => '',
	/**
	 * @var string
	 */
	'us_bg_overlay_color' => '',
	/**
	 * @var string
	 */
	'el_id' => '',
	/**
	 * @var string
	 */
	'el_class' => '',
	/**
	 * @var string
	 */
	'css' => '',
), $atts );

// .l-submain container additional classes and inner CSS-styles
$classes = '';
$inner_css = '';

$classes .= ' height_' . $atts['height'];

if ( $atts['height'] == 'full' AND ! empty( $atts['valign'] ) ) {
	$classes .= ' valign_' . $atts['valign'];
}

if ( $atts['width'] == 'full' ) {
	$classes .= ' width_full';
}

if ( $atts['color_scheme'] != '' ) {
	$classes .= ' color_' . $atts['color_scheme'];
	if ( $atts['color_scheme'] == 'custom' ) {
		// Custom background
		if ( $atts['us_bg_color'] != '' ) {
			$inner_css .= 'background-color: ' . $atts['us_bg_color'] . ';';
		}
		if ( $atts['us_text_color'] != '' ) {
			$inner_css .= 'color: ' . $atts['us_text_color'] . ';';
		}
	}
}

$classes .= ' imgsize_' . $atts['us_bg_size'];

$bg_image_html = '';
if ( ! empty( $atts['us_bg_image'] ) ) {
	$bg_image_url = '';
	if ( is_numeric( $atts['us_bg_image'] ) ) {
		$wp_image = wp_get_attachment_image_src( (int) $atts['us_bg_image'], 'full' );
		if ( $wp_image != NULL ) {
			$bg_image_url = $wp_image[0];
		}
	} else {
		$bg_image_url = $atts['img'];
	}
	$classes .= ' with_img';
	$bg_image_html = '<div class="l-section-img" style="background-image: url(' . $bg_image_url . ')"></div>';
}

$bg_video_html = '';
if ( $atts['us_bg_video'] AND ( $atts['video_mp4'] != '' OR $atts['video_ogg'] != '' OR $atts['video_webm'] != '' ) ) {
	$classes .= ' with_video';
	$bg_video_html = '<div class="l-section-video"><video loop="loop" autoplay="autoplay" preload="auto"';
	if ( isset($bg_image_url) AND ! empty( $bg_image_url ) ) {
		$bg_video_html .= ' poster="' . $bg_image_url . '"';
	}
	$bg_video_html .= '>';

	// Available video sources
	if ( ! empty( $atts['video_mp4'] ) ) {
		$bg_video_html .= '<source type="video/mp4 " src="' . $atts['video_mp4'] . '"></source>';
	}
	if ( ! empty( $atts['video_ogg'] ) ) {
		$bg_video_html .= '<source type="video/ogg " src="' . $atts['video_ogg'] . '"></source>';
	}
	if ( ! empty( $atts['video_webm'] ) ) {
		$bg_video_html .= '<source type="video/webm" src="' . $atts['video_webm'] . '"></source>';
	}
	if ( isset($bg_image_url) AND ! empty( $bg_image_url ) ) {
		$bg_video_html .= '<img src="' . $bg_image_url . '" alt="">';
	}

	$bg_video_html .= '</video></div>';
	// We need mediaelement script for this, but only once per page
	if ( ! wp_script_is( 'us-mediaelement', 'enqueued' ) ) {
		wp_enqueue_script( 'us-mediaelement' );
	}
} else {
	if ( $atts['us_bg_parallax'] == 'vertical' ) {
		$classes .= ' parallax_ver';
		wp_enqueue_script( 'us-parallax' );
	} elseif ( $atts['us_bg_parallax'] == 'fixed' OR $atts['us_bg_parallax'] == 'still' ) {
		$classes .= ' parallax_fixed';
	} elseif ( $atts['us_bg_parallax'] == 'horizontal' ) {
		$classes .= ' parallax_hor';
		wp_enqueue_script( 'us-hor-parallax' );
	}
}

$bg_overlay_html = '';
if ( ! empty( $atts['us_bg_overlay_color'] ) ) {
	$classes .= ' with_overlay';
	$bg_overlay_html = '<div class="l-section-overlay" style="background-color: ' . $atts['us_bg_overlay_color'] . '"></div>';
}


// Additional class set by a user in a shortcode attributes
if ( ! empty( $atts['el_class'] ) ) {
	$classes .= ' ' . sanitize_html_class( $atts['el_class'] );
}

// Special VC hooks
if ( function_exists( 'get_row_css_class' ) ) {
	$classes .= ' ' . get_row_css_class();
}
if ( ! empty( $atts['css'] ) AND preg_match( '~\{([^\}]+?)\;?\}~', $atts['css'], $matches ) ) {
	// We cannot use VC's method directly for rows: as it uses !important values, so we're moving the defined css
	// that don't duplicate the theme's features to inline style attribute.
	$vc_css_rules = array_map( 'trim', explode( ';', $matches[1] ) );
	$overloaded_params = array( 'background', 'background-position', 'background-repeat', 'background-size' );
	foreach ( $vc_css_rules as $vc_css_rule ) {
		$vc_css_rule = explode( ':', $vc_css_rule );
		if ( count( $vc_css_rule ) == 2 AND ! in_array( $vc_css_rule[0], $overloaded_params ) ) {
			$inner_css .= $vc_css_rule[0] . ':' . $vc_css_rule[1] . ';';
		}
	}
}
$classes = apply_filters( 'vc_shortcodes_css_class', $classes, $shortcode_base, $atts );

// Preparing html output
$output = '<section class="l-section wpb_row' . $classes . '"';
if ( ! empty( $atts['el_id'] ) ) {
	$output .= ' id="' . $atts['el_id'] . '"';
}

if ( ! empty( $inner_css ) ) {
	$output .= ' style="' . $inner_css . '"';
}
$output .= '>' . $bg_image_html . $bg_video_html . $bg_overlay_html . '<div class="l-section-h g-html i-cf">';

$inner_output = do_shortcode( $content );

// If the row has no inner rows, preparing wrapper for inner columns
if ( substr( $inner_output, 0, 18 ) != '<div class="g-cols' ) {
	// Offset modificator
	$cols_class_name = ' offset_' . $atts['columns_type'];
	$output .= '<div class="g-cols' . $cols_class_name . '">' . $inner_output . '</div>';
} else {
	$output .= $inner_output;
}

$output .= '</div></section>';

echo $output;
