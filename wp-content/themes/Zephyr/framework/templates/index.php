<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );
/**
 * Index template (used for front page blog listing)
 */
$us_layout = US_Layout::instance();
get_header();

$template_vars = array(
	'layout_type' => us_get_option( 'blog_layout', 'large' ),
	'metas' => array(),
	'content_type' => us_get_option( 'blog_content_type', 'excerpt' ),
	'show_read_more' => ! ! us_get_option( 'blog_read_more', TRUE ),
	'pagination' => us_get_option( 'blog_pagination', 'regular' ),
);
foreach ( array( 'date', 'author', 'categories', 'comments', 'tags' ) as $meta_key ) {
	if ( us_get_option( 'blog_meta_' . $meta_key, TRUE ) ) {
		$template_vars['metas'][] = $meta_key;
	}
}

?>
<!-- MAIN -->
<div class="l-main">
	<div class="l-main-h i-cf">

		<div class="l-content g-html">
			<section class="l-section">
				<div class="l-section-h i-cf">

					<?php do_action( 'us_before_index' ) ?>

					<?php us_load_template( 'templates/blog/listing', $template_vars ) ?>

					<?php do_action( 'us_after_index' ) ?>

				</div>
			</section>
		</div>

<?php if ( $us_layout->sidebar_pos == 'left' OR $us_layout->sidebar_pos == 'right' ): ?>
		<aside class="l-sidebar at_<?php echo $us_layout->sidebar_pos ?>">
			<?php dynamic_sidebar( 'default_sidebar' ) ?>
		</aside>
<?php endif; ?>

	</div>
</div>
<?php

get_footer();
