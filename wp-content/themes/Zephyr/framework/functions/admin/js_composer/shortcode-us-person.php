<?php
/**
 * Shortcode: us_person
 */
vc_map( array(
	'base' => 'us_person',
	'name' => __( 'Person', 'us' ),
	'icon' => 'icon-wpb-ui-separator-label',
	'category' => __( 'Content', 'us' ),
	'params' => array(
		array(
			'type' => 'attach_image',
			'heading' => __( 'Photo', 'us' ),
			'param_name' => 'image',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Layout Style', 'us' ),
			'param_name' => 'style',
			'value' => array(
				__( 'Card Style', 'us' ) => '1',
				__( 'Flat Style', 'us' ) => '2',
			),
			'std' => '1',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Name', 'us' ),
			'param_name' => 'name',
			'value' => __( 'Jon Snow', 'us' ),
			'holder' => 'div',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column newline',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Role', 'us' ),
			'param_name' => 'role',
			'value' => __( 'Lord Commander', 'us' ),
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'vc_link',
			'heading' => __( 'Link', 'us' ),
			'param_name' => 'link',
			'value' => '',
			'description' => __( 'Applies to the Name and to the Photo', 'us' ),
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
			'param_name' => 'google_plus',
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
			'heading' => 'Skype',
			'param_name' => 'skype',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Custom Icon', 'us' ),
			'param_name' => 'custom_icon',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Custom Icon Link', 'us' ),
			'param_name' => 'custom_link',
			'value' => '',
			'description' => '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'type' => 'textarea',
			'holder' => 'div',
			'heading' => __( 'Description (optional)', 'us' ),
			'param_name' => 'content',
			'value' => '',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'us' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'us' ),
		),
	),
) );

class WPBakeryShortCode_us_person extends WPBakeryShortCode {

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
				$logo_html .= '<img width="150" height="150" class="attachment-thumbnail ' . $element_icon . ' vc_element-icon"  data-name="' . $param_name . '" alt="" title="" style="display: none;" />';
			}
			$logo_html .= '<span class="no_image_image vc_element-icon ' . $element_icon . ( $img && ! empty( $img['p_img_large'][0] ) ? ' image-exists' : '' ) . '" />';
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
