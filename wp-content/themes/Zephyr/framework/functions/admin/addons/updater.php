<?php defined('ABSPATH') OR die('This script cannot be accessed directly.');
/**
 * Auto-updater for the plugins bundled with the theme and available at TGM_Plugin_Activation class
 *
 * @version   1.0.0
 * @author    UpSolution <us-themes.com>
 */

if ( ! class_exists( 'US_Plugin_Updater' ) ) {

	class US_Plugin_Updater {

		public function __construct() {

			if ( ! class_exists( 'TGM_Plugin_Activation' ) OR ! isset( TGM_Plugin_Activation::$instance ) ) {
				return;
			}

			// Take over the update check
			add_action( 'site_transient_update_plugins', array( $this, 'site_transient_update_plugins' ) );

			// Seen when user clicks "view details" on the plugin listing page
			add_action( 'install_plugins_pre_plugin-information', array( $this, 'plugin_update_popup' ) );
		}

		/**
		 * Transient hook for automatical updates of bundled plugins
		 *
		 * @param $trans
		 *
		 * @return mixed
		 */
		public function site_transient_update_plugins( $trans ) {

			$installed_plugins = get_plugins();

			foreach ( TGM_Plugin_Activation::$instance->plugins as $plugin ) {
				$plugin_basename = sprintf( '%s/%s.php', $plugin['slug'], $plugin['slug'] );

				if ( ! isset( $installed_plugins[ $plugin_basename ] ) ) {
					continue;
				}

				if ( version_compare( $installed_plugins[ $plugin_basename ]['Version'], $plugin['version'], '<' ) ) {
					$trans->response[ $plugin_basename ] = new StdClass();
					$trans->response[ $plugin_basename ]->plugin = $plugin_basename;
					$trans->response[ $plugin_basename ]->url = $plugin['changelog_url'];
					$trans->response[ $plugin_basename ]->slug = $plugin['slug'];
					$trans->response[ $plugin_basename ]->package = $plugin['source'];
					$trans->response[ $plugin_basename ]->new_version = $plugin['version'];
					$trans->response[ $plugin_basename ]->id = '0';
				}
			}

			return $trans;
		}

		/**
		 * Seen when user clicks "view changelog" on the plugins page
		 */
		public function plugin_update_popup() {

			if ( ! isset( $_GET['plugin'] ) ) {
				return;
			}

			$plugin_slug = sanitize_file_name( $_GET['plugin'] );

			foreach ( TGM_Plugin_Activation::$instance->plugins as $plugin ) {
				if ( $plugin['slug'] == $plugin_slug ) {
					$changelog_url = $plugin['changelog_url'];

					echo '<html><body style="height: 90%; background: #fcfcfc"><p>See the <a href="' . $changelog_url . '" ' . 'target="_blank">' . $changelog_url . '</a> for the detailed changelog</p></body></html>';

					exit;
				}
			}
		}
	}

	$us_plugin_updater = new US_Plugin_Updater();
}
