<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Provides Visual Composer compatibility
 *
 * Dev note: should be included only at vc_after_set_mode action, so the vc_mode() is workable
 */

if ( ! class_exists( 'Vc_Manager' ) ) {
	return;
}

// Css animation settings that can be applied to various elements
$us_css_animation = array(
	'type' => 'dropdown',
	'heading' => __( 'Animation', 'us' ),
	'param_name' => 'animate',
	'admin_label' => TRUE,
	'value' => array(
		__( 'No Animation', 'us' ) => '',
		__( 'Fade', 'us' ) => 'fade',
		__( 'Appear From Center', 'us' ) => 'afc',
		__( 'Appear From Left', 'us' ) => 'afl',
		__( 'Appear From Right', 'us' ) => 'afr',
		__( 'Appear From Bottom', 'us' ) => 'afb',
		__( 'Appear From Top', 'us' ) => 'aft',
		__( 'Height From Center', 'us' ) => 'hfc',
		__( 'Width From Center', 'us' ) => 'wfc',
	),
	'std' => '',
	'description' => __( 'Select animation type if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.', 'us' ),
);
$us_css_animation_delay = array(
	'type' => 'dropdown',
	'heading' => __( 'Animation Delay', 'us' ),
	'param_name' => 'animate_delay',
	'admin_label' => TRUE,
	'value' => array(
		__( 'None', 'us' ) => '',
		__( '0.2 second', 'us' ) => '0.2',
		__( '0.4 second', 'us' ) => '0.4',
		__( '0.6 second', 'us' ) => '0.6',
		__( '0.8 second', 'us' ) => '0.8',
		__( '1 second', 'us' ) => '1',
	),
	'std' => '',
	'dependency' => array( 'element' => 'animate', 'not_empty' => TRUE ),
	'description' => '',
);

$url_fontawesome = 'http://fontawesome.io/icons/';
$url_mdfi = 'http://designjockey.github.io/material-design-fonticons/';

/**
 * Extending shortcode: vc_row
 */
vc_remove_param( 'vc_row', 'full_width' );
vc_remove_param( 'vc_row', 'full_height' );
vc_remove_param( 'vc_row', 'content_placement' );
vc_remove_param( 'vc_row', 'video_bg' );
vc_remove_param( 'vc_row', 'video_bg_url' );
vc_remove_param( 'vc_row', 'video_bg_parallax' );
if ( ! vc_is_page_editable() ) {
	vc_remove_param( 'vc_row', 'parallax' );
	vc_remove_param( 'vc_row', 'parallax_image' );
}
vc_add_params( 'vc_row', array(
	array(
		'type' => 'dropdown',
		'heading' => __( 'Row Columns Layout', 'us' ),
		'param_name' => 'columns_type',
		'value' => array(
			__( 'With Small gaps', 'us' ) => 'small',
			__( 'With Medium gaps', 'us' ) => 'medium',
			__( 'With Large gaps', 'us' ) => 'large',
			__( 'Boxed and without gaps', 'us' ) => 'none',
		),
		'std' => 'medium',
		'description' => '',
		'weight' => 20,
	),
	array(
		'type' => 'dropdown',
		'heading' => __( 'Row Height', 'us' ),
		'param_name' => 'height',
		'value' => array(
			__( 'No paddings', 'us' ) => 'auto',
			__( 'Small paddings', 'us' ) => 'small',
			__( 'Medium paddings', 'us' ) => 'medium',
			__( 'Large paddings', 'us' ) => 'large',
			__( 'Full Screen', 'us' ) => 'full',
		),
		'std' => 'medium',
		'weight' => 19,
	),
	array(
		'type' => 'checkbox',
		'param_name' => 'valign',
		'value' => array( __( 'Center content of this row vertically', 'us' ) => 'center' ),
		'dependency' => array( 'element' => 'height', 'value' => 'full' ),
		'weight' => 18,
	),
	array(
		'type' => 'checkbox',
		'heading' => __( 'Full Width Content', 'us' ),
		'param_name' => 'width',
		'value' => array( __( 'Stretch content of this row to the screen width', 'us' ) => 'full' ),
		'weight' => 17,
	),
	array(
		'type' => 'dropdown',
		'heading' => __( 'Row Color Style', 'us' ),
		'param_name' => 'color_scheme',
		'value' => array(
			__( 'Content bg | Content text', 'us' ) => '',
			__( 'Alternate bg | Content text', 'us' ) => 'alternate',
			__( 'Primary bg | White text', 'us' ) => 'primary',
			__( 'Secondary bg | White text', 'us' ) => 'secondary',
			__( 'Custom colors', 'us' ) => 'custom',
		),
		'std' => '',
		'description' => '',
		'weight' => 16,
	),
	array(
		'type' => 'colorpicker',
		'heading' => __( 'Background Color', 'us' ),
		'param_name' => 'us_bg_color',
		'value' => '',
		'description' => '',
		'dependency' => array( 'element' => 'color_scheme', 'value' => 'custom' ),
		'edit_field_class' => 'vc_col-sm-6 vc_column',
		'weight' => 15,
	),
	array(
		'type' => 'colorpicker',
		'heading' => __( 'Text Color', 'us' ),
		'param_name' => 'us_text_color',
		'value' => '',
		'description' => '',
		'dependency' => array( 'element' => 'color_scheme', 'value' => 'custom' ),
		'edit_field_class' => 'vc_col-sm-6 vc_column',
		'weight' => 14,
	),
	array(
		'type' => 'attach_image',
		'heading' => __( 'Background Image', 'us' ),
		'param_name' => 'us_bg_image',
		'value' => '',
		'description' => __( 'Leave empty if you don\'t want to use the background image', 'us' ),
		'weight' => 13,
	),
	array(
		'type' => 'dropdown',
		'heading' => __( 'Background Image Size', 'us' ),
		'param_name' => 'us_bg_size',
		'value' => array(
			__( 'Cover - Image will cover the whole row area', 'us' ) => 'cover',
			__( 'Contain - Image will fit inside the row area', 'us' ) => 'contain',
			__( 'Initial', 'us' ) => 'initial',
		),
		'std' => 'cover',
		'dependency' => array( 'element' => 'us_bg_image', 'not_empty' => TRUE ),
		'weight' => 12,
	),
	array(
		'type' => 'dropdown',
		'heading' => __( 'Parallax Effect', 'us' ),
		'param_name' => 'us_bg_parallax',
		'value' => array(
			__( 'None', 'us' ) => '',
			__( 'Vertical Parallax', 'us' ) => 'vertical',
			__( 'Horizontal Parallax', 'us' ) => 'horizontal',
			__( 'Still (Image doesn\'t move)', 'us' ) => 'still',
		),
		'std' => '',
		'dependency' => array( 'element' => 'us_bg_image', 'not_empty' => TRUE ),
		'weight' => 11,
	),
	array(
		'type' => 'checkbox',
		'heading' => __( 'Background Video', 'us' ),
		'param_name' => 'us_bg_video',
		'value' => array( __( 'Apply background video to this row', 'us' ) => TRUE ),
		'weight' => 10,
	),
	array(
		'type' => 'textfield',
		'heading' => __( 'MP4 video file', 'us' ),
		'param_name' => 'video_mp4',
		'description' => __( 'Add link to MP4 video file', 'us' ),
		'dependency' => array( 'element' => 'us_bg_video', 'not_empty' => TRUE ),
		'weight' => 9,
	),
	array(
		'type' => 'textfield',
		'heading' => __( 'OGV video file', 'us' ),
		'param_name' => 'video_ogg',
		'description' => __( 'Add link to OGV video file', 'us' ),
		'dependency' => array( 'element' => 'us_bg_video', 'not_empty' => TRUE ),
		'weight' => 8,
	),
	array(
		'type' => 'textfield',
		'heading' => __( 'WebM video file', 'us' ),
		'param_name' => 'video_webm',
		'description' => __( 'Add link to WebM video file', 'us' ),
		'dependency' => array( 'element' => 'us_bg_video', 'not_empty' => TRUE ),
		'weight' => 7,
	),
	array(
		'type' => 'colorpicker',
		'holder' => 'div',
		'class' => '',
		'heading' => __( 'Background Overlay', 'us' ),
		'param_name' => 'us_bg_overlay_color',
		'description' => '',
		'weight' => 6,
	),
) );
if ( class_exists( 'Ultimate_VC_Addons' ) ) {
	vc_add_param( 'vc_row', array(
		'type' => 'ult_param_heading',
		'text' => __( 'Background Image, Background Video, Background Overlay settings located below will override the settings located at "Background" and "Effect" tabs.', 'us' ),
		'param_name' => 'us_notification',
		'edit_field_class' => 'ult-param-important-wrapper ult-dashicon vc_column vc_col-sm-12',
		'weight' => 14,
	) );
}

/**
 * Extending shortcode: vc_row_inner
 */
vc_add_params( 'vc_row_inner', array(
	array(
		'type' => 'dropdown',
		'heading' => __( 'Row Columns Layout', 'us' ),
		'param_name' => 'columns_type',
		'value' => array(
			__( 'With Small gaps', 'us' ) => 'small',
			__( 'With Medium gaps (default)', 'us' ) => 'medium',
			__( 'With Large gaps', 'us' ) => 'large',
			__( 'Boxed and without gaps', 'us' ) => 'none',
		),
		'std' => 'medium',
		'description' => '',
		'weight' => 20,
	),
) );

/**
 * Extending shortcode: vc_column
 */
vc_add_params( 'vc_column', array(
	array(
		'type' => 'colorpicker',
		'class' => '',
		'heading' => __( 'Text Color', 'us' ),
		'param_name' => 'text_color',
		'value' => '',
		'description' => '',
		'weight' => 20,
	),
	array_merge( $us_css_animation, array( 'weight' => 19 ) ),
	array_merge( $us_css_animation_delay, array( 'weight' => 18 ) ),
) );

/**
 * Modifying shortcode: vc_column_text
 */
