<?php

$header_titlebar_fields = array(
	array(
		'name' => __( 'Small caption (shown next to Page Title)', 'us' ),
		'id' => 'us_titlebar_subtitle',
		'clone' => FALSE,
		'type' => 'text',
		'std' => '',
	),
	array(
		'name' => __( 'Title Bar Size', 'us' ),
		'id' => 'us_titlebar_size',
		'type' => 'select',
		'options' => array(
			'' => __( 'Default (set at Theme Options)', 'us' ),
			'small' => __( 'Ultra Compact', 'us' ),
			'medium' => __( 'Compact', 'us' ),
			'large' => __( 'Large', 'us' ),
			'huge' => __( 'Huge', 'us' ),
		),
	),
	array(
		'name' => __( 'Title Bar Color Style', 'us' ),
		'id' => 'us_titlebar_color',
		'type' => 'select',
		'options' => array(
			'' => __( 'Default (set at Theme Options)', 'us' ),
			'default' => __( 'Content colors', 'us' ),
			'alternate' => __( 'Alternate bg & Content text', 'us' ),
			'primary' => __( 'Primary bg & White text', 'us' ),
			'secondary' => __( 'Secondary bg & White text', 'us' ),
		),
	),
	array(
		'name' => __( 'Background Image', 'us' ),
		'id' => 'us_titlebar_image',
		'type' => 'image_advanced',
		'max_file_uploads' => 1,
	),
	array(
		'name' => __( 'Background Image Size', 'us' ),
		'id' => 'us_titlebar_image_size',
		'type' => 'select',
		'options' => array(
			'cover' => __( 'Cover - Image will cover the whole Title Bar', 'us' ),
			'contain' => __( 'Contain - Image will fit inside the Title Bar', 'us' ),
			'initial' => __( 'Initial', 'us' ),
		),
	),
	array(
		'name' => __( 'Parallax Effect', 'us' ),
		'id' => 'us_titlebar_image_parallax',
		'type' => 'select',
		'options' => array(
			'' => __( 'None', 'us' ),
			'vertical' => __( 'Vertical Parallax', 'us' ),
			'horizontal' => __( 'Horizontal Parallax', 'us' ),
			'still' => __( 'Still (Image doesn\'t move)', 'us' ),
		),
	),
	array(
		'name' => __( 'Overlay Color', 'us' ),
		'id' => 'us_titlebar_overlay_color',
		'type' => 'color',
	),
	array(
		'name' => __( 'Overlay Opacity', 'us' ),
		'id' => 'us_titlebar_overlay_opacity',
		'type' => 'slider',
		'js_options' => array(
			'min' => 1,
			'max' => 99,
		)
	),
);

$footer_fields = array(
	array(
		'name' => __( 'Show Subfooter (widgets area)', 'us' ),
		'id' => 'us_footer_show_top',
		'type' => 'select',
		'options' => array(
			'' => __( 'Default (set at Theme Options)', 'us' ),
			'show' => __( 'Show', 'us' ),
			'hide' => __( 'Hide', 'us' ),
		),
	),
	array(
		'name' => __( 'Show Footer (copyright and menu area)', 'us' ),
		'id' => 'us_footer_show_bottom',
		'type' => 'select',
		'options' => array(
			'' => __( 'Default (set at Theme Options)', 'us' ),
			'show' => __( 'Show', 'us' ),
			'hide' => __( 'Hide', 'us' ),
		),
	),
);

$meta_boxes[] = array(
	'id' => 'us_post_page_portfolio_sidebar_settings',
	'title' => __( 'Sidebar', 'us' ),
	'pages' => array( 'post', 'page', 'us_portfolio', 'product' ),
	'context' => 'side',
	'priority' => 'default',
	'fields' => array(
		array(
			'name' => '',
			'id' => 'us_sidebar',
			'type' => 'select',
			'options' => array(
				'' => __( 'Default (set at Theme Options)', 'us' ),
				'none' => __( 'No Sidebar', 'us' ),
				'right' => __( 'Right', 'us' ),
				'left' => __( 'Left', 'us' ),
			),
		),
	),
);

$meta_boxes[] = array(
	'id' => 'us_post_page_portfolio_header_settings',
	'title' => __( 'Header Options', 'us' ),
	'pages' => array( 'post', 'page', 'us_portfolio', 'product' ),
	'context' => 'side',
	'priority' => 'default',
	'fields' => array(
		array(
			'name' => '',
			'id' => 'us_header_remove',
			'type' => 'checkbox',
			'desc' => __( 'Remove header on this page', 'us' ),
		),
		array(
			'name' => __( 'Transparent Header', 'us' ),
			'id' => 'us_header_bg',
			'type' => 'select',
			'options' => array(
				'' => __( 'Default (set at Theme Options)', 'us' ),
				'transparent' => __( 'Transparent (overrides Theme Options)', 'us' ),
				'solid' => __( 'Solid (overrides Theme Options)', 'us' ),
			),
		),
		array(
			'name' => __( 'Sticky Header', 'us' ),
			'id' => 'us_header_pos',
			'type' => 'select',
			'options' => array(
				'' => __( 'Default (set at Theme Options)', 'us' ),
				'fixed' => __( 'Sticky (overrides Theme Options)', 'us' ),
				'static' => __( 'Non-sticky (overrides Theme Options)', 'us' ),
			),
		),
		array(
			'name' => __( 'Hidden Header', 'us' ),
			'id' => 'us_header_show',
			'type' => 'select',
			'options' => array(
				'' => __( 'Default (set at Theme Options)', 'us' ),
				'onscroll' => __( 'Hide the header on its initial position', 'us' ),
				'always' => __( 'Show the header on its initial position', 'us' ),
			),
		),
	),
);

