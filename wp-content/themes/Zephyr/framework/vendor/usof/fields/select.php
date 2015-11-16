<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Theme Options Field: Select
 *
 * Drop-down selector field.
 *
 * @var $field_id string Field ID
 * @var $field array Field options
 *
 * @param $field ['title'] string Field title
 * @param $field ['description'] string Field title
 * @param $field ['options'] array List of value => title pairs
 *
 * @var $value string Current value
 */

$output = '<div class="usof-form-row-control"><div class="usof-select">';
$output .= '<select name="' . $field_id . '">';
foreach ( $field['options'] as $key => $option_title ) {
	$output .= '<option value="' . esc_attr( $key ) . '"' . selected( $value, $key, FALSE ) . '>' . $option_title . '</option>';
}
$output .= '</select>';
$output .= '</div></div>';

echo $output;

