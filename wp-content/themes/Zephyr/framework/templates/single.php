<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );
/**
 * The template for displaying all single posts and attachments
 */
$us_layout = US_Layout::instance();
// Needed for canvas class
//$us_layout->titlebar = 'default';
get_header();

$metas = array();
foreach ( array( 'date', 'author', 'categories', 'comments' ) as $meta_key ) {
	if ( us_get_option( 'post_meta_' . $meta_key ) ) {
		$metas[] = $meta_key;
	}
}

$template_vars = array(
	'metas' => $metas,
	'show_tags' => ! ! us_get_option( 'post_meta_tags' ),
);
?>
<!-- MAIN -->
<div class="l-main">
	<div class="l-main-h i-cf">

		<div class="l-content g-html">

			<?php do_action( 'us_before_single' ) ?>

			<?php
			while ( have_posts() ){
				the_post();

				us_load_template( 'templates/blog/single-post', $template_vars );

			}
			?>

			<?php do_action( 'us_after_single' ) ?>

		</div>

		<?php if ( $us_layout->sidebar_pos == 'left' OR $us_layout->sidebar_pos == 'right' ): ?>
			<aside class="l-sidebar at_<?php echo $us_layout->sidebar_pos ?>">
				<?php dynamic_sidebar( 'default_sidebar' ); ?>
			</aside>
		<?php endif; ?>

	</div>
</div>

<?php get_footer(); ?>
