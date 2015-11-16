<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

add_action( 'tgmpa_register', 'us_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function us_register_required_plugins() {
	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = us_config( 'addons' );

	/*
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	// TODO Move to a separate theme config
	$config = array(
		// Text domain - likely want to be the same as your theme.
		'domain' => 'us',
		// Unique ID for hashing notices for multiple instances of TGMPA.
		'id' => 'tgmpa',
		// Default absolute path to pre-packaged plugins.
		'default_path' => '',
		// Menu slug.
		'menu' => 'us-addons',
		// Parent menu slug.
		'parent_slug' => 'themes.php',
		// Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'capability' => 'edit_theme_options',
		// Show admin notices or not.
		'has_notices' => TRUE,
		// If false, a user cannot dismiss the nag message.
		'dismissable' => TRUE,
		// Automatically activate plugins after installation or not.
		'is_automatic' => TRUE,
		// Message to output right before the plugins table.
		'message' => '',
		'strings' => array(
			'page_title' => __( 'Install Required Plugins', 'us' ),
			'menu_title' => __( 'Install Plugins', 'us' ),
			'installing' => __( 'Installing Plugin: %s', 'us' ),
			'oops' => __( 'Something went wrong with the plugin API.', 'us' ),
			'notice_can_install_required' => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'us' ),
			'notice_can_install_recommended' => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'us' ),
			'notice_cannot_install' => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'us' ),
			'notice_can_activate_required' => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'us' ),
			'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'us' ),
			'notice_cannot_activate' => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'us' ),
			'notice_ask_to_update' => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'us' ),
			'notice_cannot_update' => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'us' ),
			'install_link' => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'us' ),
			'activate_link' => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'us' ),
			'return' => __( 'Return to Required Plugins Installer', 'us' ),
			'dashboard' => __( 'Return to the dashboard', 'us' ),
			'plugin_activated' => __( 'Plugin activated successfully.', 'us' ),
			'activated_successfully' => __( 'The following plugin was activated successfully:', 'us' ),
			'complete' => __( 'All plugins installed and activated successfully. %1$s', 'us' ),
			'dismiss' => __( 'Dismiss this notice', 'us' ),
			// Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
			'nag_type' => 'updated',
		)
	);

	tgmpa( $plugins, $config );
}

// Moving all the us-* submenu elements to the theme's menu
add_action( 'admin_menu', 'us_admin_menu_addons_position', 12 );
function us_admin_menu_addons_position() {
	global $submenu;
	if ( ! isset( $submenu['themes.php'] ) OR ! isset( $submenu['us-home'] ) ) {
		return;
	}
	foreach ( $submenu['themes.php'] as $priority => $elm ) {
		if ( $elm[2] == 'us-addons' ) {
			remove_submenu_page( 'themes.php', $elm[2] );
			add_submenu_page(
				'us-home',
				__('Install Plugins', 'us'),
				__('Install Plugins', 'us'),
				'edit_theme_options',
				'us-addons',
				array( TGM_Plugin_Activation::$instance, 'install_plugins_page' )
			);
		}
	}
}