vc_remove_param( 'vc_column_text', 'css_animation' );

/**
 * Extending shortcode: vc_column_inner
 */
vc_add_params( 'vc_column_inner', array(
	array(
		'type' => 'colorpicker',
		'class' => '',
		'heading' => __( 'Text Color', 'us' ),
		'param_name' => 'text_color',
		'value' => '',
		'description' => '',
		'weight' => 20,
	),
	array_merge( $us_css_animation, array( 'weight' => 19 ) ),
	array_merge( $us_css_animation_delay, array( 'weight' => 18 ) ),
) );

/**
 * Shortcode: us_single_image
 */
include 'shortcode-us-single-image.php';

/**
 * Shortcode: us_gallery
 */
vc_map( array(
	'base' => 'us_gallery',
	'name' => __( 'Image Gallery', 'us' ),
	'icon' => 'icon-wpb-images-stack',
	'category' => __( 'Content', 'us' ),
	'description' => __( 'Responsive image gallery', 'us' ),
	'params' => array(
		array(
			'type' => 'attach_images',
			'heading' => __( 'Images', 'us' ),
			'param_name' => 'ids',
			'value' => '',
			'description' => __( 'Select images from media library.', 'us' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Layout', 'us' ),
			'param_name' => 'layout',
			'value' => array(
				__( 'Default (square thumbnails)', 'us' ) => 'default',
				__( 'Masonry (thumbnails with initial proportions)', 'us' ) => 'masonry',
			),
			'std' => 'default',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Columns', 'us' ),
			'param_name' => 'columns',
			'value' => array(
				__( '1 column', 'us' ) => '1',
				__( '2 columns', 'us' ) => '2',
				__( '3 columns', 'us' ) => '3',
				__( '4 columns', 'us' ) => '4',
				__( '5 columns', 'us' ) => '5',
				__( '6 columns', 'us' ) => '6',
				__( '7 columns', 'us' ) => '7',
				__( '8 columns', 'us' ) => '8',
				__( '9 columns', 'us' ) => '9',
				__( '10 columns', 'us' ) => '10',
			),
			'std' => '6',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Random Order', 'us' ),
			'param_name' => 'orderby',
			'description' => '',
			'value' => array( __( 'Display thumbnails in random order', 'us' ) => 'rand' ),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Indents', 'us' ),
			'param_name' => 'indents',
			'description' => '',
			'value' => array( __( 'Add indents between thumbnails', 'us' ) => TRUE ),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'us' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'us' ),
		),
	),
) );
vc_remove_element( 'vc_gallery' );

/**
 * Shortcode: us_image_slider
 */
vc_map( array(
	'base' => 'us_image_slider',
	'name' => __( 'Image Slider', 'us' ),
	'icon' => 'icon-wpb-images-stack',
	'category' => __( 'Content', 'us' ),
	'params' => array(
		array(
			'type' => 'attach_images',
			'heading' => __( 'Images', 'us' ),
			'param_name' => 'ids',
			'value' => '',
			'description' => __( 'Select images from media library.', 'us' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Navigation Arrows', 'us' ),
			'param_name' => 'arrows',
			'value' => array(
				__( 'Show always', 'us' ) => 'always',
				__( 'Show on hover', 'us' ) => 'hover',
				__( 'Hide', 'us' ) => 'hide',
			),
			'std' => 'always',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-4 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Additional Navigation', 'us' ),
			'param_name' => 'nav',
			'value' => array(
				__( 'None', 'us' ) => 'none',
				__( 'Dots', 'us' ) => 'dots',
				__( 'Thumbs', 'us' ) => 'thumbs',
			),
			'std' => 'none',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-4 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Transition Effect', 'us' ),
			'param_name' => 'transition',
			'value' => array(
				__( 'Slide', 'us' ) => 'slide',
				__( 'Fade', 'us' ) => 'crossfade',
			),
			'std' => 'slide',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-4 vc_column',
		),
		array(
			'type' => 'checkbox',
			'heading' => '',
			'param_name' => 'autoplay',
			'value' => array( __( 'Enable Auto Rotation', 'us' ) => TRUE ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Auto Rotation Period (milliseconds)', 'us' ),
			'param_name' => 'autoplay_period',
			'value' => '3000',
			'dependency' => array( 'element' => 'autoplay', 'not_empty' => TRUE ),
		),
		array(
			'type' => 'checkbox',
			'heading' => '',
			'param_name' => 'fullscreen',
			'value' => array( __( 'Allow Full Screen view', 'us' ) => TRUE ),
		),
		array(
			'type' => 'checkbox',
			'heading' => '',
			'param_name' => 'orderby',
			'value' => array( __( 'Display images in random order', 'us' ) => 'rand' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Images Size', 'us' ),
			'param_name' => 'img_size',
			'value' => us_image_sizes_select_values( array( 'large', 'medium', 'thumbnail', 'full' ) ),
			'std' => 'large',
			'description' => '',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Images Fit', 'us' ),
			'param_name' => 'img_fit',
			'value' => array(
				__( 'Scaledown - Images won\'t be stretched if they are smaller than the slider area', 'us' ) => 'scaledown',
				__( 'Contain - Images will fit inside the slider area', 'us' ) => 'contain',
				__( 'Cover - Images will cover the whole slider area', 'us' ) => 'cover',
			),
			'std' => 'scaledown',
			'description' => '',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'us' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'us' ),
		),
	),
) );
vc_remove_element( 'vc_simple_slider' );

/**
 * Shortcode: us_separator
 */
vc_map( array(
	'base' => 'us_separator',
	'name' => __( 'Separator', 'us' ),
	'icon' => 'icon-wpb-ui-separator',
	'category' => __( 'Content', 'us' ),
	'description' => __( 'Horizontal separator line', 'us' ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Separator Type', 'us' ),
			'param_name' => 'type',
			'value' => array(
				__( 'Default', 'us' ) => 'default',
				__( 'Full Width', 'us' ) => 'fullwidth',
				__( 'Short', 'us' ) => 'short',
				__( 'Invisible', 'us' ) => 'invisible',
			),
			'std' => 'default',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Separator Size', 'us' ),
			'param_name' => 'size',
			'value' => array(
				__( 'Small', 'us' ) => 'small',
				__( 'Medium', 'us' ) => 'medium',
				__( 'Large', 'us' ) => 'large',
				__( 'Huge', 'us' ) => 'huge',
			),
			'std' => 'medium',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Line Thickness', 'us' ),
			'param_name' => 'thick',
			'value' => array(
				'1px' => '1',
				'2px' => '2',
				'3px' => '3',
				'4px' => '4',
				'5px' => '5',
			),
			'std' => '1',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Line Style', 'us' ),
			'param_name' => 'style',
			'value' => array(
				__( 'Solid', 'us' ) => 'solid',
				__( 'Dashed', 'us' ) => 'dashed',
				__( 'Dotted', 'us' ) => 'dotted',
				__( 'Double', 'us' ) => 'double',
			),
			'std' => 'solid',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Line Color', 'us' ),
			'param_name' => 'color',
			'value' => array(
				__( 'Border (theme color)', 'us' ) => 'border',
				__( 'Primary (theme color)', 'us' ) => 'primary',
				__( 'Secondary (theme color)', 'us' ) => 'secondary',
				__( 'Custom Color', 'us' ) => 'custom',
			),
			'std' => 'border',
			'description' => '',
		),
		array(
			'type' => 'colorpicker',
			'class' => '',
			'param_name' => 'bdcolor',
			'value' => '',
			'description' => '',
			'dependency' => array( 'element' => 'color', 'value' => 'custom' ),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Icon (optional)', 'us' ),
			'param_name' => 'icon',
			'value' => '',
			'description' => sprintf( __( '<a href="%s" target="_blank">FontAwesome</a> or <a href="%s" target="_blank">Material Design</a> icon', 'us' ), $url_fontawesome, $url_mdfi ),
			'edit_field_class' => 'vc_col-sm-6 vc_column newline',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Text (optional)', 'us' ),
			'param_name' => 'text',
			'value' => '',
			'holder' => 'div',
			'description' => __( 'Displays text in the middle of this separator', 'us' ),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'us' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'us' ),
		),
	),
) );
vc_remove_element( 'vc_separator' );
vc_remove_element( 'vc_text_separator' );

/**
 * Shortcode: us_btn
 */
vc_map( array(
	'base' => 'us_btn',
	'name' => __( 'Button', 'us' ),
	'icon' => 'icon-wpb-ui-button',
	'category' => __( 'Content', 'us' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Button Label', 'us' ),
			'holder' => 'button',
			'class' => 'wpb_button',
			'param_name' => 'text',
			'value' => __( 'Click Me', 'us' ),
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Button Icon (optional)', 'us' ),
			'param_name' => 'icon',
			'description' => sprintf( __( '<a href="%s" target="_blank">FontAwesome</a> or <a href="%s" target="_blank">Material Design</a> icon', 'us' ), $url_fontawesome, $url_mdfi ),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button Style', 'us' ),
			'param_name' => 'style',
			'value' => array(
				__( 'Raised', 'us' ) => 'raised',
				__( 'Flat', 'us' ) => 'flat',
			),
			'std' => 'raised',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button Color', 'us' ),
			'param_name' => 'color',
			'value' => array(
				__( 'Primary (theme color)', 'us' ) => 'primary',
				__( 'Secondary (theme color)', 'us' ) => 'secondary',
				__( 'Light (theme color)', 'us' ) => 'light',
				__( 'Contrast (theme color)', 'us' ) => 'contrast',
				__( 'Black', 'us' ) => 'black',
				__( 'White', 'us' ) => 'white',
				__( 'Custom colors', 'us' ) => 'custom',
			),
			'std' => 'primary',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'colorpicker',
			'class' => '',
			'heading' => __( 'Background Color', 'us' ),
			'param_name' => 'bg_color',
			'value' => '',
			'description' => '',
			'dependency' => array( 'element' => 'color', 'value' => 'custom' ),
		),
		array(
			'type' => 'colorpicker',
			'class' => '',
			'heading' => __( 'Text Color', 'us' ),
			'param_name' => 'text_color',
			'value' => '',
			'description' => '',
			'dependency' => array( 'element' => 'color', 'value' => 'custom' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Icon Position', 'us' ),
			'param_name' => 'iconpos',
			'value' => array(
				__( 'Left', 'us' ) => 'left',
				__( 'Right', 'us' ) => 'right',
			),
			'std' => 'left',
			'description' => '',
			'dependency' => array( 'element' => 'icon', 'not_empty' => TRUE ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button Size', 'us' ),
			'param_name' => 'size',
			'value' => array(
				__( 'Medium', 'us' ) => 'medium',
				__( 'Large', 'us' ) => 'large',
			),
			'std' => 'medium',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button Alignment', 'us' ),
			'param_name' => 'align',
			'value' => array(
				__( 'Left', 'us' ) => 'left',
				__( 'Center', 'us' ) => 'center',
				__( 'Right', 'us' ) => 'right',
			),
			'std' => 'left',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'vc_link',
			'heading' => __( 'Button Link', 'us' ),
			'param_name' => 'link',
			'description' => '',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'us' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'us' ),
		),
	),
	'js_view' => 'VcButtonView',
) );
vc_remove_element( 'vc_button' );
vc_remove_element( 'vc_button2' );
vc_remove_element( 'vc_btn' );

