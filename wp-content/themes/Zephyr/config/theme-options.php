<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Theme's Theme Options config
 *
 * @var $config array Framework-based theme options config
 *
 * @return array Changed config
 */

// Material design menu dropdown effect option
$config['menuoptions']['fields']['menu_dropdown_effect']['options'] += array(
	'mdesign' => __( 'Material Design Effect', 'us' ),
);
$config['menuoptions']['fields']['menu_dropdown_effect']['std'] = 'mdesign';

// WooCommerce shop listing styles
$config['woocommerce']['fields'] = us_array_merge_insert( $config['woocommerce']['fields'], array(
	'shop_listing_style' => array(
		'title' => __( 'Products Grid Style', 'us' ),
		'description' => __( 'This option sets style of products grid for all pages', 'us' ),
		'std' => '2',
		'type' => 'select',
		'options' => array(
			'1' => __( 'Flat style', 'us' ),
			'2' => __( 'Card style', 'us' ),
		),
	),
), 'after', 'product_sidebar' );

// Blog sharing button styles
unset( $config['blogoptions']['fields']['post_sharing_type']['options']['outlined'] );

unset( $config['styling']['fields']['color_menu_active_bg'] );

unset( $config['headeroptions']['fields']['wrapper_search_start'] );
unset( $config['headeroptions']['fields']['header_search_layout'] );
unset( $config['headeroptions']['fields']['wrapper_search_end'] );

unset( $config['footeroptions']['fields']['footer_layout'] );

return $config;
