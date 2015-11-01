<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Contact form configuration
 *
 * @filter us_config_cform
 */

return array(
	'fields' => array(
		'name' => array(
			'title' => __( 'Name', 'us' ),
			'icon' => 'mdfi_social_person',
			'type' => 'text',
			'error' => __( 'Please enter your Name', 'us' ),
		),
		'email' => array(
			'title' => __( 'Email', 'us' ),
			'icon' => 'mdfi_communication_email',
			'type' => 'email',
			'error' => __( 'Please enter your Email', 'us' ),
		),
		'phone' => array(
			'title' => __( 'Phone Number', 'us' ),
			'icon' => 'mdfi_communication_phone',
			'type' => 'text',
			'error' => __( 'Please enter your Phone Number', 'us' ),
		),
		'message' => array(
			'title' => __( 'Message', 'us' ),
			'icon' => 'mdfi_content_create',
			'type' => 'textarea',
			'error' => __( 'Please enter a Message', 'us' ),
		),
		'captcha' => array(
			'title' => __( 'Just to prove you are a human, please solve the equation: ', 'us' ),
			'icon' => 'mdfi_action_help',
			'type' => 'captcha',
			'error' => __( 'Please enter the equation result', 'us' ),
		),
	),
	'submit' => __( 'Send Message', 'us' ),
	'success' => __( 'Thank you! Your message was sent.', 'us' ),
	'error' => array(
		'empty_message' => __( 'Cannot send empty message. Please fill any of the fields.', 'us' ),
		'other' => __( 'Cannot send the message. Please contact the website administrator directly.', 'us' ),
	),
	'subject' => __( 'Contact request from %s', 'us' ),
);
