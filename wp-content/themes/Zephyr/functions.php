<?php
/**
 * Include all the needed files
 *
 * (!) Note for Clients: please, do not modify this or other theme's file. Use child theme instead!
 */

$us_template_directory = get_template_directory();
$us_stylesheet_directory = get_stylesheet_directory();
// Removing protocols for better compatibility with caching plugins and services
$us_template_directory_uri = str_replace( array( 'http:', 'https:' ), '', get_template_directory_uri() );
$us_stylesheet_directory_uri = str_replace( array( 'http:', 'https:' ), '', get_stylesheet_directory_uri() );

// Overloading some of the theme's configs files paths
add_filter( 'us_configs_paths', 'us_configs_paths' );
function us_configs_paths( $paths ) {
	global $us_template_directory;
	$paths['style-schemes'] = $us_template_directory . '/config/style-schemes.php';

	return $paths;
}

// Upsolution helper functions
require $us_template_directory . '/framework/functions/helpers.php';

// Slightly Modified Options Framework
require $us_template_directory . '/admin/index.php';

// Performing fallback compatibility and migrations when needed
require $us_template_directory . '/functions/migrations/init.php';

// Load shortcodes
require $us_template_directory . '/framework/functions/shortcodes.php';

// UpSolution Layout definitions
require $us_template_directory . '/framework/functions/layout.php';

// Breadcrumbs function
require $us_template_directory . '/framework/functions/breadcrumbs.php';

// Post formats
require $us_template_directory . '/framework/functions/post.php';

// Custom Post types
require $us_template_directory . '/framework/functions/post-types.php';

// Meta Box plugin and settings
// TODO Rewrite this class to use only the light-weight version at front-end
define( 'RWMB_URL', trailingslashit( $us_template_directory_uri . '/vendor/meta-box' ) );
define( 'RWMB_DIR', trailingslashit( $us_template_directory . '/vendor/meta-box' ) );
require RWMB_DIR . 'meta-box.php';
// Hook to 'admin_init' to make sure the meta box class is loaded before
add_action( 'admin_init', 'us_register_meta_boxes' );
function us_register_meta_boxes() {
	global $us_template_directory, $meta_boxes;
	require $us_template_directory . '/functions/meta-box_settings.php';
	// Make sure there's no errors when the plugin is deactivated or during upgrade
	if ( class_exists( 'RW_Meta_Box' ) ) {
		foreach ( $meta_boxes as $meta_box ) {
			new RW_Meta_Box( $meta_box );
		}
	}
}

// Menu and it's custom markup
require $us_template_directory . '/functions/menu.php';
// Comments custom markup
require $us_template_directory . '/functions/comments.php';
// wp_link_pages both next and numbers usage
require $us_template_directory . '/functions/link_pages.php';

// Sidebars init
require $us_template_directory . '/framework/functions/sidebars.php';
// TODO Rewrite sidebar generator and move it to a separate addon
require $us_template_directory . '/framework/vendor/sidebar_generator.php';

// Plugins activation
if ( is_admin() ) {
	// Admin specific functions
	require $us_template_directory . '/framework/functions/admin/functions.php';
	require $us_template_directory . '/framework/functions/admin/addons/register.php';
	require $us_template_directory . '/framework/vendor/class-tgm-plugin-activation.php';
	require $us_template_directory . '/framework/functions/admin/addons/updater.php';
}
// CSS and JS enqueue
require $us_template_directory . '/framework/functions/enqueue.php';

// Widgets
require $us_template_directory . '/functions/widgets/socials.php';
require $us_template_directory . '/functions/widgets/login.php';
add_filter( 'widget_text', 'do_shortcode' );

if ( is_admin() ) {
	// Theme Dashboard page
	require $us_template_directory . '/framework/functions/admin/dashboard.php';
	// Product Validation
//	require $template_directory . '/framework/functions/admin/product-validation.php';
	// Demo Import
	require $us_template_directory . '/framework/functions/admin/demo-import.php';
	// Auto Updater
	require $us_template_directory . '/framework/vendor/tf-updater/index.php';
}

if ( defined( 'DOING_AJAX' ) AND DOING_AJAX ) {
	require $us_template_directory . '/framework/functions/ajax/blog.php';
	require $us_template_directory . '/framework/functions/ajax/portfolio.php';
	require $us_template_directory . '/framework/functions/ajax/cform.php';
}

// Visual Composer Config (should be included before US_Shortcodes initialization)
if ( class_exists( 'Vc_Manager' ) ) {
	add_action( 'vc_before_init', 'us_vc_set_as_theme' );
	function us_vc_set_as_theme() {
		vc_set_as_theme( TRUE );
	}

	add_action( 'vc_after_set_mode', 'us_vc_after_set_mode' );
	function us_vc_after_set_mode() {
		global $us_template_directory;
		require $us_template_directory . '/framework/functions/admin/js_composer/map.php';
	}

	// Disabling redirect to VC welcome page
	remove_action( 'init', 'vc_page_welcome_redirect' );
}

