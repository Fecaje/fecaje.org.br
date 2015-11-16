<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output one post from blog listing.
 *
 * (!) Should be called in WP_Query fetching loop only.
 * @link https://codex.wordpress.org/Class_Reference/WP_Query#Standard_Loop
 *
 * @var $layout_type string Blog layout: large / smallcircle / smallsquare / grid / masonry / compact / related / latest
 * @var $metas array Meta data that should be shown: array('date', 'author', 'categories', 'tags', 'comments')
 * @var $content_type string Content type: 'excerpt' / 'content' / 'none'
 * @var $show_read_more boolean
 *
 * @action Before the template: 'us_before_template:templates/blog/listing-post'
 * @action After the template: 'us_after_template:templates/blog/listing-post'
 * @filter Template variables: 'us_template_vars:templates/blog/listing-post'
 */

// Retreiving post format
$post_format = get_post_format() ? get_post_format() : 'standard';

// Determining thumbnail size
$thumbnail_sizes = array(
	'large' => 'large',
	'smallcircle' => 'tnail-1x1-small',
	'smallsquare' => 'tnail-1x1-small',
	'compact' => FALSE,
	'related' => 'tnail-3x2',
	'grid' => 'tnail-3x2',
	'masonry' => 'tnail-masonry',
	'latest' => FALSE,
);
$has_preview = ( ! isset( $thumbnail_sizes[ $layout_type ] ) OR $thumbnail_sizes[ $layout_type ] !== FALSE );

$the_content = get_the_content();

$featured_image = '';
$featured_html = '';
if ( $has_preview AND ! post_password_required() ) {
	$thumbnail_size = isset( $thumbnail_sizes[ $layout_type ] ) ? $thumbnail_sizes [ $layout_type ] : 'large';
	$featured_image = has_post_thumbnail() ? get_the_post_thumbnail( get_the_ID(), $thumbnail_size ) : '';
	if ( $featured_image == '' ) {
		// We fetch previews for images at any layout and for any post formats at large / grid / masonry layouts
		if ( $post_format == 'image' ) {
			$featured_image = us_get_post_preview( $the_content, TRUE );
		} elseif ( in_array( $layout_type, array( 'large', 'grid', 'masonry' ) ) ) {
			$featured_html = us_get_post_preview( $the_content, TRUE );
		}
	}
}

// We need some special markup for quotes
$use_special_quote_markup = ( $post_format == 'quote' AND ! in_array( $layout_type, array( 'compact', 'related' ) ) );

if ( $use_special_quote_markup ){
	// Always display content for normal quotes
	$content_type = 'content';
}

if ( $content_type == 'content' ) {
	$the_content = apply_filters( 'the_content', $the_content );
} elseif ( $content_type == 'none' ) {
	$the_content = '';
} else/*if ( $content_type == 'excerpt' )*/ {
	$the_content = apply_filters( 'the_excerpt', get_the_excerpt() );
}

// Meta => certain html in a proper order
$meta_html = array_fill_keys( $metas, '' );

// Preparing post metas separately because we might want to order them inside the .w-blog-post-meta in future
$meta_html['date'] = '<time class="w-blog-post-meta-date date updated';
if ( ! in_array( 'date', $metas ) ) {
	// Hiding from users but not from search engines
	$meta_html['date'] .= ' hidden';
}
$meta_html['date'] .= '">';
if ( $layout_type == 'latest' ) {
	// Special date format for latest posts
	$meta_html['date'] .= '<span class="w-blog-post-meta-date-month">' . get_the_date( 'M' ) . '</span>';
	$meta_html['date'] .= '<span class="w-blog-post-meta-date-day">' . get_the_date( 'd' ) . '</span>';
	$meta_html['date'] .= '<span class="w-blog-post-meta-date-year">' . get_the_date( 'Y' ) . '</span>';
} else {
	$meta_html['date'] .= get_the_date();
}
$meta_html['date'] .= '</time>';

