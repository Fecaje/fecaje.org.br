<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Modifying shortcode: vc_tta_tabs
 *
 * @var $shortcode string Current shortcode name
 * @var $config array Shortcode's config
 *
 * @param $config ['atts'] array Shortcode's attributes and default values
 */
if ( version_compare( WPB_VC_VERSION, '4.6', '<' ) ) {
	// Oops: the modified shorcode doesn't exist in current VC version. Doing nothing.
	return;
}

if ( ! vc_is_page_editable() ) {
	vc_remove_param( 'vc_tta_tabs', 'style' );
	vc_remove_param( 'vc_tta_tabs', 'shape' );
	vc_remove_param( 'vc_tta_tabs', 'color' );
	vc_remove_param( 'vc_tta_tabs', 'no_fill_content_area' );
	vc_remove_param( 'vc_tta_tabs', 'spacing' );
	vc_remove_param( 'vc_tta_tabs', 'gap' );
	vc_remove_param( 'vc_tta_tabs', 'tab_position' );
	vc_remove_param( 'vc_tta_tabs', 'alignment' );
	vc_remove_param( 'vc_tta_tabs', 'autoplay' );
	vc_remove_param( 'vc_tta_tabs', 'active_section' );
	vc_remove_param( 'vc_tta_tabs', 'pagination_style' );
	vc_remove_param( 'vc_tta_tabs', 'pagination_color' );
	vc_add_param( 'vc_tta_tabs', array(
		'param_name' => 'layout',
		'heading' => __( 'Act as Timeline', 'us' ),
		'description' => '',
		'type' => 'checkbox',
		'value' => array( __( 'Change look and feel into Timeline', 'us' ) => 'timeline' ),
		( ( $config['atts']['layout'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['layout'],
	) );
	// The only available way to preserve param order :(
	// TODO When some vc_modify_param will be available, reorder params by other means
	vc_remove_param( 'vc_tta_tabs', 'el_class' );
	vc_add_param( 'vc_tta_tabs', array(
		'param_name' => 'el_class',
		'heading' => __( 'Extra class name', 'us' ),
		'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'us' ),
		'type' => 'textfield',
		'std' => $config['atts']['el_class'],
	) );
}

// Setting proper shortcode order in VC shortcodes listing
vc_map_update( 'vc_tta_tabs', array( 'weight' => 320 ) );
