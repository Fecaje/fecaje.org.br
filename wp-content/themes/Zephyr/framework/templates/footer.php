<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$us_layout = US_Layout::instance();
?>
</div>
<!-- /CANVAS -->

<?php if ( $us_layout->footer_show_top OR $us_layout->footer_show_bottom ) { ?>

<?php do_action( 'us_before_footer' ) ?>

<?php
$footer_classes = '';
$footer_layout = us_get_option( 'footer_layout' );
if ( $footer_layout != NULL ) {
	$footer_classes .= ' layout_' . $footer_layout;
}
?>
<!-- FOOTER -->
<div class="l-footer<?php echo $footer_classes; ?>">

<?php if ( $us_layout->footer_show_top ): ?>
	<!-- subfooter: top -->
	<div class="l-subfooter at_top">
		<div class="l-subfooter-h i-cf">

			<?php do_action( 'us_top_subfooter_start' ) ?>

			<div class="g-cols offset_medium">
			<?php
			$columns_number = (int) us_get_option( 'footer_columns', 3 );
			if ( $columns_number < 1 OR $columns_number > 4 ) {
				$columns_number = 3;
			}
			$columns_classes = array (
				1 => 'full-width',
				2 => 'one-half',
				3 => 'one-third',
				4 => 'one-quarter',
			);
			$columns_class = $columns_classes[$columns_number];
			$widget_names = array (
				1 => 'footer_first',
				2 => 'footer_second',
				3 => 'footer_third',
				4 => 'footer_fourth',
			);
			for ( $i = 1; $i <= $columns_number; $i ++ ) {
				?>
				<div class="<?php echo $columns_class ?>">
					<?php dynamic_sidebar( $widget_names[ $i ] ) ?>
				</div>
				<?php
			}
			?>
			</div>

			<?php do_action( 'us_top_subfooter_end' ) ?>

		</div>
	</div>
<?php endif/*( $us_layout->footer_show_top )*/; ?>

<?php if ( $us_layout->footer_show_bottom ): ?>
	<!-- subfooter: bottom -->
	<div class="l-subfooter at_bottom">
		<div class="l-subfooter-h i-cf">

			<?php do_action( 'us_bottom_subfooter_start' ) ?>

			<?php us_load_template( 'templates/widgets/nav-footer' ) ?>

			<div class="w-copyright"><?php echo us_get_option( 'footer_copyright', '' ) ?></div>

			<?php do_action( 'us_bottom_subfooter_end' ) ?>

		</div>
	</div>
<?php endif/*( $us_layout->footer_show_bottom )*/; ?>

</div>
<!-- /FOOTER -->

<?php do_action( 'us_after_footer' ) ?>

<?php }/*( $us_layout->footer_show_top OR $us_layout->footer_show_bottom )*/; ?>

<a class="w-toplink" href="#" title="<?php _e( 'Back to top', 'us'); ?>"></a>
<script type="text/javascript">
	if (window.$us === undefined) window.$us = {};
	$us.canvasOptions = ($us.canvasOptions || {});
	$us.canvasOptions.disableStickyHeaderWidth = <?php echo intval( us_get_option( 'header_sticky_disable_width', 900 ) ) ?>;
	$us.canvasOptions.disableEffectsWidth = <?php echo intval( us_get_option( 'disable_effects_width', 900 ) ) ?>;
	$us.canvasOptions.headerScrollBreakpoint = <?php echo intval( us_get_option( 'header_scroll_breakpoint', 100 ) ) ?>;
	$us.canvasOptions.responsive = <?php echo us_get_option( 'responsive_layout', TRUE ) ? 'true' : 'false' ?>;

	$us.navOptions = ($us.navOptions || {});
	$us.navOptions.mobileWidth = <?php echo intval( us_get_option( 'menu_mobile_width', 900 ) ) ?>;
	$us.navOptions.togglable = <?php echo us_get_option( 'menu_togglable_type', TRUE ) ? 'true' : 'false' ?>;
</script>
<?php echo us_get_option( 'custom_html', '' ) ?>

<?php wp_footer(); ?>

</body>
</html>
