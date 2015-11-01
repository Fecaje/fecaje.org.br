<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Certain theme's migration profile
 */
add_filter( 'us_config_migrations', 'us_config_migrations' );
function us_config_migrations( $migrations ) {
	return array(
		'2.0' => 'functions/migrations/us_migration_2_0.php',
		'2.1' => 'functions/migrations/us_migration_2_1.php',
	);
}

require $us_template_directory . '/framework/functions/migration.php';
US_Migration::instance();