/**
 * Modifying shortcode: vc_tta_tabs
 */
if ( version_compare( WPB_VC_VERSION, '4.6', '>=' ) AND ! vc_is_page_editable() ) {
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
		'type' => 'checkbox',
		'heading' => __( 'Act as Timeline', 'us' ),
		'param_name' => 'layout',
		'description' => '',
		'value' => array( __( 'Change look and feel into Timeline', 'us' ) => 'timeline' ),
	) );
	// The only available way to preserve param order :(
	// TODO When some vc_modify_param will be available, reorder params by other means
	vc_remove_param( 'vc_tta_tabs', 'el_class' );
	vc_add_param( 'vc_tta_tabs', array(
		'type' => 'textfield',
		'heading' => __( 'Extra class name', 'us' ),
		'param_name' => 'el_class',
		'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'us' ),
	) );
}

/**
 * Modifying shortcode: vc_tta_accordion
 */
if ( version_compare( WPB_VC_VERSION, '4.6', '>=' ) AND ! vc_is_page_editable() ) {
	vc_remove_param( 'vc_tta_accordion', 'title' );
	vc_remove_param( 'vc_tta_accordion', 'style' );
	vc_remove_param( 'vc_tta_accordion', 'shape' );
	vc_remove_param( 'vc_tta_accordion', 'color' );
	vc_remove_param( 'vc_tta_accordion', 'no_fill' );
	vc_remove_param( 'vc_tta_accordion', 'spacing' );
	vc_remove_param( 'vc_tta_accordion', 'gap' );
	vc_remove_param( 'vc_tta_accordion', 'autoplay' );
	vc_remove_param( 'vc_tta_accordion', 'collapsible_all' );
	vc_remove_param( 'vc_tta_accordion', 'active_section' );
	vc_remove_param( 'vc_tta_accordion', 'c_align' );
	vc_remove_param( 'vc_tta_accordion', 'c_icon' );
	vc_remove_param( 'vc_tta_accordion', 'c_position' );
	vc_add_params( 'vc_tta_accordion', array(
		array(
			'type' => 'checkbox',
			'heading' => __( 'Act as Toggles', 'us' ),
			'param_name' => 'toggle',
			'value' => array( __( 'Allow several sections to be opened at the same time', 'us' ) => TRUE ),
			'weight' => 20,
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Title Alignment', 'us' ),
			'param_name' => 'c_align',
			'value' => array(
				__( 'Left', 'us' ) => 'left',
				__( 'Right', 'us' ) => 'right',
				__( 'Center', 'us' ) => 'center',
			),
			'std' => 'left',
			'weight' => 19,
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Icon', 'us' ),
			'param_name' => 'c_icon',
			'value' => array(
				__( 'None', 'us' ) => '',
				__( 'Chevron', 'us' ) => 'chevron',
				__( 'Plus', 'us' ) => 'plus',
				__( 'Triangle', 'us' ) => 'triangle',
			),
			'std' => 'chevron',
			'description' => __( 'Select accordion navigation icon.', 'us' ),
			'weight' => 18,
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Icon Position', 'us' ),
			'param_name' => 'c_position',
			'value' => array(
				__( 'Left', 'us' ) => 'left',
				__( 'Right', 'us' ) => 'right',
			),
			'std' => 'right',
			'dependency' => array(
				'element' => 'c_icon',
				'not_empty' => TRUE,
			),
			'description' => __( 'Select accordion navigation icon position.', 'us' ),
			'weight' => 17,
		),
	) );
}

/**
 * Modifying shortcode: vc_tta_tour
 */
if ( version_compare( WPB_VC_VERSION, '4.6', '>=' ) AND ! vc_is_page_editable() ) {
	vc_remove_param( 'vc_tta_tour', 'title' );
	vc_remove_param( 'vc_tta_tour', 'style' );
	vc_remove_param( 'vc_tta_tour', 'shape' );
	vc_remove_param( 'vc_tta_tour', 'color' );
	vc_remove_param( 'vc_tta_tour', 'no_fill_content_area' );
	vc_remove_param( 'vc_tta_tour', 'spacing' );
	vc_remove_param( 'vc_tta_tour', 'gap' );
	vc_remove_param( 'vc_tta_tour', 'tab_position' );
	vc_remove_param( 'vc_tta_tour', 'alignment' );
	vc_remove_param( 'vc_tta_tour', 'controls_size' );
	vc_remove_param( 'vc_tta_tour', 'autoplay' );
	vc_remove_param( 'vc_tta_tour', 'active_section' );
	vc_remove_param( 'vc_tta_tour', 'pagination_style' );
	vc_remove_param( 'vc_tta_tour', 'pagination_color' );

	vc_add_params( 'vc_tta_tour', array(
		array(
			'type' => 'dropdown',
			'param_name' => 'tab_position',
			'value' => array(
				__( 'Left', 'us' ) => 'left',
				__( 'Right', 'us' ) => 'right'
			),
			'heading' => __( 'Tabs Position', 'us' ),
			'weight' => 20,
		),
		array(
			'type' => 'dropdown',
			'param_name' => 'c_align',
			'value' => array(
				__( 'Left', 'us' ) => 'left',
				__( 'Center', 'us' ) => 'center',
				__( 'Right', 'us' ) => 'right',
			),
			'heading' => __( 'Tabs Text Alignment', 'us' ),
			'weight' => 19,
		),
		array(
			'type' => 'dropdown',
			'param_name' => 'controls_size',
			'value' => array(
				__( 'Auto', 'us' ) => 'auto',
				'10%' => '10',
				'20%' => '20',
				'30%' => '30',
				'40%' => '40',
				'50%' => '50',
			),
			'heading' => __( 'Tabs Width', 'us' ),
			'weight' => 18,
		),
	) );
}

/**
 * Modifying shortcode: vc_tta_section
 */
if ( version_compare( WPB_VC_VERSION, '4.6', '>=' ) AND  ! vc_is_page_editable() ) {
	vc_remove_param( 'vc_tta_section', 'add_icon' );
	vc_remove_param( 'vc_tta_section', 'i_type' );
	vc_remove_param( 'vc_tta_section', 'i_icon_fontawesome' );
	vc_remove_param( 'vc_tta_section', 'i_icon_openiconic' );
	vc_remove_param( 'vc_tta_section', 'i_icon_typicons' );
	vc_remove_param( 'vc_tta_section', 'i_icon_entypo' );
	vc_remove_param( 'vc_tta_section', 'i_icon_linecons' );
	vc_remove_param( 'vc_tta_section', 'i_position' );
	vc_remove_param( 'vc_tta_section', 'tab_id' );
	vc_add_params( 'vc_tta_section', array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Tab Icon (optional)', 'us' ),
			'param_name' => 'icon',
			'description' => sprintf( __( '<a href="%s" target="_blank">FontAwesome</a> or <a href="%s" target="_blank">Material Design</a> icon', 'us' ), $url_fontawesome, $url_mdfi ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Icon position', 'us' ),
			'param_name' => 'i_position',
			'value' => array(
				__( 'Before title', 'js_composer' ) => 'left',
				__( 'After title', 'js_composer' ) => 'right',
			),
			'std' => 'left',
			'dependency' => array( 'element' => 'icon', 'not_empty' => TRUE ),
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Active', 'us' ),
			'param_name' => 'active',
			'value' => array( __( 'Show this section when the page loads', 'us' ) => TRUE ),
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Full Size Content', 'us' ),
			'param_name' => 'indents',
			'value' => array( __( 'Remove paddings in the section\'s content area', 'us' ) => 'none' ),
		),
		array(
			'type' => 'colorpicker',
			'holder' => 'div',
			'class' => '',
			'heading' => __( 'Background Color', 'us' ),
			'param_name' => 'bg_color',
			'value' => '',
			'description' => '',
		),
		array(
			'type' => 'colorpicker',
			'holder' => 'div',
			'class' => '',
			'heading' => __( 'Text Color', 'us' ),
			'param_name' => 'text_color',
			'value' => '',
			'description' => '',
		),
	) );
	// The only available way to preserve param order :(
	// TODO When some vc_modify_param will be available, reorder params by other means
	vc_remove_param( 'vc_tta_section', 'el_class' );
	vc_add_params( 'vc_tta_section', array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'us' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'us' ),
		),
	) );
}

/**
 * Shortcode: us_iconbox
 */
