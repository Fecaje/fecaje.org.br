<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Addons configuration
 *
 * @filter us_config_addons
 */

global $us_template_directory;

return array(
	array(
		'name' => 'Slider Revolution',
		'slug' => 'revslider',
		'source' => $us_template_directory . '/vendor/plugins/revslider.zip',
		'required' => FALSE,
		'version' => '5.1',
		'force_activation' => FALSE,
		'force_deactivation' => FALSE,
		'external_url' => '',
		'changelog_url' => 'http://codecanyon.net/item/slider-revolution-responsive-wordpress-plugin/2751380',
	),
	array(
		'name' => 'WPBakery Visual Composer',
		'slug' => 'js_composer',
		'source' => $us_template_directory . '/vendor/plugins/js_composer.zip',
		'required' => TRUE,
		'version' => '4.8.0.1',
		'force_activation' => FALSE,
		'force_deactivation' => FALSE,
		'external_url' => '',
		'changelog_url' => 'https://wpbakery.atlassian.net/wiki/display/VC/Release+Notes',
	),
	array(
		'name' => 'Ultimate Addons for Visual Composer',
		'slug' => 'Ultimate_VC_Addons',
		'source' => $us_template_directory . '/vendor/plugins/Ultimate_VC_Addons.zip',
		'required' => FALSE,
		'recommended' => FALSE,
		'version' => '3.13.7',
		'force_activation' => FALSE,
		'force_deactivation' => FALSE,
		'external_url' => '',
		'changelog_url' => 'http://codecanyon.net/item/ultimate-addons-for-visual-composer/6892199',
	),
);
