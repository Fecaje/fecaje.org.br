<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Auto-updater for the plugins bundled with the theme
 */

$addons = us_config( 'addons', array() );
if ( empty( $addons ) ) {
	return;
}

// Transient hook for automatical updates of bundled plugins
add_action( 'site_transient_update_plugins', 'us_addons_transient_update' );
function us_addons_transient_update( $trans ) {

	$installed_plugins = get_plugins();

	foreach ( us_config( 'addons', array() ) as $addon ) {
		$plugin_basename = sprintf( '%s/%s.php', $addon['slug'], $addon['slug'] );

		if ( ! isset( $installed_plugins[ $plugin_basename ] ) ) {
			continue;
		}

		if ( version_compare( $installed_plugins[ $plugin_basename ]['Version'], $addon['version'], '<' ) ) {
			$trans->response[ $plugin_basename ] = new StdClass();
			$trans->response[ $plugin_basename ]->plugin = $plugin_basename;
			$trans->response[ $plugin_basename ]->url = $addon['changelog_url'];
			$trans->response[ $plugin_basename ]->slug = $addon['slug'];
			$trans->response[ $plugin_basename ]->package = $addon['source'];
			$trans->response[ $plugin_basename ]->new_version = $addon['version'];
			$trans->response[ $plugin_basename ]->id = '0';
		}
	}

	return $trans;
}

// Seen when user clicks "view details" on the plugin listing page
add_action( 'install_plugins_pre_plugin-information', 'us_addons_update_popup' );
function us_addons_update_popup() {

	if ( ! isset( $_GET['plugin'] ) ) {
		return;
	}

	$plugin_slug = sanitize_file_name( $_GET['plugin'] );

	foreach ( us_config( 'addons', array() ) as $addon ) {
		if ( $addon['slug'] == $plugin_slug ) {
			$changelog_url = $addon['changelog_url'];

			echo '<html><body style="height: 90%; background: #fcfcfc"><p>See the <a href="' . $changelog_url . '" ' . 'target="_blank">' . $changelog_url . '</a> for the detailed changelog</p></body></html>';

			exit;
		}
	}
}

add_action( 'admin_head', 'us_remove_bsf_update_counter' );
function us_remove_bsf_update_counter() {
	remove_action( 'admin_head', 'bsf_update_counter', 999 );
}
