<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output a single blog listing. Universal template that is used by all the possible blog posts listings.
 *
 * (!) $query_args should be filtered before passing to this template.
 *
 * @var $query_args array Arguments for the new WP_Query. If not set, current global $wp_query will be used instead.
 * @var $layout_type string Blog layout: large / smallcircle / smallsquare / grid / masonry / compact / related / latest
 * @var $columns int Number of columns 1 / 2 / 3 / 4
 * @var $metas array Meta data that should be shown: array('date', 'author', 'categories', 'tags', 'comments')
 * @var $content_type string Content type: 'excerpt' / 'content' / 'none'
 * @var $show_read_more boolean Show "Read more" link after the excerpt?
 * @var $pagination string Pagination type: regular / none / ajax
 * @var $el_class string Additional classes that will be appended to the main .w-blog container
 *
 * @action Before the template: 'us_before_template:templates/blog/listing'
 * @action After the template: 'us_after_template:templates/blog/listing'
 * @filter Template variables: 'us_template_vars:templates/blog/listing'
 */


// Variables defaults and filtering
$layout_type = isset( $layout_type ) ? $layout_type : 'large';
$columns = ( isset( $columns ) AND $layout_type == 'latest' ) ? intval( $columns ) : 1;
$default_metas = array( 'date', 'author', 'categories', 'tags', 'comments' );
$metas = ( isset( $metas ) AND is_array( $metas ) ) ? array_intersect( $metas, $default_metas ) : $default_metas;
$content_type = isset( $content_type ) ? $content_type : 'excerpt';
$show_read_more = isset( $show_read_more ) ? $show_read_more : TRUE;
$pagination = isset( $pagination ) ? $pagination : 'regular';
$el_class = isset( $el_class ) ? $el_class : '';

// .w-blog container additional classes and inner CSS-styles
$classes = '';
$inner_css = '';

// Filtering and executing database query
global $wp_query;
$use_custom_query = isset( $query_args ) AND is_array( $query_args ) AND ! empty( $query_args );
if ( $use_custom_query ) {
	us_open_wp_query_context();
	$wp_query = new WP_Query( $query_args );
} else {
	$query_args = $wp_query->query;
	// Extracting query arguments from WP_Query that are not shown but relevant
	if ( ! isset( $query_args['post_type'] ) AND preg_match_all( '~\.post_type = \'([a-z0-9\_\-]+)\'~', $wp_query->request, $matches ) ) {
		$query_args['post_type'] = $matches[1];
	}
	if ( ! isset( $query_args['post_status'] ) AND preg_match_all( '~\.post_status = \'([a-z]+)\'~', $wp_query->request, $matches ) ) {
		$query_args['post_status'] = $matches[1];
	}
}

if ( ! have_posts() ) {
	// TODO Move to a separate variable
	_e( 'No posts were found.', 'us' );

	return;
}

if ( $layout_type == 'masonry' ) {
	// We'll need the isotope script for masonry layout
	wp_enqueue_script( 'us-isotope' );
}
$classes .= ' layout_' . $layout_type;

if ( $layout_type == 'latest' ) {
	$classes .= ' cols_' . $columns;
}

if ( ! empty( $el_class ) ) {
	$classes .= ' ' . $el_class;
}

?><div class="w-blog<?php echo $classes ?>"><div class="w-blog-list"><?php

// Preparing template settings for loop post template
$template_vars = array(
	'layout_type' => $layout_type,
	'metas' => $metas,
	'content_type' => $content_type,
	'show_read_more' => $show_read_more,
);

// Start the loop.
while ( have_posts() ){
	the_post();

	us_load_template( 'templates/blog/listing-post', $template_vars );
}

?></div><?php

if ( $wp_query->max_num_pages > 1 ) {
	if ( $pagination == 'regular' ) {
		?><div class="g-pagination"><?php
		the_posts_pagination( array(
			'prev_text' => '<',
			'next_text' => '>',
			'before_page_number' => '<span>',
			'after_page_number' => '</span>',
		) );
		?></div><?php
	} elseif ( $pagination == 'ajax' ) {
		// Next page elements may have sliders, so we preloading the needed assets now
		// TODO On-demand ajax assets usage
		wp_enqueue_script( 'us-royalslider' );
		wp_enqueue_style('us-royalslider');
		// Passing g-loadmore options to JavaScript via onclick event
		$loadmore_options = array(
			// Controller options
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'action' => 'us_ajax_blog',
			'max_num_pages' => $wp_query->max_num_pages,
			// Blog listing template variables that will be passed to this file in the next call
			'template_vars' => array(
				'query_args' => $query_args,
				'layout_type' => $layout_type,
				'metas' => $metas,
				'content_type' => $content_type,
				'show_read_more' => $show_read_more,
			),
		);
		?><div class="g-loadmore"<?php echo us_pass_data_to_js( $loadmore_options ) ?>>
			<div class="g-loadmore-btn">
				<span><?php _e( 'Load More', 'us' ) ?></span>
			</div>
			<div class="g-preloader type_1"></div>
		</div><?php
	}
}

?></div><?php

if ( $use_custom_query ) {
	// Cleaning up
	us_close_wp_query_context();
}