vc_map( array(
	'base' => 'us_iconbox',
	'name' => __( 'IconBox', 'us' ),
	'icon' => 'icon-wpb-ui-separator-label',
	'category' => __( 'Content', 'us' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Icon', 'us' ),
			'param_name' => 'icon',
			'value' => 'star',
			'description' => sprintf( __( '<a href="%s" target="_blank">FontAwesome</a> or <a href="%s" target="_blank">Material Design</a> icon', 'us' ), $url_fontawesome, $url_mdfi ),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Icon Style', 'us' ),
			'param_name' => 'style',
			'value' => array(
				__( 'Simple', 'us' ) => 'default',
				__( 'Inside the circle', 'us' ) => 'circle',
			),
			'std' => 'default',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Icon Color', 'us' ),
			'param_name' => 'color',
			'value' => array(
				__( 'Primary (theme color)', 'us' ) => 'primary',
				__( 'Secondary (theme color)', 'us' ) => 'secondary',
				__( 'Light (theme color)', 'us' ) => 'light',
				__( 'Contrast (theme color)', 'us' ) => 'contrast',
				__( 'Custom colors', 'us' ) => 'custom',
			),
			'std' => 'primary',
			'description' => '',
		),
		array(
			'type' => 'colorpicker',
			'class' => '',
			'heading' => __( 'Icon Color', 'us' ),
			'param_name' => 'icon_color',
			'value' => '',
			'description' => '',
			'dependency' => array( 'element' => 'color', 'value' => 'custom' ),
		),
		array(
			'type' => 'colorpicker',
			'class' => '',
			'heading' => __( 'Icon Circle Color', 'us' ),
			'param_name' => 'bg_color',
			'value' => '',
			'description' => '',
			'dependency' => array( 'element' => 'color', 'value' => 'custom' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Icon Position', 'us' ),
			'param_name' => 'iconpos',
			'value' => array(
				__( 'Top', 'us' ) => 'top',
				__( 'Left', 'us' ) => 'left',
			),
			'std' => 'top',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Icon Size', 'us' ),
			'param_name' => 'size',
			'value' => array(
				__( 'Tiny', 'us' ) => 'tiny',
				__( 'Small', 'us' ) => 'small',
				__( 'Medium', 'us' ) => 'medium',
				__( 'Large', 'us' ) => 'large',
				__( 'Huge', 'us' ) => 'huge',
			),
			'std' => 'medium',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', 'us' ),
			'param_name' => 'title',
			'holder' => 'div',
			'value' => '',
			'description' => '',
		),
		array(
			'type' => 'textarea',
			'heading' => __( 'Iconbox content (optional)', 'us' ),
			'param_name' => 'content',
			'value' => '',
			'description' => '',
		),
		array(
			'type' => 'vc_link',
			'heading' => __( 'Link (optional)', 'us' ),
			'param_name' => 'link',
			'value' => '',
			'description' => '',
		),
		array(
			'type' => 'attach_image',
			'heading' => __( 'Image (optional)', 'us' ),
			'param_name' => 'img',
			'value' => '',
			'description' => __( 'Set an image, which overrides the font icon', 'us' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'us' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'us' ),
		),
	),
) );

/**
 * Shortcode: us_testimonial
 */
vc_map( array(
	'base' => 'us_testimonial',
	'name' => __( 'Testimonial', 'us' ),
	'icon' => 'icon-wpb-ui-separator-label',
	'category' => __( 'Content', 'us' ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Quote Style', 'us' ),
			'param_name' => 'style',
			'value' => array(
				__( 'Card Style', 'us' ) => '1',
				__( 'Flat Style', 'us' ) => '2',
			),
			'std' => '1',
			'description' => '',
		),
		array(
			'type' => 'textarea',
			'admin_label' => TRUE,
			'heading' => __( 'Quote Text', 'us' ),
			'param_name' => 'content',
			'value' => __( 'Text goes here', 'us' ),
			'description' => '',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Author Name', 'us' ),
			'param_name' => 'author',
			'value' => __( 'Jon Snow', 'us' ),
			'description' => __( 'Enter the Name of the Person to quote', 'us' ),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Author Subtitle', 'us' ),
			'param_name' => 'company',
			'value' => __( 'Lord Commander', 'us' ),
			'description' => __( 'Can be used for a job description', 'us' ),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'attach_image',
			'heading' => __( 'Author Photo (optional)', 'us' ),
			'param_name' => 'img',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'us' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'us' ),
		),
	),
) );

/**
 * Shortcode: us_person
 */
include 'shortcode-us-person.php';

/**
 * Shortcode: us_portfolio
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
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Columns', 'us' ),
			'param_name' => 'columns',
			'value' => array(
				__( '2 columns', 'us' ) => '2',
				__( '3 columns', 'us' ) => '3',
				__( '4 columns', 'us' ) => '4',
				__( '5 columns', 'us' ) => '5',
			),
			'std' => '3',
			'admin_label' => TRUE,
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Pagination', 'us' ),
			'param_name' => 'pagination',
			'value' => array(
				__( 'No pagination', 'us' ) => 'none',
				__( 'Regular pagination', 'us' ) => 'regular',
				__( 'Load More Button', 'us' ) => 'ajax',
			),
			'std' => 'none',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Items Quantity', 'us' ),
			'param_name' => 'items',
			'value' => '',
			'description' => __( 'If left blank, will output all the items', 'us' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Items Style', 'us' ),
			'param_name' => 'style',
			'value' => array(
				__( 'Style 1', 'us' ) => 'style_1',
				__( 'Style 2', 'us' ) => 'style_2',
				__( 'Style 3', 'us' ) => 'style_3',
				__( 'Style 4', 'us' ) => 'style_4',
				__( 'Style 5', 'us' ) => 'style_5',
			),
			'std' => 'style_1',
			'admin_label' => TRUE,
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Items Text Alignment', 'us' ),
			'param_name' => 'align',
			'value' => array(
				__( 'Left', 'us' ) => 'left',
				__( 'Center', 'us' ) => 'center',
				__( 'Right', 'us' ) => 'right',
			),
			'std' => 'center',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Items Ratio', 'us' ),
			'param_name' => 'ratio',
			'value' => array(
				__( '4:3 (landscape)', 'us' ) => '4x3',
				__( '3:2 (landscape)', 'us' ) => '3x2',
				__( '1:1 (square)', 'us' ) => '1x1',
				__( '2:3 (portrait)', 'us' ) => '2x3',
				__( '3:4 (portrait)', 'us' ) => '3x4',
			),
			'std' => '1x1',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Items Meta', 'us' ),
			'param_name' => 'meta',
			'value' => array(
				__( 'Do not show', 'us' ) => '',
				__( 'Show Item date', 'us' ) => 'date',
				__( 'Show Item categories', 'us' ) => 'categories',
			),
			'std' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Filtering', 'us' ),
			'param_name' => 'filter',
			'description' => '',
			'value' => array( __( 'Enable filtering by category', 'us' ) => 'category' ),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Items Indents', 'us' ),
			'param_name' => 'with_indents',
			'description' => '',
			'value' => array( __( 'Add indents between Items', 'us' ) => TRUE ),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Random Order', 'us' ),
			'param_name' => 'orderby',
			'description' => '',
			'value' => array( __( 'Display items in random order', 'us' ) => 'rand' ),
		),
	),

) );
if ( ! empty( $us_portfolio_categories ) ) {
	vc_add_param( 'us_portfolio', array(
		'type' => 'checkbox',
		'heading' => __( 'Display Items of selected categories', 'us' ),
		'param_name' => 'categories',
		'value' => $us_portfolio_categories,
		'description' => '',
	) );
}
vc_add_param( 'us_portfolio', array(
	'type' => 'textfield',
	'heading' => __( 'Extra class name', 'us' ),
	'param_name' => 'el_class',
	'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'us' ),
) );

/**
 * Shortcode: us_blog
 */
$us_post_categories = array();
$us_post_categories_raw = get_categories( "hierarchical=0" );
foreach ( $us_post_categories_raw as $post_category_raw ) {
	$us_post_categories[ $post_category_raw->name ] = $post_category_raw->slug;
}
vc_map( array(
	'base' => 'us_blog',
	'name' => __( 'Blog', 'us' ),
	'icon' => 'icon-wpb-ui-separator-label',
	'category' => __( 'Content', 'us' ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Layout Type', 'us' ),
			'param_name' => 'layout',
			'value' => array(
				__( 'Small Image', 'us' ) => 'smallcircle',
				__( 'Large Image', 'us' ) => 'large',
				__( 'Regular Grid', 'us' ) => 'grid',
				__( 'Masonry Grid', 'us' ) => 'masonry',
				__( 'Compact', 'us' ) => 'compact',
			),
			'std' => 'large',
			'admin_label' => TRUE,
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Posts Content', 'us' ),
			'param_name' => 'content_type',
			'value' => array(
				__( 'Excerpt', 'us' ) => 'excerpt',
				__( 'Full Content', 'us' ) => 'content',
				__( 'None', 'us' ) => 'none',
			),
			'std' => 'excerpt',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Posts Quantity', 'us' ),
			'param_name' => 'items',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Pagination', 'us' ),
			'param_name' => 'pagination',
			'value' => array(
				__( 'No pagination', 'us' ) => 'none',
				__( 'Regular pagination', 'us' ) => 'regular',
				__( 'Load More Button', 'us' ) => 'ajax',
			),
			'std' => 'none',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'checkbox',
			'heading' => '',
			'param_name' => 'show_date',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'value' => array( __( 'Show Post Date', 'us' ) => TRUE ),
			'std' => TRUE,
		),
		array(
			'type' => 'checkbox',
			'heading' => '',
			'param_name' => 'show_author',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'value' => array( __( 'Show Post Author', 'us' ) => TRUE ),
			'std' => TRUE,
		),
		array(
			'type' => 'checkbox',
			'heading' => '',
			'param_name' => 'show_categories',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'value' => array( __( 'Show Post Categories', 'us' ) => TRUE ),
			'std' => TRUE,
		),
		array(
			'type' => 'checkbox',
			'heading' => '',
			'param_name' => 'show_tags',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'value' => array( __( 'Show Post Tags', 'us' ) => TRUE ),
			'std' => TRUE,
		),
		array(
			'type' => 'checkbox',
			'heading' => '',
			'param_name' => 'show_comments',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'value' => array( __( 'Show Post Comments', 'us' ) => TRUE ),
			'std' => TRUE,
		),
		array(
			'type' => 'checkbox',
			'heading' => '',
			'param_name' => 'show_read_more',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'value' => array( __( 'Show Read More button', 'us' ) => TRUE ),
			'std' => TRUE,
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Display Posts of selected categories', 'us' ),
			'param_name' => 'categories',
			'value' => $us_post_categories,
			'description' => '',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'us' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'us' ),
		),
	),
) );

