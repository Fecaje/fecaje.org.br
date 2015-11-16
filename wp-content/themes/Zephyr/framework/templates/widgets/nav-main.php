<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Header navigation menu block
 *
 * (!) Important: this file is not intended to be overloaded, so use the below hooks for customizing instead
 *
 * @action Before the template: 'us_before_template:templates/widgets/nav-main'
 * @action After the template: 'us_after_template:templates/widgets/nav-main'
 */

$menu_location = apply_filters( 'us_main_menu_location', 'us_main_menu' );
if ( ! has_nav_menu( $menu_location ) ) {
	return;
}

$us_layout = US_Layout::instance();

$classes = '';
$list_classes = ' level_1 hover_' . us_get_option( 'menu_hover_effect', 'none' );
$classes .= ' layout_' . ( ( $us_layout->header_layout != 'sided' ) ? 'hor' : 'ver' );
$classes .= ' type_desktop';
$classes .= ' animation_' . us_get_option( 'menu_dropdown_effect', 'opacity' );
if ( us_get_option( 'menu_height' ) ) {
	$classes .= ' height_' . ( us_get_option( 'menu_height' ) ? 'full' : 'auto' );
}
$list_classes .= ' hidden';

?>
<!-- NAV -->
<nav class="w-nav<?php echo $classes ?>">
	<div class="w-nav-control"></div>
	<ul class="w-nav-list<?php echo $list_classes ?>">
		<?php wp_nav_menu( array(
			'theme_location' => $menu_location,
			'container' => 'ul',
			'container_class' => 'w-nav-list',
			'walker' => new US_Walker_Nav_Menu(),
			'items_wrap' => '%3$s',
			'fallback_cb' => FALSE,
		) ); ?>
	</ul>
</nav><!-- /NAV -->