// WooCommerce
require $us_template_directory . '/functions/woocommerce.php';

// Ultimate Addons for VC fixes
if ( class_exists( 'Ultimate_VC_Addons' ) ) {
	defined( 'ULTIMATE_USE_BUILTIN' ) OR define( 'ULTIMATE_USE_BUILTIN', TRUE );
	defined( 'ULTIMATE_NO_EDIT_PAGE_NOTICE' ) OR define( 'ULTIMATE_NO_EDIT_PAGE_NOTICE', TRUE );
	defined( 'ULTIMATE_NO_PLUGIN_PAGE_NOTICE' ) OR define( 'ULTIMATE_NO_PLUGIN_PAGE_NOTICE', TRUE );
	// Removing potentially dangerous functions
	if ( ! function_exists( 'bsf_grant_developer_access' ) ) {
		function bsf_grant_developer_access() {
		}
	}
	if ( ! function_exists( 'bsf_allow_developer_access' ) ) {
		function bsf_allow_developer_access() {
		}
	}
	if ( ! function_exists( 'bsf_process_developer_login' ) ) {
		function bsf_process_developer_login() {
		}
	}
	if ( ! function_exists( 'bsf_notices' ) ) {
		function bsf_notices() {
		}
	}
	add_action( 'init', 'us_sanitize_ultimate_addons', 20 );
	function us_sanitize_ultimate_addons() {
		remove_action( 'admin_init', 'bsf_update_all_product_version', 1000 );
		remove_action( 'admin_notices', 'bsf_notices', 1000 );
		remove_action( 'network_admin_notices', 'bsf_notices', 1000 );
		remove_action( 'admin_footer', 'bsf_update_counter', 999 );
		remove_action( 'wp_ajax_bsf_update_client_license', 'bsf_server_update_client_license' );
		remove_action( 'wp_ajax_nopriv_bsf_update_client_license', 'bsf_server_update_client_license' );
	}

	// Disabling after-activation redirect to Ultimate Addons Dashboard
	if ( get_option( 'ultimate_vc_addons_redirect' ) == TRUE ) {
		update_option( 'ultimate_vc_addons_redirect', FALSE );
	}
}

/**
 * Theme Setup
 */
add_action( 'after_setup_theme', 'us_theme_setup' );
function us_theme_setup() {
	global $content_width;

	if ( ! isset( $content_width ) ) {
		$content_width = 1500;
	}
	add_theme_support( 'automatic-feed-links' );

	add_theme_support( 'post-formats', array( 'quote', 'image', 'gallery', 'video' ) );

	// Add post thumbnail functionality
	add_theme_support( 'post-thumbnails' );

	/**
	 * Dev note: you can overload theme's image sizes config using filter 'us_config_image-sizes'
	 */
	$tnail_sizes = us_config( 'image-sizes' );
	foreach ( $tnail_sizes as $size_name => $size ) {
		add_image_size( $size_name, $size['width'], $size['height'], $size['crop'] );
	}

	// Excerpt length
	add_filter( 'excerpt_length', 'us_excerpt_length', 100 );
	function us_excerpt_length( $length ) {
		$excerpt_length = us_get_option( 'excerpt_length' );
		if ( $excerpt_length === NULL ) {
			return $length;
		} elseif ( $excerpt_length === '' ) {
			// If not set, showing the full excerpt
			return 9999;
		} else {
			return intval( $excerpt_length );
		}
	}

	// Remove [...] from excerpt
	add_filter( 'excerpt_more', 'us_excerpt_more' );
	function us_excerpt_more( $more ) {
		return '...';
	}

	// Theme localization
	us_maybe_load_theme_textdomain();
}

if ( function_exists( 'set_revslider_as_theme' ) ) {
	if ( ! defined( 'REV_SLIDER_AS_THEME' ) ) {
		define( 'REV_SLIDER_AS_THEME', TRUE );
	}
	set_revslider_as_theme();
}
// Actually the revslider's code above doesn't work as expected, so turning off the notifications manually
if ( get_option( 'revslider-valid-notice', 'true' ) ) {
	update_option( 'revslider-valid-notice', 'false' );
}

if ( ! defined( 'ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS' ) ) {
	define( 'ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', TRUE );
}

if ( ! function_exists( 'us_wp_title' ) ) {
	add_filter( 'wp_title', 'us_wp_title' );
	function us_wp_title( $title ) {
		if ( is_front_page() ) {
			return get_bloginfo( 'name' );
		} else {
			return trim( $title );
		}
	}
}