/**
 * Shortcode: us_logos
 */
vc_map( array(
	'base' => 'us_logos',
	'name' => __( 'Client Logos', 'us' ),
	'icon' => 'icon-wpb-ui-separator-label',
	'category' => __( 'Content', 'us' ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Quantity of displayed items', 'us' ),
			'param_name' => 'columns',
			'value' => array(
				__( '1 item', 'us' ) => '1',
				__( '2 items', 'us' ) => '2',
				__( '3 items', 'us' ) => '3',
				__( '4 items', 'us' ) => '4',
				__( '5 items', 'us' ) => '5',
				__( '6 items', 'us' ) => '6',
			),
			'std' => '5',
			'description' => '',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Hover style', 'us' ),
			'param_name' => 'style',
			'value' => array(
				__( 'Card Style', 'us' ) => '1',
				__( 'Flat Style', 'us' ) => '2',
			),
			'std' => '1',
			'description' => '',
		),
		array(
			'type' => 'checkbox',
			'heading' => '',
			'param_name' => 'arrows',
			'value' => array( __( 'Show Navigation Arrows', 'us' ) => TRUE ),
		),
		array(
			'type' => 'checkbox',
			'heading' => '',
			'param_name' => 'orderby',
			'value' => array( __( 'Display items in random order', 'us' ) => 'rand' ),
		),
		array(
			'type' => 'checkbox',
			'heading' => '',
			'param_name' => 'auto_scroll',
			'value' => array( __( 'Enable Auto Rotation', 'us' ) => TRUE ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Auto Rotation Interval (in seconds)', 'us' ),
			'param_name' => 'interval',
			'value' => 3,
			'description' => '',
			'dependency' => array( 'element' => 'auto_scroll', 'not_empty' => TRUE ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'us' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'us' ),
		),
	),
) );
vc_remove_element( 'vc_images_carousel' );

/**
 * Shortcode: us_cta
 */
vc_map( array(
	'base' => 'us_cta',
	'name' => __( 'ActionBox', 'us' ),
	'icon' => 'icon-wpb-ui-separator-label',
	'category' => __( 'Content', 'us' ),
	'description' => __( 'Call to action', 'us' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'ActionBox Title', 'us' ),
			'param_name' => 'title',
			'holder' => 'div',
			'value' => __( 'This is ActionBox', 'us' ),
			'description' => '',
		),
		array(
			'type' => 'textarea',
			'heading' => __( 'ActionBox Text', 'us' ),
			'param_name' => 'message',
			'value' => '',
			'description' => '',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'ActionBox Color Style', 'us' ),
			'param_name' => 'color',
			'value' => array(
				__( 'Alternate bg | Content text', 'us' ) => 'alternate',
				__( 'Primary bg | White text', 'us' ) => 'primary',
				__( 'Secondary bg | White text', 'us' ) => 'secondary',
				__( 'Custom colors', 'us' ) => 'custom',
			),
			'std' => 'alternate',
			'description' => '',
		),
		array(
			'type' => 'colorpicker',
			'class' => '',
			'heading' => __( 'Background Color', 'us' ),
			'param_name' => 'bg_color',
			'value' => '',
			'description' => '',
			'dependency' => array( 'element' => 'color', 'value' => 'custom' ),
		),
		array(
			'type' => 'colorpicker',
			'class' => '',
			'heading' => __( 'Text Color', 'us' ),
			'param_name' => 'text_color',
			'value' => '',
			'description' => '',
			'dependency' => array( 'element' => 'color', 'value' => 'custom' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button(s) Location', 'us' ),
			'param_name' => 'controls',
			'value' => array(
				__( 'At Right', 'us' ) => 'right',
				__( 'At Bottom', 'us' ) => 'bottom',
			),
			'std' => 'right',
			'description' => '',
		),
		array(
			'type' => 'vc_link',
			'heading' => __( 'Button Link', 'us' ),
			'param_name' => 'btn_link',
			'value' => '#',
			'description' => '',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Button Label', 'us' ),
			'param_name' => 'btn_label',
			'value' => __( 'Click Me', 'us' ),
			'description' => '',
			'edit_field_class' => 'vc_col-sm-4 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button Style', 'us' ),
			'param_name' => 'btn_style',
			'value' => array(
				__( 'Raised', 'us' ) => 'raised',
				__( 'Flat', 'us' ) => 'flat',
			),
			'std' => 'raised',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-4 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button Color', 'us' ),
			'param_name' => 'btn_color',
			'value' => array(
				__( 'Primary (theme color)', 'us' ) => 'primary',
				__( 'Secondary (theme color)', 'us' ) => 'secondary',
				__( 'Light (theme color)', 'us' ) => 'light',
				__( 'Contrast (theme color)', 'us' ) => 'contrast',
				__( 'Black', 'us' ) => 'black',
				__( 'White', 'us' ) => 'white',
				__( 'Custom colors', 'us' ) => 'custom',
			),
			'std' => 'primary',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-4 vc_column',
		),
		array(
			'type' => 'colorpicker',
			'class' => '',
			'heading' => __( 'Button Background Color', 'us' ),
			'param_name' => 'btn_bg_color',
			'value' => '',
			'description' => '',
			'dependency' => array( 'element' => 'btn_color', 'value' => 'custom' ),
		),
		array(
			'type' => 'colorpicker',
			'class' => '',
			'heading' => __( 'Button Text Color', 'us' ),
			'param_name' => 'btn_text_color',
			'value' => '',
			'description' => '',
			'dependency' => array( 'element' => 'btn_color', 'value' => 'custom' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button Size', 'us' ),
			'param_name' => 'btn_size',
			'value' => array(
				__( 'Medium', 'us' ) => 'medium',
				__( 'Large', 'us' ) => 'large',
			),
			'std' => 'medium',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-4 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Button Icon (optional)', 'us' ),
			'param_name' => 'btn_icon',
			'description' => sprintf( __( '<a href="%s" target="_blank">FontAwesome</a> or <a href="%s" target="_blank">Material Design</a> icon', 'us' ), $url_fontawesome, $url_mdfi ),
			'edit_field_class' => 'vc_col-sm-4 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button Icon Position', 'us' ),
			'param_name' => 'btn_iconpos',
			'value' => array(
				__( 'Left', 'us' ) => 'left',
				__( 'Right', 'us' ) => 'right',
			),
			'std' => 'left',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-4 vc_column',
		),
		array(
			'type' => 'checkbox',
			'heading' => '',
			'param_name' => 'second_button',
			'value' => array( __( 'Display second button', 'us' ) => TRUE ),
		),
		array(
			'type' => 'vc_link',
			'heading' => __( 'Second Button Link', 'us' ),
			'param_name' => 'btn2_link',
			'value' => '#',
			'description' => '',
			'dependency' => array( 'element' => 'second_button', 'not_empty' => TRUE ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Second Button Label', 'us' ),
			'param_name' => 'btn2_label',
			'value' => __( 'Or Me', 'us' ),
			'description' => '',
			'dependency' => array( 'element' => 'second_button', 'not_empty' => TRUE ),
			'edit_field_class' => 'vc_col-sm-4 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Second Button Style', 'us' ),
			'param_name' => 'btn2_style',
			'value' => array(
				__( 'Raised', 'us' ) => 'raised',
				__( 'Flat', 'us' ) => 'flat',
			),
			'std' => 'raised',
			'description' => '',
			'dependency' => array( 'element' => 'second_button', 'not_empty' => TRUE ),
			'edit_field_class' => 'vc_col-sm-4 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Second Button Color', 'us' ),
			'param_name' => 'btn2_color',
			'value' => array(
				__( 'Primary (theme color)', 'us' ) => 'primary',
				__( 'Secondary (theme color)', 'us' ) => 'secondary',
				__( 'Light (theme color)', 'us' ) => 'light',
				__( 'Contrast (theme color)', 'us' ) => 'contrast',
				__( 'Black', 'us' ) => 'black',
				__( 'White', 'us' ) => 'white',
				__( 'Custom colors', 'us' ) => 'custom',
			),
			'std' => 'secondary',
			'description' => '',
			'dependency' => array( 'element' => 'second_button', 'not_empty' => TRUE ),
			'edit_field_class' => 'vc_col-sm-4 vc_column',
		),
		array(
			'type' => 'colorpicker',
			'class' => '',
			'heading' => __( 'Button Background Color', 'us' ),
			'param_name' => 'btn2_bg_color',
			'value' => '',
			'description' => '',
			'dependency' => array( 'element' => 'btn2_color', 'value' => 'custom' ),
		),
		array(
			'type' => 'colorpicker',
			'class' => '',
			'heading' => __( 'Button Text Color', 'us' ),
			'param_name' => 'btn2_text_color',
			'value' => '',
			'description' => '',
			'dependency' => array( 'element' => 'btn2_color', 'value' => 'custom' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Second Button Size', 'us' ),
			'param_name' => 'btn2_size',
			'value' => array(
				__( 'Medium', 'us' ) => 'medium',
				__( 'Large', 'us' ) => 'large',
			),
			'std' => 'medium',
			'description' => '',
			'dependency' => array( 'element' => 'second_button', 'not_empty' => TRUE ),
			'edit_field_class' => 'vc_col-sm-4 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Second Button Icon (optional)', 'us' ),
			'param_name' => 'btn2_icon',
			'description' => sprintf( __( '<a href="%s" target="_blank">FontAwesome</a> or <a href="%s" target="_blank">Material Design</a> icon', 'us' ), $url_fontawesome, $url_mdfi ),
			'dependency' => array( 'element' => 'second_button', 'not_empty' => TRUE ),
			'edit_field_class' => 'vc_col-sm-4 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Second Button Icon Position', 'us' ),
			'param_name' => 'btn2_iconpos',
			'value' => array(
				__( 'Left', 'us' ) => 'left',
				__( 'Right', 'us' ) => 'right',
			),
			'std' => 'left',
			'description' => '',
			'dependency' => array( 'element' => 'second_button', 'not_empty' => TRUE ),
			'edit_field_class' => 'vc_col-sm-4 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'us' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'us' ),
		),
	),
) );
vc_remove_element( 'vc_cta' );

