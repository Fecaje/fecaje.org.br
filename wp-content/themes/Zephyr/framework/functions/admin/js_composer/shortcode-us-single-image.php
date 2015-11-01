<?php
/**
 * Shortcode: us_single_image
 */
vc_map( array(
	'base' => 'us_single_image',
	'name' => __( 'Single Image', 'us' ),
	'icon' => 'icon-wpb-single-image',
	'category' => __( 'Content', 'us' ),
	'description' => __( 'Simple image with CSS animation', 'us' ),
	'params' => array(
		array(
			'type' => 'attach_image',
			'heading' => __( 'Image', 'us' ),
			'param_name' => 'image',
			'value' => '',
			'description' => __( 'Select image from media library.', 'us' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Image Size', 'us' ),
			'param_name' => 'size',
			'value' => us_image_sizes_select_values( array( 'large', 'medium', 'thumbnail', 'full' ) ),
			'std' => 'large',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Image Alignment', 'us' ),
			'param_name' => 'align',
			'value' => array(
				__( 'Default', 'us' ) => '',
				__( 'Left', 'us' ) => 'left',
				__( 'Center', 'us' ) => 'center',
				__( 'Right', 'us' ) => 'right',
			),
			'std' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'checkbox',
			'heading' => '',
			'param_name' => 'lightbox',
			'description' => '',
			'value' => array( __( 'Enable lightbox with the original image on click', 'us' ) => TRUE ),
		),
		array(
			'type' => 'vc_link',
			'heading' => __( 'Image link', 'us' ),
			'param_name' => 'link',
			'description' => __( 'Set URL if you want this image to have a link.', 'us' ),
			'dependency' => array(
				'element' => 'lightbox',
				'is_empty' => TRUE,
			),
		),
		$us_css_animation,
		$us_css_animation_delay,
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
	),
) );
vc_remove_element( 'vc_single_image' );

class WPBakeryShortCode_us_single_image extends WPBakeryShortCode {

	public function singleParamHtmlHolder( $param, $value ) {
		$output = '';
		// Compatibility fixes
		$param_name = isset( $param['param_name'] ) ? $param['param_name'] : '';
		$type = isset( $param['type'] ) ? $param['type'] : '';
		$class = isset( $param['class'] ) ? $param['class'] : '';

		if ( $type == 'attach_image' AND $param_name == 'image' ) {
			$output .= '<input type="hidden" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . $value . '" />';
			$element_icon = $this->settings( 'icon' );
			$img = wpb_getImageBySize( array(
				'attach_id' => (int) preg_replace( '/[^\d]/', '', $value ),
				'thumb_size' => 'thumbnail',
			) );
			$logo_html = '';

			if ( $img ) {
				$logo_html .= $img['thumbnail'];
			} else {
				$logo_html .= '<img width="150" height="150" class="attachment-thumbnail icon-wpb-single-image vc_element-icon"  data-name="' . $param_name . '" alt="" title="" style="display: none;" />';
			}
			$logo_html .= '<span class="no_image_image vc_element-icon' . ( ! empty( $element_icon ) ? ' ' . $element_icon : '' ) . ( $img && ! empty( $img['p_img_large'][0] ) ? ' image-exists' : '' ) . '" />';
			$this->setSettings( 'logo', $logo_html );
			$output .= $this->outputTitleTrue( $this->settings['name'] );
		} elseif ( ! empty( $param['holder'] ) ) {
			if ( $param['holder'] == 'input' ) {
				$output .= '<' . $param['holder'] . ' readonly="true" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . $value . '">';
			} elseif ( in_array( $param['holder'], array( 'img', 'iframe' ) ) ) {
				$output .= '<' . $param['holder'] . ' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" src="' . $value . '">';
			} elseif ( $param['holder'] !== 'hidden' ) {
				$output .= '<' . $param['holder'] . ' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '">' . $value . '</' . $param['holder'] . '>';
			}
		}

		if ( ! empty( $param['admin_label'] ) && $param['admin_label'] === TRUE ) {
			$output .= '<span class="vc_admin_label admin_label_' . $param['param_name'] . ( empty( $value ) ? ' hidden-label' : '' ) . '"><label>' . __( $param['heading'], 'js_composer' ) . '</label>: ' . $value . '</span>';
		}

		return $output;
	}

	public function getImageSquereSize( $img_id, $img_size ) {
		if ( preg_match_all( '/(\d+)x(\d+)/', $img_size, $sizes ) ) {
			$exact_size = array(
				'width' => isset( $sizes[1][0] ) ? $sizes[1][0] : '0',
				'height' => isset( $sizes[2][0] ) ? $sizes[2][0] : '0',
			);
		} else {
			$image_downsize = image_downsize( $img_id, $img_size );
			$exact_size = array(
				'width' => $image_downsize[1],
				'height' => $image_downsize[2],
			);
		}
		if ( isset( $exact_size['width'] ) && (int) $exact_size['width'] !== (int) $exact_size['height'] ) {
			$img_size = (int) $exact_size['width'] > (int) $exact_size['height'] ? $exact_size['height'] . 'x' . $exact_size['height'] : $exact_size['width'] . 'x' . $exact_size['width'];
		}

		return $img_size;
	}

	protected function outputTitle( $title ) {
		return '';
	}

	protected function outputTitleTrue( $title ) {
		return '<h4 class="wpb_element_title">' . __( $title, 'us' ) . ' ' . $this->settings( 'logo' ) . '</h4>';
	}
}