$meta_html['author'] = '<span class="w-blog-post-meta-author vcard author';
if ( ! in_array('author', $metas) ) {
	$meta_html['author'] .= ' hidden';
}
$meta_html['author'] .= '">';
if ( get_the_author_meta( 'url' ) ) {
	$meta_html['author'] .= '<a href="' . esc_url( get_the_author_meta( 'url' ) ) . '" class="fn">' . get_the_author() . '</a>';
} else {
	$meta_html['author'] .= '<span class="fn">' . get_the_author() . '</span>';
}
$meta_html['author'] .= '</span>';

if ( in_array('categories', $metas) ) {
	$meta_html['categories'] = get_the_category_list( ', ' );
	if ( ! empty( $meta_html['categories'] ) ) {
		$meta_html['categories'] = '<span class="w-blog-post-meta-category">' . $meta_html['categories'] . '</span>';
	}
}

if ( in_array( 'tags', $metas ) ) {
	$meta_html['tags'] = get_the_tag_list( '', ', ', '' );
	if ( ! empty( $meta_html['tags'] ) ) {
		$meta_html['tags'] = '<span class="w-blog-post-meta-tags">' . $meta_html['tags'] . '</span>';
	}
}

$comments_number = get_comments_number();
if ( in_array('comments', $metas) AND ! ( $comments_number == 0 AND ! comments_open() ) ) {
	$meta_html['comments'] = '<span class="w-blog-post-meta-comments">';
	// TODO Replace with get_comments_popup_link() when https://core.trac.wordpress.org/ticket/17763 is resolved
	ob_start();
	$comments_label = sprintf( _n( '%s comment', '%s comments', $comments_number, 'us' ), $comments_number );
	comments_popup_link( __( 'No comments', 'us' ), $comments_label, $comments_label );
	$meta_html['comments'] .= ob_get_clean();
	$meta_html['comments'] .= '</span>';
}

$meta_html = apply_filters( 'us_listing_post_meta_html', $meta_html, get_the_ID() );
?>

<?php if ( ! $use_special_quote_markup ): ?>

<div <?php post_class( 'w-blog-post' ) ?>>
	<div class="w-blog-post-h">
<?php if ( $has_preview AND ! empty( $featured_html ) ): ?>
		<span class="w-blog-post-preview">
			<?php echo $featured_html ?>
			<span class="w-blog-post-preview-icon"></span>
		</span>
<?php endif/*( ! empty( $featured_html ) )*/; ?>
		<a class="w-blog-post-link" href="<?php the_permalink() ?>">
<?php if ( $has_preview AND empty( $featured_html ) ): ?>
			<span class="w-blog-post-preview">
				<?php echo $featured_image; ?>
				<span class="w-blog-post-preview-icon"></span>
			</span>
<?php endif/*( empty( $featured_html ) )*/; ?>
			<h2 class="w-blog-post-title">
				<span class="entry-title"><?php the_title(); ?></span>
			</h2>
		</a>
		<div class="w-blog-post-body">
			<div class="w-blog-post-meta<?php echo empty($metas) ? ' hidden' : '' ?>">
				<?php echo implode( '', $meta_html ) ?>
			</div>
<?php if ( ! empty( $the_content ) ): ?>
			<div class="w-blog-post-content">
				<?php echo $the_content ?>
			</div>
<?php endif/*( ! empty( $the_content ) )*/; ?>
<?php if ( $show_read_more ): ?>
			<a class="w-blog-post-more w-btn" href="<?php the_permalink() ?>"><span class="w-btn-label"><?php _e( 'Read More', 'us' ) ?></span></a>
<?php endif/*( $show_read_more )*/; ?>
		</div>
	</div>
</div>

<?php else/*if ( $use_special_quote_markup )*/: ?>

<div <?php post_class( 'w-blog-post' ) ?>>
	<div class="w-blog-post-h">
		<span class="w-blog-post-preview">
			<?php echo $featured_image ?>
			<span class="w-blog-post-preview-icon"></span>
		</span>
		<div class="w-blog-post-body">
			<blockquote>
				<?php echo $the_content ?>
				<cite class="entry-title"><?php the_title() ?></cite>
			</blockquote>
		</div>
	</div>
</div>

<?php endif/*( $use_special_quote_markup )*/; ?>

