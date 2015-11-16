<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Theme's migrations
 *
 * @filter us_config_migrations
 */

return array(
	'2.0' => 'functions/migrations/us_migration_2_0.php',
	'2.1' => 'functions/migrations/us_migration_2_1.php',
	'2.2' => 'functions/migrations/us_migration_2_2.php',
	'2.4' => 'functions/migrations/us_migration_2_4.php',
);
