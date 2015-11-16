<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_blog
 *
 * @var $shortcode string Current shortcode name
 * @var $config array Shortcode's config
 *
 * @param $config ['atts'] array Shortcode's attributes and default values
 */
$us_post_categories = array();
$us_post_categories_raw = get_categories( "hierarchical=0" );
foreach ( $us_post_categories_raw as $post_category_raw ) {
	$us_post_categories[ $post_category_raw->name ] = $post_category_raw->slug;
}
vc_map( array(
	'base' => 'us_blog',
	'name' => __( 'Blog', 'us' ),
	'description' => __( 'Blog posts listing', 'us' ),
	'icon' => 'icon-wpb-ui-separator-label',
	'category' => __( 'Content', 'us' ),
	'weight' => 240,
	'params' => array(
		array(
			'param_name' => 'layout',
			'heading' => __( 'Layout Type', 'us' ),
			'description' => '',
			'type' => 'dropdown',
			'value' => array(
				__( 'Small Image', 'us' ) => 'smallcircle',
				__( 'Small Square Image', 'us' ) => 'smallsquare',
				__( 'Large Image', 'us' ) => 'large',
				__( 'Regular Grid', 'us' ) => 'grid',
				__( 'Masonry Grid', 'us' ) => 'masonry',
				__( 'Compact', 'us' ) => 'compact',
			),
			'std' => $config['atts']['layout'],
			'admin_label' => TRUE,
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'weight' => 130,
		),
		array(
			'param_name' => 'content_type',
			'heading' => __( 'Posts Content', 'us' ),
			'description' => '',
			'type' => 'dropdown',
			'value' => array(
				__( 'Excerpt', 'us' ) => 'excerpt',
				__( 'Full Content', 'us' ) => 'content',
				__( 'None', 'us' ) => 'none',
			),
			'std' => $config['atts']['content_type'],
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'weight' => 120,
		),
		array(
			'param_name' => 'columns',
			'heading' => __( 'Columns', 'us' ),
			'description' => '',
			'type' => 'dropdown',
			'value' => array(
				sprintf( _n( '%d column', '%d columns', 1, 'us' ), 1 ) => '1',
				sprintf( _n( '%d column', '%d columns', 2, 'us' ), 2 ) => '2',
				sprintf( _n( '%d column', '%d columns', 3, 'us' ), 3 ) => '3',
				sprintf( _n( '%d column', '%d columns', 4, 'us' ), 4 ) => '4',
			),
			'std' => $config['atts']['columns'],
			'dependency' => array( 'element' => 'layout', 'value' => 'latest' ),
			'weight' => 110,
		),
		array(
			'param_name' => 'items',
			'heading' => __( 'Posts Quantity', 'us' ),
			'description' => '',
			'type' => 'textfield',
			'std' => $config['atts']['items'],
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'weight' => 100,
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
			'weight' => 90,
		),
		array(
			'param_name' => 'show_date',
			'heading' => '',
			'description' => '',
			'type' => 'checkbox',
			'value' => array( __( 'Show Post Date', 'us' ) => TRUE ),
			( ( $config['atts']['show_date'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['show_date'],
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'weight' => 80,
		),
		array(
			'param_name' => 'show_author',
			'heading' => '',
			'description' => '',
			'type' => 'checkbox',
			'value' => array( __( 'Show Post Author', 'us' ) => TRUE ),
			( ( $config['atts']['show_author'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['show_author'],
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'weight' => 70,
		),
		array(
			'param_name' => 'show_categories',
			'heading' => '',
			'description' => '',
			'type' => 'checkbox',
			'value' => array( __( 'Show Post Categories', 'us' ) => TRUE ),
			( ( $config['atts']['show_categories'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['show_categories'],
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'weight' => 60,
		),
		array(
			'param_name' => 'show_tags',
			'heading' => '',
			'description' => '',
			'type' => 'checkbox',
			'value' => array( __( 'Show Post Tags', 'us' ) => TRUE ),
			( ( $config['atts']['show_tags'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['show_tags'],
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'weight' => 50,
		),
		array(
			'param_name' => 'show_comments',
			'heading' => '',
			'description' => '',
			'type' => 'checkbox',
			'value' => array( __( 'Show Post Comments', 'us' ) => TRUE ),
			( ( $config['atts']['show_comments'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['show_comments'],
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'weight' => 40,
		),
		array(
			'param_name' => 'show_read_more',
			'heading' => '',
			'description' => '',
			'type' => 'checkbox',
			'value' => array( __( 'Show Read More button', 'us' ) => TRUE ),
			( ( $config['atts']['show_read_more'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['show_read_more'],
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'weight' => 30,
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
			'weight' => 25,
		),
		array(
			'param_name' => 'categories',
			'heading' => __( 'Display Posts of selected categories', 'us' ),
			'description' => '',
			'type' => 'checkbox',
			'value' => $us_post_categories,
			'std' => $config['atts']['categories'],
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
