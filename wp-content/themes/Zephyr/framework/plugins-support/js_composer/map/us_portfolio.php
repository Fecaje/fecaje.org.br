<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_portfolio
 *
 * @var $shortcode string Current shortcode name
 * @var $config array Shortcode's config
 *
 * @param $config ['atts'] array Shortcode's attributes and default values
 */
$us_portfolio_categories = array();
$us_portfolio_categories_raw = get_categories( array(
	'taxonomy' => 'us_portfolio_category',
	'hierarchical' => 0,
) );
if ( $us_portfolio_categories_raw ) {
	foreach ( $us_portfolio_categories_raw as $portfolio_category_raw ) {
		if ( is_object( $portfolio_category_raw ) ) {
			$us_portfolio_categories[ $portfolio_category_raw->name ] = $portfolio_category_raw->slug;
		}
	}
}
vc_map( array(
	'base' => 'us_portfolio',
	'name' => __( 'Portfolio Grid', 'us' ),
	'icon' => 'icon-wpb-ui-separator-label',
	'category' => __( 'Content', 'us' ),
	'weight' => 250,
	'params' => array(
		array(
			'param_name' => 'columns',
			'heading' => __( 'Columns', 'us' ),
			'description' => '',
			'type' => 'dropdown',
			'value' => array(
				sprintf( _n( '%d column', '%d columns', 2, 'us' ), 2 ) => '2',
				sprintf( _n( '%d column', '%d columns', 3, 'us' ), 3 ) => '3',
				sprintf( _n( '%d column', '%d columns', 4, 'us' ), 4 ) => '4',
				sprintf( _n( '%d column', '%d columns', 5, 'us' ), 5 ) => '5',
			),
			'std' => $config['atts']['columns'],
			'admin_label' => TRUE,
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'weight' => 110,
		),
		array(
			'param_name' => 'pagination',
			'heading' => __( 'Pagination', 'us' ),
			'description' => '',
			'type' => 'dropdown',
			'value' => array(
				__( 'No pagination', 'us' ) => 'none',
				__( 'Regular pagination', 'us' ) => 'regular',
				__( 'Load More Button', 'us' ) => 'ajax',
			),
			'std' => $config['atts']['pagination'],
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'weight' => 100,
		),
		array(
			'param_name' => 'items',
			'heading' => __( 'Items Quantity', 'us' ),
			'description' => __( 'If left blank, will output all the items', 'us' ),
			'type' => 'textfield',
			'std' => $config['atts']['items'],
			'weight' => 90,
		),
		array(
			'param_name' => 'style',
			'heading' => __( 'Items Style', 'us' ),
			'description' => '',
			'type' => 'dropdown',
			'value' => array(
				sprintf( __( 'Style %d', 'us' ), 1 ) => 'style_1',
				sprintf( __( 'Style %d', 'us' ), 2 ) => 'style_2',
				sprintf( __( 'Style %d', 'us' ), 3 ) => 'style_3',
				sprintf( __( 'Style %d', 'us' ), 4 ) => 'style_4',
				sprintf( __( 'Style %d', 'us' ), 5 ) => 'style_5',
			),
			'std' => $config['atts']['style'],
			'admin_label' => TRUE,
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'weight' => 80,
		),
		array(
			'param_name' => 'align',
			'heading' => __( 'Items Text Alignment', 'us' ),
			'description' => '',
			'type' => 'dropdown',
			'value' => array(
				__( 'Left', 'us' ) => 'left',
				__( 'Center', 'us' ) => 'center',
				__( 'Right', 'us' ) => 'right',
			),
			'std' => $config['atts']['align'],
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'weight' => 80,
		),
		array(
			'param_name' => 'ratio',
			'heading' => __( 'Items Ratio', 'us' ),
			'description' => '',
			'type' => 'dropdown',
			'value' => array(
				__( '4:3 (landscape)', 'us' ) => '4x3',
				__( '3:2 (landscape)', 'us' ) => '3x2',
				__( '1:1 (square)', 'us' ) => '1x1',
				__( '2:3 (portrait)', 'us' ) => '2x3',
				__( '3:4 (portrait)', 'us' ) => '3x4',
				__( 'Initial', 'us' ) => 'initial',
			),
			'std' => $config['atts']['ratio'],
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'weight' => 70,
		),
		array(
			'param_name' => 'meta',
			'heading' => __( 'Items Meta', 'us' ),
			'description' => '',
			'type' => 'dropdown',
			'value' => array(
				__( 'Do not show', 'us' ) => '',
				__( 'Show Item date', 'us' ) => 'date',
				__( 'Show Item categories', 'us' ) => 'categories',
			),
			'std' => $config['atts']['meta'],
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'weight' => 60,
		),
		array(
			'param_name' => 'filter',
			'heading' => __( 'Filtering', 'us' ),
			'description' => '',
			'type' => 'checkbox',
			'value' => array( __( 'Enable filtering by category', 'us' ) => 'category' ),
			( ( $config['atts']['filter'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['filter'],
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'weight' => 50,
		),
		array(
			'param_name' => 'with_indents',
			'heading' => __( 'Items Indents', 'us' ),
			'description' => '',
			'type' => 'checkbox',
			'value' => array( __( 'Add indents between Items', 'us' ) => TRUE ),
			( ( $config['atts']['with_indents'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['with_indents'],
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'weight' => 40,
		),
		array(
			'param_name' => 'orderby',
			'heading' => _x( 'Order', 'sequence of items', 'us' ),
			'description' => '',
			'type' => 'dropdown',
			'value' => array(
				__( 'By date (newer first)', 'us' ) => 'date',
				__( 'By date (older first)', 'us' ) => 'date_asc',
				__( 'Alphabetically', 'us' ) => 'alpha',
				__( 'Random', 'us' ) => 'rand',
			),
			'std' => $config['atts']['orderby'],
			'weight' => 30,
		),
	),

) );
if ( ! empty( $us_portfolio_categories ) ) {
	vc_add_param( 'us_portfolio', array(
		'param_name' => 'categories',
		'heading' => __( 'Display Items of selected categories', 'us' ),
		'description' => '',
		'type' => 'checkbox',
		'value' => $us_portfolio_categories,
		'std' => $config['atts']['categories'],
		'weight' => 20,
	) );
}
vc_add_param( 'us_portfolio', array(
	'param_name' => 'el_class',
	'heading' => __( 'Extra class name', 'us' ),
	'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'us' ),
	'type' => 'textfield',
	'std' => $config['atts']['el_class'],
	'weight' => 10,
) );
