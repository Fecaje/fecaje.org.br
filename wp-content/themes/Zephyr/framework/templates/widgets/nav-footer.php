<?php defined('ABSPATH') OR die('This script cannot be accessed directly.');

/**
 * Footer navigation menu block
 *
 * (!) Important: this file is not intended to be overloaded, so use the below hooks for customizing instead
 *
 * @action Before the template: 'us_before_template:templates/widgets/nav-footer'
 * @action After the template: 'us_after_template:templates/widgets/nav-footer'
 */

$menu_location = apply_filters( 'us_main_menu_location', 'us_footer_menu' );
if ( ! has_nav_menu( $menu_location ) ) {
	return;
}

?>
<!-- NAV -->
<nav class="w-nav layout_hor">
	<ul class="w-nav-list level_1">
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