/**
 * Modifying shortcode: vc_video
 */
vc_remove_param( 'vc_video', 'title' );
vc_add_params( 'vc_video', array(
	array(
		'type' => 'textfield',
		'heading' => __( 'Video link', 'us' ),
		'param_name' => 'link',
		'value' => 'http://vimeo.com/23237102',
		'admin_label' => TRUE,
		'description' => sprintf( __( 'Link to the video. More about supported formats at %s.', 'us' ), '<a href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F" target="_blank">WordPress codex page</a>' ),
	),
	array(
		'type' => 'dropdown',
		'heading' => __( 'Ratio', 'us' ),
		'param_name' => 'ratio',
		'value' => array(
			'16x9' => '16x9',
			'4x3' => '4x3',
			'3x2' => '3x2',
			'1x1' => '1x1',
		),
		'std' => '16x9',
		'description' => '',
	),
	array(
		'type' => 'textfield',
		'heading' => __( 'Max Width in pixels', 'us' ),
		'param_name' => 'max_width',
		'admin_label' => TRUE,
		'description' => '',
	),
	array(
		'type' => 'dropdown',
		'heading' => __( 'Video Alignment', 'us' ),
		'param_name' => 'align',
		'value' => array(
			__( 'Left', 'us' ) => 'left',
			__( 'Center', 'us' ) => 'center',
			__( 'Right', 'us' ) => 'right',
		),
		'std' => 'left',
		'description' => '',
		'dependency' => array( 'element' => 'max_width', 'not_empty' => TRUE ),
	),
) );
// The only available way to preserve param order :(
// TODO When some vc_modify_param will be available, reorder params by other means
vc_remove_param( 'vc_video', 'el_class' );
vc_remove_param( 'vc_video', 'css' );
vc_add_params( 'vc_video', array(
	array(
		'type' => 'textfield',
		'heading' => __( 'Extra class name', 'us' ),
		'param_name' => 'el_class',
		'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'us' ),
	),
	array(
		'type' => 'css_editor',
		'heading' => 'CSS',
		'param_name' => 'css',
		'group' => __( 'Design options', 'us' ),
	),
) );

/**
 * Shortcode: us_message
 */
vc_map( array(
	'base' => 'us_message',
	'name' => __( 'Message Box', 'us' ),
	'icon' => 'icon-wpb-information-white',
	'wrapper_class' => 'alert',
	'category' => __( 'Content', 'us' ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Color Style', 'us' ),
			'param_name' => 'color',
			'value' => array(
				__( 'Notification (blue)', 'us' ) => 'info',
				__( 'Attention (yellow)', 'us' ) => 'attention',
				__( 'Success (green)', 'us' ) => 'success',
				__( 'Error (red)', 'us' ) => 'error',
				__( 'Custom colors', 'us' ) => 'custom',
			),
			'std' => 'info',
			'description' => '',
		),
		array(
			'type' => 'colorpicker',
			'holder' => 'div',
			'class' => '',
			'heading' => __( 'Background Color', 'us' ),
			'param_name' => 'bg_color',
			'value' => '',
			'description' => '',
			'dependency' => array( 'element' => 'color', 'value' => 'custom' ),
		),
		array(
			'type' => 'colorpicker',
			'holder' => 'div',
			'class' => '',
			'heading' => __( 'Text Color', 'us' ),
			'param_name' => 'text_color',
			'value' => '',
			'description' => '',
			'dependency' => array( 'element' => 'color', 'value' => 'custom' ),
		),
		array(
			'type' => 'textarea',
			'holder' => 'div',
			'class' => 'content',
			'heading' => __( 'Message Text', 'us' ),
			'param_name' => 'content',
			'value' => __( 'I am message box. Click edit button to change this text.', 'us' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Icon (optional)', 'us' ),
			'param_name' => 'icon',
			'value' => '',
			'description' => sprintf( __( '<a href="%s" target="_blank">FontAwesome</a> or <a href="%s" target="_blank">Material Design</a> icon', 'us' ), $url_fontawesome, $url_mdfi ),
		),
		array(
			'type' => 'checkbox',
			'heading' => '',
			'param_name' => 'closing',
			'value' => array( __( 'Enable closing', 'us' ) => TRUE ),
			'description' => '',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'us' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'us' ),
		),
	),
	'js_view' => 'VcMessageView',
) );
vc_remove_element( 'vc_message' );

/**
 * Shortcode: us_counter
 */
vc_map( array(
	'base' => 'us_counter',
	'name' => __( 'Counter', 'us' ),
	'icon' => 'icon-wpb-ui-separator',
	'category' => __( 'Content', 'us' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'The initial number value', 'us' ),
			'param_name' => 'initial',
			'value' => '0',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'The final number value', 'us' ),
			'param_name' => 'target',
			'value' => '99',
			'holder' => 'span',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Number Color', 'us' ),
			'param_name' => 'color',
			'value' => array(
				__( 'Heading (theme color)', 'us' ) => 'text',
				__( 'Primary (theme color)', 'us' ) => 'primary',
				__( 'Secondary (theme color)', 'us' ) => 'secondary',
				__( 'Custom Color', 'us' ) => 'custom',
			),
			'std' => 'text',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Number Size', 'us' ),
			'param_name' => 'size',
			'value' => array(
				__( 'Small', 'us' ) => 'small',
				__( 'Medium', 'us' ) => 'medium',
				__( 'Large', 'us' ) => 'large',
			),
			'std' => 'medium',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'colorpicker',
			'heading' => '',
			'param_name' => 'custom_color',
			'description' => '',
			'dependency' => array( 'element' => 'color', 'value' => 'custom' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Title for Counter', 'us' ),
			'param_name' => 'title',
			'value' => __( 'Projects completed', 'us' ),
			'holder' => 'span',
			'description' => ''
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Prefix (optional)', 'us' ),
			'param_name' => 'prefix',
			'value' => '',
			'description' => __( 'Text before number', 'us' ),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Suffix (optional)', 'us' ),
			'param_name' => 'suffix',
			'value' => '',
			'description' => __( 'Text after number', 'us' ),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'us' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'us' ),
		),
	),
) );

/**
 * Shortcode: us_feedback
 */
vc_map( array(
	'base' => 'us_cform',
	'name' => __( 'Contact Form', 'us' ),
	'icon' => 'icon-wpb-ui-separator',
	'category' => __( 'Content', 'us' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Receiver Email', 'us' ),
			'param_name' => 'receiver_email',
			'value' => '',
			'description' => sprintf( __( 'Requests will be sent to this Email. You can insert multiple comma-separated emails as well.', 'us' ) ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Name field', 'us' ),
			'param_name' => 'name_field',
			'value' => array(
				__( 'Shown, required', 'us' ) => 'required',
				__( 'Shown, not required', 'us' ) => 'shown',
				__( 'Hidden', 'us' ) => 'hidden',
			),
			'std' => 'required',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Email field', 'us' ),
			'param_name' => 'email_field',
			'value' => array(
				__( 'Shown, required', 'us' ) => 'required',
				__( 'Shown, not required', 'us' ) => 'shown',
				__( 'Hidden', 'us' ) => 'hidden',
			),
			'std' => 'required',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Phone field', 'us' ),
			'param_name' => 'phone_field',
			'value' => array(
				__( 'Shown, required', 'us' ) => 'required',
				__( 'Shown, not required', 'us' ) => 'shown',
				__( 'Hidden', 'us' ) => 'hidden',
			),
			'std' => 'required',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Message field', 'us' ),
			'param_name' => 'message_field',
			'value' => array(
				__( 'Shown, required', 'us' ) => 'required',
				__( 'Shown, not required', 'us' ) => 'shown',
				__( 'Hidden', 'us' ) => 'hidden',
			),
			'std' => 'required',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Captcha field', 'us' ),
			'param_name' => 'captcha_field',
			'value' => array(
				__( 'Hidden', 'us' ) => 'hidden',
				__( 'Shown, required', 'us' ) => 'required',
			),
			'std' => 'hidden',
			'description' => '',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button Style', 'us' ),
			'param_name' => 'button_style',
			'value' => array(
				__( 'Raised', 'us' ) => 'raised',
				__( 'Flat', 'us' ) => 'flat',
			),
			'std' => 'raised',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button Color', 'us' ),
			'param_name' => 'button_color',
			'value' => array(
				__( 'Primary (theme color)', 'us' ) => 'primary',
				__( 'Secondary (theme color)', 'us' ) => 'secondary',
				__( 'Light (theme color)', 'us' ) => 'light',
				__( 'Contrast (theme color)', 'us' ) => 'contrast',
				__( 'Black', 'us' ) => 'black',
				__( 'White', 'us' ) => 'white',
				__( 'Custom colors', 'us' ) => 'custom',
			),
			'std' => 'primary',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'colorpicker',
			'class' => '',
			'heading' => __( 'Button Background Color', 'us' ),
			'param_name' => 'button_bg_color',
			'value' => '',
			'description' => '',
			'dependency' => array( 'element' => 'button_color', 'value' => 'custom' ),
		),
		array(
			'type' => 'colorpicker',
			'class' => '',
			'heading' => __( 'Button Text Color', 'us' ),
			'param_name' => 'button_text_color',
			'value' => '',
			'description' => '',
			'dependency' => array( 'element' => 'button_color', 'value' => 'custom' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button Size', 'us' ),
			'param_name' => 'button_size',
			'value' => array(
				__( 'Medium', 'us' ) => 'medium',
				__( 'Large', 'us' ) => 'large',
			),
			'std' => 'medium',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Button Alignment', 'us' ),
			'param_name' => 'button_align',
			'value' => array(
				__( 'Left', 'us' ) => 'left',
				__( 'Center', 'us' ) => 'center',
				__( 'Right', 'us' ) => 'right',
			),
			'std' => 'left',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'us' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'us' ),
		),
	),
) );

