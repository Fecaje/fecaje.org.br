<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Theme's thumbnails image sizes
 *
 * @filter us_config_image-sizes
 */

return array(

	// Size 1: 350x350 - small blog and gallery medium thumb
	'tnail-1x1-small' => array(
		'width' => 350,
		'height' => 350,
		'crop' => TRUE,
	),

	// Size 2: 600x400 - regular blog and carousel thumb
	'tnail-3x2' => array(
		'width' => 600,
		'height' => 400,
		'crop' => TRUE,
	),

	// Size 3: 600x600 - portfolio, gallery large and person thumb
	'tnail-1x1' => array(
		'width' => 600,
		'height' => 600,
		'crop' => TRUE,
	),

	// Size 4: 600xAny - masonry blog and masonry gallery thumb
	'tnail-masonry' => array(
		'width' => 600,
		'height' => 0,
		'crop' => FALSE,
	),

);
