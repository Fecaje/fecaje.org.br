<?php defined('ABSPATH') OR die('This script cannot be accessed directly.');

/**
 * Theme's shortcodes
 *
 * @filter us_config_shortcodes
 */

return array(

	/**
	 * New shortcodes that are added with the theme
	 */
	'us_blog' => array(),
	'us_btn' => array(),
	'us_cform' => array(),
	'us_counter' => array(),
	'us_cta' => array(),
	'us_gallery' => array(),
	'us_gmaps' => array(),
	'us_iconbox' => array(),
	'us_image_slider' => array(),
	'us_logos' => array(),
	'us_message' => array(),
	'us_person' => array(),
	'us_portfolio' => array(),
	'us_pricing' => array(),
	'us_separator' => array(),
	'us_single_image' => array(),
	'us_social_links' => array(),
	'us_testimonial' => array(),

	/**
	 * Existing shortcodes that are overloaded by the theme's own implementation
	 */
	'vc_row' => array(),
	'vc_row_inner' => array(),
	'vc_column' => array(),
	'vc_column_inner' => array(
		'alias_of' => 'vc_column',
	),
	'vc_column_text' => array(),
	'vc_tta_accordion' => array(
		'alias_of' => 'vc_tta_tabs',
	),
	'vc_tta_section' => array(),
	'vc_tta_tabs' => array(),
	'vc_tta_tour' => array(
		'alias_of' => 'vc_tta_tabs',
	),
	'vc_tabs' => array(
		'alias_of' => 'vc_tta_tabs',
	),
	'vc_tab' => array(
		'alias_of' => 'vc_tta_section',
	),
	'vc_accordion' => array(
		'alias_of' => 'vc_tta_tabs',
	),
	'vc_accordion_tab' => array(
		'alias_of' => 'vc_tta_section',
	),
	'vc_video' => array(),

	/**
	 * Backward compatibility
	 */
	'gallery' => array(
		'alias_of' => 'us_gallery',
	),
	'vc_actionbox' => array(
		'alias_of' => 'us_cta',
	),
	'vc_blog' => array(
		'alias_of' => 'us_blog',
	),
	'vc_button' => array(
		'alias_of' => 'us_btn',
	),
	'vc_clients' => array(
		'alias_of' => 'us_logos',
	),
	'vc_counter' => array(
		'alias_of' => 'us_counter',
	),
	'vc_contact_form' => array(
		'alias_of' => 'us_cform',
	),
	'vc_gallery' => array(
		'alias_of' => 'us_gallery',
	),
	'vc_gmaps' => array(
		'alias_of' => 'us_gmaps',
	),
	'vc_iconbox' => array(
		'alias_of' => 'us_iconbox',
	),
	'vc_simple_slider' => array(
		'alias_of' => 'us_image_slider',
	),
	'vc_single_image' => array(
		'alias_of' => 'us_single_image',
	),
	'vc_separator' => array(
		'alias_of' => 'us_separator',
	),
	'vc_testimonial' => array(
		'alias_of' => 'us_testimonial',
	),
	'vc_text_separator' => array(
		'alias_of' => 'us_separator',
//		'compatibility_func' => '',
	),
	'vc_member' => array(
		'alias_of' => 'us_person',
	),
	'vc_message' => array(
		'alias_of' => 'us_message',
	),
	'vc_portfolio' => array(
		'alias_of' => 'us_portfolio',
	),
	'vc_social_links' => array(
		'alias_of' => 'us_social_links',
	),

	/**
	 * Shortcodes that are not supported by the theme, and should be temporarily disabled
	 */
	'vc_facebook' => array(
		'disabled' => TRUE,
	),
	'vc_tweetmeme' => array(
		'disabled' => TRUE,
	),
	'vc_googleplus' => array(
		'disabled' => TRUE,
	),
	'vc_pinterest' => array(
		'disabled' => TRUE,
	),
	'vc_toggle' => array(
		'disabled' => TRUE,
	),
	'vc_tour' => array(
		'disabled' => TRUE,
	),
	'vc_posts_slider' => array(
		'disabled' => TRUE,
	),
	'vc_progress_bar' => array(
		'disabled' => TRUE,
	),
	'vc_pie' => array(
		'disabled' => TRUE,
	),
	'vc_basic_grid' => array(
		'disabled' => TRUE,
	),
	'vc_media_grid' => array(
		'disabled' => TRUE,
	),
	'vc_images_carousel' => array(
		'disabled' => TRUE,
	),
	'vc_masonry_grid' => array(
		'disabled' => TRUE,
	),
	'vc_masonry_media_grid' => array(
		'disabled' => TRUE,
	),

);
