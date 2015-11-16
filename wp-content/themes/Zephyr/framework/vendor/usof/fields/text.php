<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Theme Options Field: Text
 *
 * Simple text field.
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
$output .= '<input type="text" name="' . $field_id . '" value="' . esc_attr( $value ) . '"';
if ( isset( $field['placeholder'] ) AND ! empty( $field['placeholder'] ) ) {
	$output .= ' placeholder="' . esc_attr( $field['placeholder'] ) . '"';
}
$output .= ' />';
$output .= '</div>';

echo $output;
