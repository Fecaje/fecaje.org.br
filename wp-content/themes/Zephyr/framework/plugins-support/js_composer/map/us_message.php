<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_message
 *
 * @var $shortcode string Current shortcode name
 * @var $config array Shortcode's config
 *
 * @param $config ['atts'] array Shortcode's attributes and default values
 * @param $config ['content'] string Shortcode's default content
 */
vc_map( array(
	'base' => 'us_message',
	'name' => __( 'Message Box', 'us' ),
	'icon' => 'icon-wpb-information-white',
	'wrapper_class' => 'alert',
	'category' => __( 'Content', 'us' ),
	'weight' => 200,
	'params' => array(
		array(
			'param_name' => 'color',
			'heading' => __( 'Color Style', 'us' ),
			'description' => '',
			'type' => 'dropdown',
			'value' => array(
				__( 'Notification (blue)', 'us' ) => 'info',
				__( 'Attention (yellow)', 'us' ) => 'attention',
				__( 'Success (green)', 'us' ) => 'success',
				__( 'Error (red)', 'us' ) => 'error',
				__( 'Custom colors', 'us' ) => 'custom',
			),
			'std' => $config['atts']['color'],
			'weight' => 70,
		),
		array(
			'param_name' => 'bg_color',
			'heading' => __( 'Background Color', 'us' ),
			'description' => '',
			'type' => 'colorpicker',
			'std' => $config['atts']['bg_color'],
			'holder' => 'div',
			'class' => '',
			'dependency' => array( 'element' => 'color', 'value' => 'custom' ),
			'weight' => 60,
		),
		array(
			'param_name' => 'text_color',
			'heading' => __( 'Text Color', 'us' ),
			'description' => '',
			'type' => 'colorpicker',
			'std' => $config['atts']['text_color'],
			'holder' => 'div',
			'class' => '',
			'dependency' => array( 'element' => 'color', 'value' => 'custom' ),
			'weight' => 50,
		),
		array(
			'param_name' => 'content',
			'heading' => __( 'Message Text', 'us' ),
			'type' => 'textarea',
			'value' => __( 'I am message box. Click edit button to change this text.', 'us' ),
			'std' => $config['content'],
			'holder' => 'div',
			'class' => 'content',
			'weight' => 40,
		),
		array(
			'param_name' => 'icon',
			'heading' => __( 'Icon (optional)', 'us' ),
			'description' => sprintf( __( '<a href="%s" target="_blank">FontAwesome</a> or <a href="%s" target="_blank">Material Design</a> icon', 'us' ), 'http://fontawesome.io/icons/', 'http://designjockey.github.io/material-design-fonticons/' ),
			'type' => 'textfield',
			'std' => $config['atts']['icon'],
			'weight' => 30,
		),
		array(
			'param_name' => 'closing',
			'heading' => '',
			'description' => '',
			'type' => 'checkbox',
			'value' => array( __( 'Enable closing', 'us' ) => TRUE ),
			( ( $config['atts']['closing'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['closing'],
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
	'js_view' => 'VcMessageView',
) );
vc_remove_element( 'vc_message' );
