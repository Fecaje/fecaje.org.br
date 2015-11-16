<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_logos
 *
 * @var $shortcode string Current shortcode name
 * @var $config array Shortcode's config
 *
 * @param $config ['atts'] array Shortcode's attributes and default values
 */
vc_map( array(
	'base' => 'us_logos',
	'name' => __( 'Client Logos', 'us' ),
	'icon' => 'icon-wpb-ui-separator-label',
	'category' => __( 'Content', 'us' ),
	'weight' => 230,
	'params' => array(
		array(
			'param_name' => 'columns',
			'heading' => __( 'Quantity of displayed items', 'us' ),
			'type' => 'dropdown',
			'value' => array(
				sprintf( _n( '%d item', '%d items', 1, 'us' ), 1 ) => '1',
				sprintf( _n( '%d item', '%d items', 2, 'us' ), 2 ) => '2',
				sprintf( _n( '%d item', '%d items', 3, 'us' ), 3 ) => '3',
				sprintf( _n( '%d item', '%d items', 4, 'us' ), 4 ) => '4',
				sprintf( _n( '%d item', '%d items', 5, 'us' ), 5 ) => '5',
				sprintf( _n( '%d item', '%d items', 6, 'us' ), 6 ) => '6',
			),
			'std' => $config['atts']['columns'],
			'weight' => 80,
		),
		array(
			'param_name' => 'style',
			'heading' => __( 'Hover style', 'us' ),
			'type' => 'dropdown',
			'value' => array(
				__( 'Card Style', 'us' ) => '1',
				__( 'Flat Style', 'us' ) => '2',
			),
			'std' => $config['atts']['style'],
			'weight' => 70,
		),
		array(
			'param_name' => 'with_indents',
			'type' => 'checkbox',
			'value' => array( __( 'Add indents between Items', 'us' ) => TRUE ),
			( ( $config['atts']['with_indents'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['with_indents'],
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'weight' => 60,
		),
		array(
			'param_name' => 'arrows',
			'type' => 'checkbox',
			'value' => array( __( 'Show Navigation Arrows', 'us' ) => TRUE ),
			( ( $config['atts']['arrows'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['arrows'],
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'weight' => 50,
		),
		array(
			'param_name' => 'orderby',
			'type' => 'checkbox',
			'value' => array( __( 'Display items in random order', 'us' ) => 'rand' ),
			( ( $config['atts']['orderby'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['orderby'],
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'weight' => 40,
		),
		array(
			'param_name' => 'auto_scroll',
			'type' => 'checkbox',
			'description' => '',
			'value' => array( __( 'Enable Auto Rotation', 'us' ) => TRUE ),
			( ( $config['atts']['auto_scroll'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['auto_scroll'],
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'weight' => 30,
		),
		array(
			'param_name' => 'interval',
			'heading' => __( 'Auto Rotation Interval (in seconds)', 'us' ),
			'type' => 'textfield',
			'std' => $config['atts']['interval'],
			'dependency' => array( 'element' => 'auto_scroll', 'not_empty' => TRUE ),
			'weight' => 20,
		),
		array(
			'param_name' => 'el_class',
			'heading' => __( 'Extra class name', 'us' ),
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'us' ),
			'type' => 'textfield',
			'std' => $config['atts']['el_class'],
			'weight' => 10,
		),
	),
) );
vc_remove_element( 'vc_images_carousel' );
