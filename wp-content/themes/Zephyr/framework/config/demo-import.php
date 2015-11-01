<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Theme's demo-import settings
 *
 * @filter us_config_demo-import
 */

return array(
	'main' => array(
		'title' => 'Main Demo',
		'image' => 'demo-import/main-preview.jpg',
		'preview_url' => 'http://zephyr.us-themes.com/',
		'nav_menu_locations' => array(
			'Zephyr Header Menu' => 'us_main_menu',
			'Zephyr Footer Menu' => 'us_footer_menu',
		),
		'front_page' => 'Home',
		'sliders' => array(
			'demo-import/main-slider-second.zip',
			'demo-import/main-slider-main.zip',
		),
	),
);
