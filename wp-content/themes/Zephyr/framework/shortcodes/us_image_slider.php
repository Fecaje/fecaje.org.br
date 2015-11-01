<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_image_slider
 *
 * @var $shortcode {String} Current shortcode name
 * @var $shortcode_base {String} The original called shortcode name (differs if called an alias)
 * @var $atts {Array} Shortcode attributes
 * @var $content {String} Shortcode's inner content
 */

$atts = shortcode_atts( array(
	/**
	 * @var string Comma-separated list of image IDs (from media library)
	 */
	'ids' => '',
	/**
	 * @var string Navigation arrows: 'always' / 'hover' / 'hide'
	 */
	'arrows' => 'always',
	/**
	 * @var string Additional navigation: 'none' / 'dots' / 'thumbs'
	 */
	'nav' => 'none',
	/**
	 * @var string Transition effect: 'slide' / 'crossfade'
	 */
	'transition' => 'slide',
	/**
	 * @var bool Enable auto-rotation?
	 */
	'autoplay' => FALSE,
	/**
	 * @var int Auto-rotation period (in milliseconds)
	 */
	'autoplay_period' => 3000,
	/**
	 * @var bool Allow fullscreen view?
	 */
	'fullscreen' => FALSE,
	/**
	 * @var string Elements order: '' / 'rand'
	 */
	'orderby' => '',
	/**
	 * @var string Images size: 'large' / 'medium' / 'thumbnail' / 'full'
	 */
	'img_size' => 'large',
	/**
	 * @var bool How to fim an image: 'scaledown' / 'contain' / 'cover'
	 */
	'img_fit' => 'scaledown',
	/**
	 * @var string Extra class name
	 */
	'el_class' => '',
), $atts );

if ( empty( $atts['ids'] ) ) {
	return;
}

global $us_image_slider_index;
// Image sliders indexes start from 1
$us_image_slider_index = isset( $us_image_slider_index ) ? ( $us_image_slider_index + 1 ) : 1;

$classes = '';
// Royal Slider options
$js_options = array(
	'transitionSpeed' => '300',
	'loopRewind' => TRUE,
	'slidesSpacing' => 0,
	'imageScalePadding' => 0,
	'numImagesToPreload' => 2,
	'arrowsNav' => ( $atts['arrows'] != 'hide' ),
	'arrowsNavAutoHide' => ( $atts['arrows'] == 'hover' ),
	'transitionType' => ( $atts['transition'] == 'crossfade' ) ? 'fade' : 'move',
);
if ( $atts['nav'] == 'dots' ) {
	$js_options['controlNavigation'] = 'bullets';
} elseif ( $atts['nav'] == 'thumbs' ) {
	$js_options['controlNavigation'] = 'thumbnails';
} else {
	$js_options['controlNavigation'] = 'none';
}

if ( $atts['autoplay'] AND $atts['autoplay_period'] ) {
	$js_options['autoplay'] = array(
		'enabled' => TRUE,
		'pauseOnHover' => TRUE,
		'delay' => intval( $atts['autoplay_period'] ),
	);
}

if ( $atts['fullscreen'] ) {
	$js_options['fullscreen'] = array(
		'enabled' => TRUE,
	);
}

if ( $atts['img_fit'] == 'contain' ) {
	$js_options['imageScaleMode'] = 'fit';
} elseif ( $atts['img_fit'] == 'cover' ) {
	$js_options['imageScaleMode'] = 'fill';
} else/*if ( $atts['img_fit'] == 'scaledown' )*/ {
	$js_options['imageScaleMode'] = 'fit-if-smaller';
}

if ( ! in_array( $atts['img_size'], get_intermediate_image_sizes() ) ) {
	$atts['img_size'] = 'full';
}

// Getting images
$query_args = array(
	'include' => $atts['ids'],
	'post_status' => 'inherit',
	'post_type' => 'attachment',
	'post_mime_type' => 'image',
	'orderby' => 'post__in',
);

if ( $atts['orderby'] == 'rand' ) {
	$query_args['orderby'] = 'rand';
}
$attachments = get_posts( $query_args );
if ( ! is_array( $attachments ) OR empty( $attachments ) ) {
	return;
}

if ( $atts['el_class'] != '' ) {
	$classes .= ' ' . $atts['el_class'];
}

$i = 1;
$data_ratio = NULL;
$images_html = '';
foreach ( $attachments as $index => $attachment ) {
	$image = wp_get_attachment_image_src( $attachment->ID, $atts['img_size'] );
	if ( ! $image ) {
		continue;
	}
	if ( ! isset( $js_options['autoScaleSlider'] ) ) {
		$js_options['autoScaleSlider'] = TRUE;
		$js_options['autoScaleSliderWidth'] = $image[1];
		$js_options['autoScaleSliderHeight'] = $image[2];
		$js_options['fitInViewport'] = FALSE;
	}
	$full_image_attr = '';
	if ( $atts['fullscreen'] ) {
		$full_image = wp_get_attachment_image_src( $attachment->ID, 'full' );
		if ( ! $full_image ) {
			$full_image = $image;
		}
		$full_image_attr = ' data-rsBigImg="' . $full_image[0] . '"';
	}
	if ( $atts['nav'] == 'thumbs' ) {
		$tnail = wp_get_attachment_image_src( $attachment->ID, 'thumbnail' );
		if ( ! $tnail ) {
			$tnail = $image;
		}
		$images_html .= '<a class="rsImg" data-rsw="' . $image[1] . '" data-rsh="' . $image[2] . '"' . $full_image_attr . ' href="' . $image[0] . '">';
		$images_html .= '<img class="rsTmb" src="' . $tnail[0] . '" width="' . $tnail[1] . '" height="' . $tnail[2] . '" alt="" />';
		$images_html .= '</a>';
	} else {
		$images_html .= '<a class="rsImg" data-rsw="' . $image[1] . '" data-rsh="' . $image[2] . '"' . $full_image_attr . ' href="' . $image[0] . '">';
		$images_html .= '</a>';
	}
}

// We need Roayl Slider script for this
wp_enqueue_script( 'us-royalslider' );
wp_enqueue_style( 'us-royalslider' );

$output = '<div class="w-slider' . $classes . '">';
$output .= '<div class="royalSlider rsDefault">' . $images_html . '</div>';
$output .= '<div class="w-slider-json"' . us_pass_data_to_js( $js_options ) . '></div>';
$output .= '</div>';

echo $output;
