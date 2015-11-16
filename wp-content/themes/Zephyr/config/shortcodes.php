<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcodes config
 *
 * @var $config array Framework-based shortcodes config
 *
 * @filter us_config_shortcodes
 */

global $us_template_directory;

$config['us_social_links']['custom_vc_map'] = $us_template_directory . '/plugins-support/js_composer/map/us_social_links.php';

$config['us_logos']['custom_vc_map'] = $us_template_directory . '/plugins-support/js_composer/map/us_logos.php';

unset( $config['us_contacts'] );

return $config;
