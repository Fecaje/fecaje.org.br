<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Revolution Slider Theme Support
 *
 * @link http://codecanyon.net/item/slider-revolution-responsive-wordpress-plugin/2751380?ref=UpSolution
 */

if ( ! class_exists( 'RevSliderFront' ) ) {
	return;
}

if ( function_exists( 'set_revslider_as_theme' ) ) {
	if ( ! defined( 'REV_SLIDER_AS_THEME' ) ) {
		define( 'REV_SLIDER_AS_THEME', TRUE );
	}
	set_revslider_as_theme();
}
// Actually the revslider's code above doesn't work as expected, so turning off the notifications manually
if ( get_option( 'revslider-valid-notice', 'true' ) ) {
	update_option( 'revslider-valid-notice', 'false' );
}
