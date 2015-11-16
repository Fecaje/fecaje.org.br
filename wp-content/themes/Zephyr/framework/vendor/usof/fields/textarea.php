<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Theme Options Field: Textarea
 *
 * Simple textarea field.
 *
 * @var $field_id string Field ID
 * @var $field array Field options
 *
 * @param $field ['title'] string Field title
 * @param $field ['description'] string Field title
 * @param $field ['placeholder'] string Field placeholder
 *
 * @var $value string Current value
 */

$output = '<div class="usof-form-row-control">';
$output .= '<textarea name="' . $field_id . '"';
if ( isset( $field['placeholder'] ) AND ! empty( $field['placeholder'] ) ) {
	$output .= ' placeholder="' . esc_attr( $field['placeholder'] ) . '"';
}
$output .= '>' . esc_textarea( $value ) . '</textarea>';
$output .= '</div>';

echo $output;

