<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Theme Options Field: Images
 *
 * Radiobutton-like toggler of images
 *
 * @var $field_id string Field ID
 * @var $field array Field options
 *
 * @param $field ['title'] string Field title
 * @param $field ['description'] string Field title
 * @param $field ['options'] array Associative array of value => image
 *
 * @var $value string Current value
 */

global $us_template_directory_uri;

$output = '<div class="usof-form-row-control"><ul class="usof-imgradio">';
foreach ( $field['options'] as $key => $img_url ) {
	$output .= '<li class="usof-imgradio-item">';
	$output .= '<input type="radio" id="usof_' . $field_id . '_' . $key . '" name="' . $field_id . '"' . checked( $value, $key, FALSE ) . ' value="' . esc_attr( $key ) . '">';
	$output .= '<label for="usof_' . $field_id . '_' . $key . '"><img src="' . $us_template_directory_uri . '/' . $img_url . '" alt=""></label>';
	$output .= '</li>';
}
$output .= '</ul></div>';

echo $output;