/**
 * Shortcode: us_social_links
 */
vc_map( array(
	'base' => 'us_social_links',
	'name' => __( 'Social Links', 'us' ),
	'icon' => 'icon-wpb-ui-separator',
	'category' => __( 'Content', 'us' ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Icons Size', 'us' ),
			'param_name' => 'size',
			'value' => array(
				__( 'Small', 'us' ) => 'small',
				__( 'Medium', 'us' ) => 'medium',
				__( 'Large', 'us' ) => 'large'
			),
			'std' => 'small',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Icons Alignment', 'us' ),
			'param_name' => 'align',
			'value' => array(
				__( 'Left', 'us' ) => 'left',
				__( 'Center', 'us' ) => 'center',
				__( 'Right', 'us' ) => 'right'
			),
			'std' => 'left',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'checkbox',
			'heading' => '',
			'param_name' => 'inverted',
			'value' => array( __( 'Invert colors for all the icons', 'us' ) => TRUE ),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'checkbox',
			'heading' => '',
			'param_name' => 'desaturated',
			'value' => array( __( 'Desaturate all the icons', 'us' ) => TRUE ),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Email', 'us' ),
			'param_name' => 'email',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => 'Facebook',
			'param_name' => 'facebook',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => 'Twitter',
			'param_name' => 'twitter',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => 'Google+',
			'param_name' => 'google',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => 'LinkedIn',
			'param_name' => 'linkedin',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => 'YouTube',
			'param_name' => 'youtube',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => 'Vimeo',
			'param_name' => 'vimeo',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => 'Flickr',
			'param_name' => 'flickr',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => 'Instagram',
			'param_name' => 'instagram',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => 'Behance',
			'param_name' => 'behance',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => 'Xing',
			'param_name' => 'xing',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => 'Pinterest',
			'param_name' => 'pinterest',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => 'Skype',
			'param_name' => 'skype',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => 'Tumblr',
			'param_name' => 'tumblr',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => 'Dribbble',
			'param_name' => 'dribbble',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => 'Vkontakte',
			'param_name' => 'vk',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => 'SoundCloud',
			'param_name' => 'soundcloud',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => 'Yelp',
			'param_name' => 'yelp',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => 'Twitch',
			'param_name' => 'twitch',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => 'DeviantArt',
			'param_name' => 'deviantart',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => 'Foursquare',
			'param_name' => 'foursquare',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => 'GitHub',
			'param_name' => 'github',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => 'Odnoklassniki',
			'param_name' => 'odnoklassniki',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => '500px',
			'param_name' => 's500px',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => 'Houzz',
			'param_name' => 'houzz',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => 'RSS',
			'param_name' => 'rss',
			'value' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'description' => '',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Custom Link', 'us' ),
			'param_name' => 'custom_link',
			'value' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'description' => '',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Custom Link Title', 'us' ),
			'param_name' => 'custom_title',
			'value' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'dependency' => array( 'element' => 'custom_link', 'not_empty' => TRUE ),
			'description' => '',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Custom Link Icon', 'us' ),
			'param_name' => 'custom_icon',
			'value' => '',
			'dependency' => array( 'element' => 'custom_link', 'not_empty' => TRUE ),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'description' => '',
		),
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Custom Link Color', 'us' ),
			'param_name' => 'custom_color',
			'value' => '#1abc9c',
			'dependency' => array( 'element' => 'custom_link', 'not_empty' => TRUE ),
			'description' => '',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'us' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'us' ),
		),
	),
) );

/**
 * Shortcode: us_gmaps
 */
