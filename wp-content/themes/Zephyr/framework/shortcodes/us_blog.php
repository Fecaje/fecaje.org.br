<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_blog
 *
 * Listing of blog posts.
 *
 * @var $shortcode string Current shortcode name
 * @var $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var $atts array Shortcode attributes
 * @var $content string Shortcode's inner content
 */

$atts = shortcode_atts( array(
	/**
	 * @var string Blog layout: 'smallcircle' / 'large' / 'grid' / 'masonry'
	 */
	'layout' => 'large',
	/**
	 * @var string Content type: 'excerpt' / 'content' / 'none'
	 */
	'content_type' => 'excerpt',
	/**
	 * @var string Pagination type: 'none' / 'regular' / 'ajax'
	 */
	'pagination' => 'none',
	/**
	 * @var string Comma-separated list of categories slugs to filter the posts
	 */
	'categories' => NULL,
	/**
	 * @var string Posts order: 'date' / 'rand'
	 */
	'order_by' => 'date',
	/**
	 * @var bool
	 */
	'show_date' => TRUE,
	/**
	 * @var bool
	 */
	'show_author' => TRUE,
	/**
	 * @var bool
	 */
	'show_categories' => TRUE,
	/**
	 * @var bool
	 */
	'show_tags' => TRUE,
	/**
	 * @var bool
	 */
	'show_comments' => TRUE,
	/**
	 * @var bool
	 */
	'show_read_more' => TRUE,
	/**
	 * @var int Number of items per page
	 */
	'items' => NULL,
	/**
	 * @var string Extra class name
	 */
	'el_class' => '',
), $atts );

$metas = array();
foreach ( array( 'date', 'author', 'categories', 'tags', 'comments' ) as $meta_key ) {
	if ( $atts[ 'show_' . $meta_key ] ) {
		$metas[] = $meta_key;
	}
}

// Preparing query
$query_args = array(
	'post_type' => 'post',
);

// Providing proper post statuses
$query_args['post_status'] = array( 'publish' => 'publish' );
$query_args['post_status'] += (array) get_post_stati( array( 'public' => TRUE ) );
// Add private states if user is capable to view them
if ( is_user_logged_in() AND current_user_can( 'read_private_posts' ) ) {
	$query_args['post_status'] += (array) get_post_stati( array( 'private' => TRUE ) );
}
$query_args['post_status'] = array_values( $query_args['post_status'] );

if ( ! empty( $atts['categories'] ) ) {
	$query_args['category_name'] = $atts['categories'];
}

// Setting posts order
if ( $atts['order_by'] == 'rand' ) {
	$query_args['orderby'] = 'rand';
} else/*if ( $atts['order_by'] == 'date' )*/ {
	$query_args['orderby'] = array(
		'date' => 'DESC',
	);
}

// Posts per page
$atts['items'] = max( 0, intval( $atts['items'] ) );
if ( $atts['items'] > 0 ) {
	$query_args['posts_per_page'] = $atts['items'];
}

// Current page
if ( $atts['pagination'] == 'regular' ) {
	$request_paged = is_front_page() ? 'page' : 'paged';
	if ( get_query_var( $request_paged ) ) {
		$query_args['paged'] = get_query_var( $request_paged );
	}
}

$template_vars = array(
	'query_args' => $query_args,
	'layout_type' => $atts['layout'],
	'content_type' => $atts['content_type'],
	'metas' => $metas,
	'show_read_more' => ! ! $atts['show_read_more'],
	'pagination' => $atts['pagination'],
	'el_class' => ' ' . $atts['el_class'],
);
us_load_template( 'templates/blog/listing', $template_vars );
