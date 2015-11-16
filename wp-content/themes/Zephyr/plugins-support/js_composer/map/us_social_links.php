<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_blog
 *
 * @var $shortcode string Current shortcode name
 * @var $config array Shortcode's config
 *
 * @param $config ['atts'] array Shortcode's attributes and default values
 */

global $us_template_directory;
require $us_template_directory . '/framework/plugins-support/js_composer/map/us_social_links.php';

vc_remove_param( 'us_social_links', 'style' );
vc_add_param( 'us_social_links', array(
	'param_name' => 'style',
	'heading' => __( 'Icons Style', 'us' ),
	'description' => '',
	'type' => 'dropdown',
	'value' => array(
		__( 'Colored', 'us' ) => 'colored',
		__( 'Desaturated', 'us' ) => 'desaturated',
		__( 'Colored Inverted', 'us' ) => 'colored_inv',
		__( 'Desaturated Inverted', 'us' ) => 'desaturated_inv',
	),
	'std' => $config['atts']['style'],
	'edit_field_class' => 'vc_col-sm-6 vc_column',
	'weight' => 320,
) );