vc_map( array(
	'base' => 'us_gmaps',
	'name' => __( 'Google Maps', 'us' ),
	'icon' => 'icon-wpb-map-pin',
	'category' => __( 'Content', 'us' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Address', 'us' ),
			'holder' => 'div',
			'param_name' => 'marker_address',
			'value' => '1600 Amphitheatre Parkway, Mountain View, CA 94043, United States',
			'description' => ''
		),
		array(
			'type' => 'textarea_raw_html',
			'heading' => __( 'Marker Text', 'us' ),
			'param_name' => 'marker_text',
			'value' => base64_encode( '<h6>Hey, we are here!</h6><p>We will be glad to see you in our office.</p>' ),
			'edit_field_class' => 'vc_col-sm-12 vc_column pretend_textfield',
			'description' => __( 'Enter your content, HTML tags are allowed.', 'us' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Map Height (pixels)', 'us' ),
			'param_name' => 'height',
			'value' => '400',
			'description' => ''
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Map Type', 'us' ),
			'param_name' => 'type',
			'value' => array(
				__( 'Roadmap', 'us' ) => 'roadmap',
				__( 'Roadmap + Terrain', 'us' ) => 'terrain',
				__( 'Satellite', 'us' ) => 'satellite',
				__( 'Satellite + Roadmap', 'us' ) => 'hybrid',
			),
			'std' => 'roadmap',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'description' => ''
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Map Zoom', 'us' ),
			'param_name' => 'zoom',
			'value' => array(
				' 1' => '1',
				' 2' => '2',
				' 3' => '3',
				' 4' => '4',
				' 5' => '5',
				' 6' => '6',
				' 7' => '7',
				' 8' => '8',
				' 9' => '9',
				' 10' => '10',
				' 11' => '11',
				' 12' => '12',
				' 13' => '13',
				' 14' => '14',
				' 15' => '15',
				' 16' => '16',
				' 17' => '17',
				' 18' => '18',
				' 19' => '19',
				' 20' => '20'
			),
			'std' => '14',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'description' => ''
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Map Latitude (optional)', 'us' ),
			'param_name' => 'latitude',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'description' => __( 'If Longitude and Latitude are set, they override the Address value.', 'us' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Map Longitude (optional)', 'us' ),
			'param_name' => 'longitude',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'description' => __( 'If Longitude and Latitude are set, they override the Address value.', 'us' ),
		),
		array(
			'type' => 'attach_image',
			'heading' => __( 'Custom Marker Image', 'us' ),
			'param_name' => 'custom_marker_img',
			'description' => __( 'Image should NOT be bigger then 80x80 px', 'us' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Custom Marker Size', 'us' ),
			'param_name' => 'custom_marker_size',
			'value' => array(
				'20x20' => '20',
				'30x30' => '30',
				'40x40' => '40',
				'50x50' => '50',
				'60x60' => '60',
				'70x70' => '70',
				'80x80' => '80',
			),
			'std' => '20',
			'dependency' => array( 'element' => 'custom_marker_img', 'not_empty' => TRUE ),
			'description' => ''
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Additional Markers', 'us' ),
			'param_name' => 'add_markers',
			'value' => array( __( 'Add more Markers to the map', 'us' ) => TRUE ),
			'description' => ''
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Marker 2 Address', 'us' ),
			'param_name' => 'marker2_address',
			'description' => '',
			'dependency' => array( 'element' => 'add_markers', 'not_empty' => TRUE ),
		),
		array(
			'type' => 'textarea_raw_html',
			'heading' => __( 'Marker 2 Text', 'us' ),
			'param_name' => 'marker2_text',
			'edit_field_class' => 'vc_col-sm-12 vc_column pretend_textfield',
			'description' => __( 'Enter your content, HTML tags are allowed.', 'us' ),
			'dependency' => array( 'element' => 'add_markers', 'not_empty' => TRUE ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Marker 3 Address', 'us' ),
			'param_name' => 'marker3_address',
			'description' => '',
			'dependency' => array( 'element' => 'add_markers', 'not_empty' => TRUE ),
		),
		array(
			'type' => 'textarea_raw_html',
			'heading' => __( 'Marker 3 Text', 'us' ),
			'param_name' => 'marker3_text',
			'edit_field_class' => 'vc_col-sm-12 vc_column pretend_textfield',
			'description' => __( 'Enter your content, HTML tags are allowed.', 'us' ),
			'dependency' => array( 'element' => 'add_markers', 'not_empty' => TRUE ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Marker 4 Address', 'us' ),
			'param_name' => 'marker4_address',
			'description' => '',
			'dependency' => array( 'element' => 'add_markers', 'not_empty' => TRUE ),
		),
		array(
			'type' => 'textarea_raw_html',
			'heading' => __( 'Marker 4 Text', 'us' ),
			'param_name' => 'marker4_text',
			'edit_field_class' => 'vc_col-sm-12 vc_column pretend_textfield',
			'description' => __( 'Enter your content, HTML tags are allowed.', 'us' ),
			'dependency' => array( 'element' => 'add_markers', 'not_empty' => TRUE ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Marker 5 Address', 'us' ),
			'param_name' => 'marker5_address',
			'description' => '',
			'dependency' => array( 'element' => 'add_markers', 'not_empty' => TRUE ),
		),
		array(
			'type' => 'textarea_raw_html',
			'heading' => __( 'Marker 5 Text', 'us' ),
			'param_name' => 'marker5_text',
			'edit_field_class' => 'vc_col-sm-12 vc_column pretend_textfield',
			'description' => __( 'Enter your content, HTML tags are allowed.', 'us' ),
			'dependency' => array( 'element' => 'add_markers', 'not_empty' => TRUE ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'us' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'us' ),
		),
	)
) );
vc_remove_element( 'vc_gmaps' );

/**
 * Shortcode: us_pricing
 */
vc_map( array(
	'base' => 'us_pricing',
	'name' => __( 'Pricing Table', 'us' ),
	'icon' => 'icon-wpb-pricing-table',
	'category' => __( 'Content', 'us' ),
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Table Style', 'us' ),
			'param_name' => 'style',
			'value' => array(
				__( 'Card Style', 'us' ) => '1',
				__( 'Flat Style', 'us' ) => '2',
			),
			'std' => '1',
			'description' => '',
		),
		array(
			'type' => 'param_group',
			'heading' => __( 'Pricing Items', 'js_composer' ),
			'param_name' => 'items',
			'value' => urlencode( json_encode( array(
				array(
					'title' => __( 'Free', 'us' ),
					'price' => '$0',
					'substring' => __( 'per month', 'us' ),
					'features' => "1 project\n1 user\n200 tasks\nNo support",
					'btn_text' => __( 'Sign up', 'us' ),
					'btn_color' => 'light',
				),
				array(
					'title' => __( 'Standard', 'us' ),
					'type' => 'featured',
					'price' => '$24',
					'substring' => __( 'per month', 'us' ),
					'features' => "10 projects\n10 users\nUnlimited tasks\nPremium support",
					'btn_text' => __( 'Sign up', 'us' ),
					'btn_color' => 'primary',
				),
				array(
					'title' => __( 'Premium', 'us' ),
					'price' => '$50',
					'substring' => __( 'per month', 'us' ),
					'features' => "Unlimited projects\nUnlimited users\nUnlimited tasks\nPremium support",
					'btn_text' => __( 'Sign up', 'us' ),
					'btn_color' => 'light',
				),
			) ) ),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __( 'Item Title', 'us' ),
					'param_name' => 'title',
					'value' => __( 'New Item', 'us' ),
					'admin_label' => TRUE
				),
				array(
					'type' => 'checkbox',
					'param_name' => 'type',
					'value' => array( __( 'Mark this item as featured', 'us' ) => 'featured' ),
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Price', 'us' ),
					'param_name' => 'price',
					'value' => '$99',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Price Substring', 'us' ),
					'param_name' => 'substring',
					'value' => __( 'per month', 'us' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textarea',
					'heading' => __( 'Features List', 'us' ),
					'param_name' => 'features',
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Button Label', 'us' ),
					'class' => 'wpb_button',
					'param_name' => 'btn_text',
					'value' => __( 'Sign up', 'us' ),
					'description' => '',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => __( 'Button Color', 'us' ),
					'param_name' => 'btn_color',
					'value' => array(
						__( 'Primary (theme color)', 'us' ) => 'primary',
						__( 'Secondary (theme color)', 'us' ) => 'secondary',
						__( 'Light (theme color)', 'us' ) => 'light',
						__( 'Contrast (theme color)', 'us' ) => 'contrast',
						__( 'Black', 'us' ) => 'black',
						__( 'White', 'us' ) => 'white',
						__( 'Custom colors', 'us' ) => 'custom',
					),
					'std' => 'primary',
					'description' => '',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'colorpicker',
					'class' => '',
					'heading' => __( 'Button Background Color', 'us' ),
					'param_name' => 'btn_bg_color',
					'value' => '',
					'description' => '',
					'dependency' => array( 'element' => 'btn_color', 'value' => 'custom' ),
				),
				array(
					'type' => 'colorpicker',
					'class' => '',
					'heading' => __( 'Button Text Color', 'us' ),
					'param_name' => 'btn_text_color',
					'value' => '',
					'description' => '',
					'dependency' => array( 'element' => 'btn_color', 'value' => 'custom' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => __( 'Button Style', 'us' ),
					'param_name' => 'btn_style',
					'value' => array(
						__( 'Raised', 'us' ) => 'raised',
						__( 'Flat', 'us' ) => 'flat',
					),
					'std' => 'raised',
					'description' => '',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => __( 'Button Size', 'us' ),
					'param_name' => 'btn_size',
					'value' => array(
						__( 'Medium', 'us' ) => 'medium',
						__( 'Large', 'us' ) => 'large',
					),
					'std' => 'medium',
					'description' => '',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Button Icon (optional)', 'us' ),
					'param_name' => 'btn_icon',
					'description' => sprintf( __( '<a href="%s" target="_blank">FontAwesome</a> or <a href="%s" target="_blank">Material Design</a> icon', 'us' ), $url_fontawesome, $url_mdfi ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => __( 'Icon Position', 'us' ),
					'param_name' => 'btn_iconpos',
					'value' => array(
						__( 'Left', 'us' ) => 'left',
						__( 'Right', 'us' ) => 'right',
					),
					'std' => 'left',
					'description' => '',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'vc_link',
					'heading' => __( 'Button Link', 'us' ),
					'param_name' => 'btn_link',
					'description' => '',
				),
			),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'us' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'us' ),
		),
	),

) );

// Removing the elements that are not supported at the moment
$us_not_supported_elements = array(
	'vc_facebook',
	'vc_tweetmeme',
	'vc_googleplus',
	'vc_pinterest',
	'vc_toggle',
	'vc_posts_slider',
	'vc_progress_bar',
	'vc_pie',
	'vc_basic_grid',
	'vc_media_grid',
	'vc_masonry_grid',
	'vc_masonry_media_grid',
);
if ( ! us_get_option( 'enable_unsupported_vc_shortcodes', FALSE ) ) {
	array_walk( $us_not_supported_elements, 'vc_remove_element' );
}

// Ordering elements for the "Add Element" dialog
$us_elements_weights = array(
	'vc_row' => 390,
	'vc_column_text' => 380,
	'us_single_image' => 370,
	'us_gallery' => 360,
	'us_image_slider' => 350,
	'us_separator' => 340,
	'us_btn' => 330,
	'us_iconbox' => 280,
	'us_testimonial' => 270,
	'us_person' => 260,
	'us_portfolio' => 250,
	'us_blog' => 240,
	'us_logos' => 230,
	'us_cta' => 220,
	'vc_video' => 210,
	'us_message' => 200,
	'us_counter' => 190,
	'us_cform' => 180,
	'us_social_links' => 170,
	'us_gmaps' => 160,
	'us_pricing' => 156,
	'vc_icon' => 153,
	'vc_raw_html' => 150,
	'vc_raw_js' => 140,
	'vc_widget_sidebar' => 130,
	'vc_flickr' => 120,
	'vc_empty_space' => 110,
	'vc_custom_heading' => 100,
);
if ( version_compare( WPB_VC_VERSION, '4.6', '>=' ) ) {
	$us_elements_weights['vc_tta_tabs'] = 327;
	$us_elements_weights['vc_tta_accordion'] = 325;
	$us_elements_weights['vc_tta_tour'] = 323;
}
foreach ( $us_elements_weights as $shortcode => $weight ) {
	vc_map_update( $shortcode, array(
		'weight' => $weight,
	) );
}

if ( ! vc_is_page_editable() ) {
	// Removing original VC styles and scripts
	// TODO move to a separate option
	add_action( 'wp_enqueue_scripts', 'us_remove_vc_base_css_js', 15 );
	function us_remove_vc_base_css_js() {
		global $us_template_directory_uri;
		if ( wp_style_is( 'font-awesome', 'registered' ) ) {
			wp_deregister_style( 'font-awesome' );
		}
		if ( ! us_get_option( 'enable_unsupported_vc_shortcodes', FALSE ) ) {
			if ( wp_style_is( 'js_composer_front', 'registered' ) ) {
				wp_deregister_style( 'js_composer_front' );
			}
			if ( wp_script_is( 'wpb_composer_front_js', 'registered' ) ) {
				wp_deregister_script( 'wpb_composer_front_js' );
			}
			wp_enqueue_style( 'us-style-vc-icon', $us_template_directory_uri . '/framework/css/site/vc_icon.css', array(), us_get_main_theme_version(), 'all' );
		}
	}
}

if ( vc_is_page_editable() ) {
	// Disabling some of the shortcodes for front-end edit mode
	US_Shortcodes::instance()->vc_front_end_compatibility();
}

if ( is_admin() AND ! us_get_option( 'enable_unsupported_vc_shortcodes', FALSE ) ) {
	// Removing grid elements
	add_action( 'admin_menu', 'us_remove_vc_grid_elements_submenu' );
	function us_remove_vc_grid_elements_submenu() {
		remove_submenu_page( VC_PAGE_MAIN_SLUG, 'edit.php?post_type=vc_grid_item' );
	}
}

/**
 * Get image size values for selector
 *
 * @param array $size_names List of size names
 *
 * @return array
 */
function us_image_sizes_select_values( $size_names ) {
	$image_sizes = array();
	// For translation purposes
	$size_titles = array(
		'large' => __( 'Large', 'us' ),
		'medium' => __( 'Medium', 'us' ),
		'thumbnail' => __( 'Thumbnail', 'us' ),
		'full' => __( 'Full Size', 'us' ),
	);
	foreach ( $size_names as $size_name ) {
		$size_title = isset( $size_titles[ $size_name ] ) ? $size_titles[ $size_name ] : ucwords( $size_name );
		if ( $size_name != 'full' ) {
			// Detecting size
			$size = us_get_intermediate_image_size( $size_name );
			$size_title .= ' - ' . ( ( $size['width'] == 0 ) ? __( 'Any', 'us' ) : $size['width'] );
			$size_title .= '&#215;';
			$size_title .= ( $size['height'] == 0 ) ? __( 'Any', 'us' ) : $size['height'];
			$size_title .= ' (' . ( $size['crop'] ? __( 'cropped', 'us' ) : __( 'not cropped', 'us' ) ) . ')';
		}
		$image_sizes[ $size_title ] = $size_name;
	}

	return $image_sizes;
}
