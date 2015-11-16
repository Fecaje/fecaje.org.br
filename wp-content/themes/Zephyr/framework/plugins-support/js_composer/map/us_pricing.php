<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_pricing
 *
 * @var $shortcode string Current shortcode name
 * @var $config array Shortcode's config
 *
 * @param $config ['atts'] array Shortcode's attributes and default values
 * @param $congig ['items_atts'] array Items' attributes and default values
 */
vc_map( array(
	'base' => 'us_pricing',
	'name' => __( 'Pricing Table', 'us' ),
	'icon' => 'icon-wpb-pricing-table',
	'category' => __( 'Content', 'us' ),
	'weight' => 150,
	'params' => array(
		array(
			'param_name' => 'style',
			'heading' => __( 'Table Style', 'us' ),
			'description' => '',
			'type' => 'dropdown',
			'value' => array(
				__( 'Card Style', 'us' ) => '1',
				__( 'Flat Style', 'us' ) => '2',
			),
			'std' => $config['atts']['style'],
			'weight' => 30,
		),
		array(
			'type' => 'param_group',
			'heading' => __( 'Pricing Items', 'js_composer' ),
			'param_name' => 'items',
			// Storing encoded value to reduce memory and CPU usage
			'value' => '%5B%7B%22title%22%3A%22Free%22%2C%22price%22%3A%22%240%22%2C%22substring%22%3A%22per+month%22%2C%22features%22%3A%221+project%5Cn1+user%5Cn200+tasks%5CnNo+support%22%2C%22btn_text%22%3A%22Sign+up%22%2C%22btn_color%22%3A%22light%22%7D%2C%7B%22title%22%3A%22Standard%22%2C%22type%22%3A%22featured%22%2C%22price%22%3A%22%2424%22%2C%22substring%22%3A%22per+month%22%2C%22features%22%3A%2210+projects%5Cn10+users%5CnUnlimited+tasks%5CnPremium+support%22%2C%22btn_text%22%3A%22Sign+up%22%2C%22btn_color%22%3A%22primary%22%7D%2C%7B%22title%22%3A%22Premium%22%2C%22price%22%3A%22%2450%22%2C%22substring%22%3A%22per+month%22%2C%22features%22%3A%22Unlimited+projects%5CnUnlimited+users%5CnUnlimited+tasks%5CnPremium+support%22%2C%22btn_text%22%3A%22Sign+up%22%2C%22btn_color%22%3A%22light%22%7D%5D',
			/*'value' => urlencode( json_encode( array(
				array(
					'title' => 'Free',
					'price' => '$0',
					'substring' => 'per month',
					'features' => "1 project\n1 user\n200 tasks\nNo support",
					'btn_text' => 'Sign up',
					'btn_color' => 'light',
				),
				array(
					'title' => 'Standard',
					'type' => 'featured',
					'price' => '$24',
					'substring' => 'per month',
					'features' => "10 projects\n10 users\nUnlimited tasks\nPremium support",
					'btn_text' => 'Sign up',
					'btn_color' => 'primary',
				),
				array(
					'title' => 'Premium',
					'price' => '$50',
					'substring' => 'per month',
					'features' => "Unlimited projects\nUnlimited users\nUnlimited tasks\nPremium support",
					'btn_text' => 'Sign up',
					'btn_color' => 'light',
				),
			) ) ),*/
			'params' => array(
				array(
					'param_name' => 'title',
					'heading' => __( 'Item Title', 'us' ),
					'type' => 'textfield',
					'std' => $config['items_atts']['title'],
					'admin_label' => TRUE,
				),
				array(
					'param_name' => 'type',
					'type' => 'checkbox',
					'value' => array( __( 'Mark this item as featured', 'us' ) => 'featured' ),
					( ( $config['items_atts']['type'] !== FALSE ) ? 'std' : '_std' ) => $config['items_atts']['type'],
				),
				array(
					'param_name' => 'price',
					'heading' => __( 'Price', 'us' ),
					'type' => 'textfield',
					'std' => $config['items_atts']['type'],
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'param_name' => 'substring',
					'heading' => __( 'Price Substring', 'us' ),
					'type' => 'textfield',
					'std' => $config['items_atts']['substring'],
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'param_name' => 'features',
					'heading' => __( 'Features List', 'us' ),
					'type' => 'textarea',
					'std' => $config['items_atts']['features'],
				),
				array(
					'param_name' => 'btn_text',
					'heading' => __( 'Button Label', 'us' ),
					'description' => '',
					'type' => 'textfield',
					'std' => $config['items_atts']['btn_text'],
					'class' => 'wpb_button',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'param_name' => 'btn_color',
					'heading' => __( 'Button Color', 'us' ),
					'description' => '',
					'type' => 'dropdown',
					'value' => array(
						__( 'Primary (theme color)', 'us' ) => 'primary',
						__( 'Secondary (theme color)', 'us' ) => 'secondary',
						__( 'Light (theme color)', 'us' ) => 'light',
						__( 'Contrast (theme color)', 'us' ) => 'contrast',
						__( 'Black', 'us' ) => 'black',
						__( 'White', 'us' ) => 'white',
						__( 'Custom colors', 'us' ) => 'custom',
					),
					'std' => $config['items_atts']['btn_color'],
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'param_name' => 'btn_bg_color',
					'heading' => __( 'Button Background Color', 'us' ),
					'description' => '',
					'type' => 'colorpicker',
					'std' => $config['items_atts']['btn_bg_color'],
					'class' => '',
					'dependency' => array( 'element' => 'btn_color', 'value' => 'custom' ),
				),
				array(
					'param_name' => 'btn_text_color',
					'heading' => __( 'Button Text Color', 'us' ),
					'description' => '',
					'type' => 'colorpicker',
					'std' => $config['items_atts']['btn_text_color'],
					'class' => '',
					'dependency' => array( 'element' => 'btn_color', 'value' => 'custom' ),
				),
				array(
					'param_name' => 'btn_style',
					'heading' => __( 'Button Style', 'us' ),
					'description' => '',
					'type' => 'dropdown',
					'value' => array(
						__( 'Raised', 'us' ) => 'raised',
						__( 'Flat', 'us' ) => 'flat',
					),
					'std' => $config['items_atts']['btn_style'],
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'param_name' => 'btn_size',
					'heading' => __( 'Button Size', 'us' ),
					'description' => '',
					'type' => 'dropdown',
					'value' => array(
						__( 'Medium', 'us' ) => 'medium',
						__( 'Large', 'us' ) => 'large',
					),
					'std' => $config['items_atts']['btn_bg_color'],
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'param_name' => 'btn_icon',
					'heading' => __( 'Button Icon (optional)', 'us' ),
					'description' => sprintf( __( '<a href="%s" target="_blank">FontAwesome</a> or <a href="%s" target="_blank">Material Design</a> icon', 'us' ), 'http://fontawesome.io/icons/', 'http://designjockey.github.io/material-design-fonticons/' ),
					'type' => 'textfield',
					'std' => $config['items_atts']['btn_icon'],
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'param_name' => 'btn_iconpos',
					'heading' => __( 'Icon Position', 'us' ),
					'description' => '',
					'type' => 'dropdown',
					'value' => array(
						__( 'Left', 'us' ) => 'left',
						__( 'Right', 'us' ) => 'right',
					),
					'std' => $config['items_atts']['btn_iconpos'],
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'param_name' => 'btn_link',
					'heading' => __( 'Button Link', 'us' ),
					'description' => '',
					'type' => 'vc_link',
					'std' => $config['items_atts']['btn_link'],
				),
			),
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

