<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Theme Options Field: Transfer
 *
 * Transfer theme options data
 *
 * @var $field_id string Field ID
 * @var $field array Field options
 *
 * @param $field ['title'] string Field title
 * @param $field ['description'] string Field title
 *
 * @var $value string Current value
 */

$output = '<div class="usof-form-row-control"><div class="usof-transfer">';
$output .= '<textarea></textarea>';
$output .= '<div class="usof-button type_import"><span>' . __( 'Import and Save Options', 'us' ) . '</span></div>';
$translations = array(
	'importError' => __( 'Wrong import data format', 'us' ),
	'importSuccess' => __( 'Theme Options imported successfully. To apply the new values, save the changes', 'us' ),
);
$output .= '<div class="usof-transfer-translations"' . us_pass_data_to_js( $translations ) . '></div>';
$output .= '</div></div>';

echo $output;
