<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );
/**
 * Outputs summary about the post's author
 *
 * @action Before the template: 'us_before_template:templates/blog/single-post-author'
 * @action After the template: 'us_after_template:templates/blog/single-post-author'
 */

global $authordata;
$author_avatar = get_avatar( $authordata->ID );
$author_url = get_the_author_meta( 'url' );
if ( ! empty( $author_url ) ) {
	$author_avatar = '<a href="' . esc_url( $author_url ) . '" rel="author external" target="_blank">' . $author_avatar . '</a>';
}
?>

<section class="l-section for_author">
	<div class="l-section-h i-cf">
		<div class="w-author">
			<div class="w-author-img">
				<?php echo $author_avatar ?>
			</div>
			<div class="w-author-name">
				<?php the_author_link() ?>
			</div>
			<div class="w-author-bio"><?php the_author_meta( 'description' ) ?></div>
		</div>
	</div>
</section>
