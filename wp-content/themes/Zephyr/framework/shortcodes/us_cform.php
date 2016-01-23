<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_feedback
 *
 * @var $shortcode {String} Current shortcode name
 * @var $shortcode_base {String} The original called shortcode name (differs if called an alias)
 * @var $atts {Array} Shortcode attributes
 * @var $content {String} Shortcode's inner content
 */

$atts = shortcode_atts( array(
	/**
	 * @var string Receiver Email
	 */
	'receiver_email' => '',
	/**
	 * @var string Name field state: 'required' / 'shown' / 'hidden'
	 */
	'name_field' => 'required',
	/**
	 * @var string Email field state: 'required' / 'shown' / 'hidden'
	 */
	'email_field' => 'required',
	/**
	 * @var string Phone field state: 'required' / 'shown' / 'hidden'
	 */
	'phone_field' => 'required',
	/**
	 * @var string Message field state: 'required' / 'shown' / 'hidden'
	 */
	'message_field' => 'required',
	/**
	 * @var string Message field state: 'hidden' / 'required'
	 */
	'captcha_field' => 'hidden',
	/**
	 * @var string Button color: 'primary' / 'secondary' / 'light' / 'contrast' / 'black' / 'white'
	 */
	'button_color' => 'primary',
	/**
	 * @var string Button background color
	 */
	'button_bg_color' => '',
	/**
	 * @var string Button text color
	 */
	'button_text_color' => '',
	/**
	 * @var string Button style: 'raised' / 'flat'
	 */
	'button_style' => 'raised',
	/**
	 * @var string Button size: 'medium' / 'large'
	 */
	'button_size' => 'medium',
	/**
	 * @var string Button alignment: 'left' / 'center' / 'right'
	 */
	'button_align' => 'left',
	/**
	 * @var string Extra class name
	 */
	'el_class' => '',
), $atts );

global $us_form_index;
// Form indexes start from 1
$us_form_index = isset( $us_form_index ) ? ( $us_form_index + 1 ) : 1;

$classes = '';
$btn_wrapper_classes = '';
$btn_classes = '';
$btn_inner_css = '';

if ( ! empty( $atts['button_color'] ) ) {
	$btn_classes .= ' color_' . $atts['button_color'];
	if ( $atts['button_color'] == 'custom' ) {
		if ( $atts['button_bg_color'] != '' ) {
			$btn_inner_css .= 'background-color: ' . $atts['button_bg_color'] . ';';
		}
		if ( $atts['button_text_color'] != '' ) {
			$btn_inner_css .= 'color: ' . $atts['button_text_color'] . ';';
		}
	}
}
if ( ! empty( $atts['button_style'] ) ) {
	$btn_classes .= ' style_' . $atts['button_style'];
}
$btn_classes .= ' size_' . $atts['button_size'];
$btn_wrapper_classes .= ' align_' . $atts['button_align'];

$btn_text = us_get_option( 'cform_button_text', us_config( 'cform.submit' ) );

$post_id = get_the_ID();

if ( ! empty( $atts['el_class'] ) ) {
	$classes = ' ' . $atts['el_class'];
}

$fields = us_config( 'cform.fields' );
foreach ( $fields as $field_name => $field ) {
	if ( ! isset( $atts[ $field_name . '_field' ] ) OR $atts[ $field_name . '_field' ] == 'hidden' ) {
		unset( $fields[ $field_name ] );
		continue;
	}
	$fields[ $field_name ]['required'] = ( $atts[ $field_name . '_field' ] == 'required' );
	if ( $field['type'] == 'captcha' ) {
		$numbers = array( rand( 16, 30 ), rand( 1, 15 ) );
		$sign = rand( 0, 1 );
		$fields[ $field_name ]['title'] .= ' ' . implode( $sign ? ' + ' : ' - ', $numbers );
		$fields[ $field_name ]['hash'] = md5( ( $numbers[0] + ( $sign ? 1 : - 1 ) * $numbers[1] ) . NONCE_SALT );
	} elseif ( $fields[ $field_name ]['required'] ) {
		$fields[ $field_name ]['title'] .= ' *';
	}
}

if ( ! empty( $btn_inner_css ) ) {
	$btn_inner_css = ' style="' . $btn_inner_css . '"';
}

?>
<div class="w-form for_cform<?php echo $classes ?>" id="us_form_<?php echo $us_form_index ?>">
	<form autocomplete="off" action="" method="post">
		<input type="hidden" name="action" value="us_ajax_cform">
		<input type="hidden" name="post_id" value="<?php echo $post_id ?>">
		<input type="hidden" name="form_index" value="<?php echo $us_form_index ?>">
<?php foreach ( $fields as $field_name => $field ): ?>
		<div class="w-form-row for_<?php echo $field_name ?>">
			<div class="w-form-field">
<?php if ( $field['type'] == 'text' OR $field['type'] == 'email' ): ?>
				<input type="<?php echo $field['type'] ?>" name="<?php echo $field_name ?>" data-required="<?php echo (int) $field['required'] ?>">
<?php elseif ( $field['type'] == 'textarea' ): ?>
				<textarea name="<?php echo $field_name ?>" cols="30" rows="10" data-required="<?php echo (int) $field['required'] ?>"></textarea>
<?php elseif ( $field['type'] == 'captcha' ): ?>
				<input type="text" name="<?php echo $field_name ?>" data-required="1">
				<input type="hidden" name="<?php echo $field_name ?>_hash" value="<?php echo $field['hash'] ?>">
<?php endif; ?>
				<i class="<?php echo us_prepare_icon_class( $field['icon'] ) ?>"></i>
				<span class="w-form-field-label"><?php echo $field['title'] ?></span>
				<span class="w-form-field-bar"></span>
			</div>
			<div class="w-form-state" id="us_form_<?php echo $us_form_index ?>_<?php echo $field_name ?>_state"></div>
		</div>
<?php endforeach; ?>

		<div class="w-form-row for_submit">
			<div class="w-form-field">
				<div class="w-btn-wrapper<?php echo $btn_wrapper_classes ?>">
					<button class="w-btn<?php echo $btn_classes ?>"<?php echo $btn_inner_css ?>>
						<div class="g-preloader style_2"></div>
						<span class="w-btn-label"><?php echo $btn_text ?></span>
					</button>
				</div>
				<div class="w-form-field-success"></div>
				<div class="w-form-field-error"></div>
			</div>
		</div>
	</form>
	<?php
	$json_data = array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'success' => us_config( 'cform.success' ),
		'errors' => array(),
	);
	foreach ( $fields as $field_name => $field ) {
		$json_data['errors'][$field_name] = $field['error'];
	}
	?>
	<div class="w-form-json hidden"<?php echo us_pass_data_to_js( $json_data ) ?>></div>
</div>

<?php