$meta_boxes[] = array(
	'id' => 'us_page_titlebar_settings',
	'title' => __( 'Title Bar Options', 'us' ),
	'pages' => array( 'page', 'product' ),
	'context' => 'side',
	'priority' => 'default',
	// List of meta fields
	'fields' => array_merge( array(
		array(
			'name' => '',
			'id' => 'us_titlebar_content',
			'type' => 'select',
			'options' => array(
				'' => __( 'Default (set at Theme Options)', 'us' ),
				'all' => __( 'Captions and Breadcrumbs', 'us' ),
				'caption' => __( 'Captions Only', 'us' ),
				'hide' => __( 'Hide Title Bar', 'us' ),
			),
		),
	), $header_titlebar_fields ),
);

// Differs from page's titlebar by "Captions and Arrows" option
$meta_boxes[] = array(
	'id' => 'us_portfolio_titlebar_settings',
	'title' => __( 'Title Bar Options', 'us' ),
	'pages' => array( 'us_portfolio' ),
	'context' => 'side',
	'priority' => 'default',
	// List of meta fields
	'fields' => array_merge( array(
		array(
			'name' => '',
			'id' => 'us_titlebar_content',
			'type' => 'select',
			'options' => array(
				'' => __( 'Default (set at Theme Options)', 'us' ),
				'all' => __( 'Captions and Arrows', 'us' ),
				'caption' => __( 'Captions Only', 'us' ),
				'hide' => __( 'Hide Title Bar', 'us' ),
			),
		),
	), $header_titlebar_fields ),
);

$meta_boxes[] = array(
	'id' => 'client_settings',
	'title' => __( 'Client Options', 'us' ),
	'pages' => array( 'us_client' ),
	'context' => 'normal',
	'priority' => 'high',
	// List of meta fields
	'fields' => array(
		array(
			'name' => __( 'Client URL', 'us' ),
			'id' => 'us_client_url',
			'type' => 'text',
			'std' => '',
		),
		array(
			'name' => __( 'Open URL in a new Tab (Window)', 'us' ),
			'id' => 'us_client_new_tab',
			'type' => 'checkbox',
			'std' => FALSE,
		),
	),
);

$meta_boxes[] = array(
	'id' => 'portfolio_layout_settings',
	'title' => __( 'Portfolio Item Options', 'us' ),
	'pages' => array( 'us_portfolio' ),
	'context' => 'normal',
	'priority' => 'high',
	// List of meta fields
	'fields' => array(
		array(
			'desc' => __( 'Open Portfolio Image in a lightbox', 'us' ),
			'id' => 'us_lightbox',
			'type' => 'checkbox',
			'std' => FALSE,
		),
		array(
			'name' => __( 'Custom Project Link', 'us' ),
			'id' => 'us_custom_link',
			'type' => 'text',
			'std' => '',
		),
		array(
			'id' => 'us_custom_link_blank',
			'type' => 'checkbox',
			'desc' => __( 'Open Custom Project Link in a new tab (window)', 'us' ),
		),
		array(
			'name' => __( 'Item Tile Size', 'us' ),
			'id' => 'us_tile_size',
			'type' => 'select',
			'options' => array(
				'' => '1x1',
				'2x1' => '2x1',
				'1x2' => '1x2',
				'2x2' => '2x2',
			),
		),
		array(
			'name' => __( 'Item Tile Background Color', 'us' ),
			'id' => 'us_tile_bg_color',
			'type' => 'color',
		),
		array(
			'name' => __( 'Item Tile Text Color', 'us' ),
			'id' => 'us_tile_text_color',
			'type' => 'color',
		),
	),

);

$meta_boxes[] = array(
	'id' => 'us_common_footer_settings',
	'title' => __( 'Footer Options', 'us' ),
	'pages' => array( 'post', 'page', 'us_portfolio', 'product' ),
	'context' => 'side',
	'priority' => 'default',
	// List of meta fields
	'fields' => $footer_fields
);

$meta_boxes[] = array(
	'id' => 'us_post_settings',
	'title' => __( 'Featured Image Layout', 'us' ),
	'pages' => array( 'post' ),
	'context' => 'side',
	'priority' => 'low',
	// List of meta fields
	'fields' => array(
		array(
			'name' => '',
			'id' => 'us_post_preview_layout',
			'type' => 'select',
			'options' => array(
				'' => __( 'Default (set at Theme Options)', 'us' ),
				'basic' => __( 'Standard', 'us' ),
				'modern' => __( 'Modern', 'us' ),
				'none' => __( 'No Preview', 'us' ),
			),
		),
	),
);
