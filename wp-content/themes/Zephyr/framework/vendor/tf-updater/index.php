<?php
/*
	Plugin Name: Themeforest Themes Update
	Plugin URI: https://github.com/bitfade/themeforest-themes-update
	Description: Updates all themes purchased from themeforest.net
	Author: pixelentity
	Version: 1.0
	Author URI: http://pixelentity.com
*/

if ( us_get_option( 'themeforest_username' ) AND us_get_option( 'themeforest_api_key' ) ) {
	function us_themeforest_themes_update( $updates ) {
		global $us_template_directory;
		if ( isset( $updates->checked ) ) {
			require_once( $us_template_directory . '/framework/vendor/tf-updater/pixelentity-themes-updater/class-pixelentity-themes-updater.php' );

			$username = us_get_option( 'themeforest_username' );
			$apikey = us_get_option( 'themeforest_api_key' );
			$author = 'UpSolution';

			$updater = new Pixelentity_Themes_Updater( $username, $apikey, $author );
			$updates = $updater->check( $updates );
		}

		return $updates;
	}

	add_filter( "pre_set_site_transient_update_themes", "us_themeforest_themes_update" );
}
