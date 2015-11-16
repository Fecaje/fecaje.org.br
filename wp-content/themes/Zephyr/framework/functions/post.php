<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Retrieves and returns the part of current post that can be used as the post's preview.
 *
 * (!) Should be called in WP_Query fetching loop only.
 *
 * @param string $the_content Post content, retrieved with get_the_content() (without 'the_content' filters)
 * @param bool $strip_from_the_content Should the found element be removed from post content not to be duplicated?
 *
 * @return string
 */
function us_get_post_preview( &$the_content, $strip_from_the_content = FALSE ) {
	// Retreiving post format
	$post_format = get_post_format() ? get_post_format() : 'standard';
	$preview_html = '';

	// Retrieving post preview
	if ( $post_format == 'image' ) {
		if ( preg_match( "%^<img.+?>%", $the_content, $matches ) ) {
			// Using first inner image
			$preview_html = $matches[0];
			if ( $strip_from_the_content ) {
				$the_content = str_replace( $matches[0], '', $the_content );
			}
		} elseif ( preg_match( '~^(https?(?://([^/?#]*))?([^?#]*?\.(?:jpe?g|gif|png)))~', $the_content, $matches ) ) {
			// Using first image link
			$preview_html = '<img src="' . $matches[0] . '" alt="">';
			if ( $strip_from_the_content ) {
				$the_content = str_replace( $matches[0], '', $the_content );
			}
		}
	} elseif ( $post_format == 'gallery' ) {
		if ( preg_match( '~\[us_gallery.+?\]|\[us_image_slider.+?\]|\[gallery.+?\]~', $the_content, $matches ) ) {

			// Replacing with a simple image slider
			$gallery = preg_replace( '~(vc_gallery|us_gallery|gallery)~', 'us_image_slider', $matches[0] );
			$preview_html = do_shortcode( $gallery );

			if ( $strip_from_the_content ) {
				$the_content = str_replace( $matches[0], '', $the_content );
			}
		}
	} elseif ( $post_format == 'video' ) {
		$post_content = preg_replace( '~^\s*(https?://[^\s"]+)\s*$~im', "[embed]$1[/embed]", $the_content );

		if ( preg_match( '~\[embed.+?\]|\[vc_video.+?\]~', $post_content, $matches ) ) {
			global $wp_embed;
			$video = $matches[0];
			$preview_html = do_shortcode( $wp_embed->run_shortcode( $video ) );
			if ( strpos( $preview_html, 'w-video' ) === FALSE ) {
				$preview_html = '<div class="w-video"><div class="w-video-h">' . $preview_html . '</div></div>';
			}
			$post_content = str_replace( $matches[0], "", $post_content );
		}

		if ( ! empty( $preview_html ) AND $strip_from_the_content ) {
			$the_content = $post_content;
		}
	}

	$preview_html = apply_filters( 'us_get_post_preview', $preview_html, get_the_ID() );

	return $preview_html;
}

/**
 * @var array The list of portfolio items that are not supposed to have their own pages (external links or lightboxes)
 */
global $us_post_prevnext_exclude_ids;

/**
 * Get information about previous and next post or page (should be used in singular element context)
 *
 * @return array
 */
function us_get_post_prevnext() {

	// TODO Create for singular pages https://codex.wordpress.org/Next_and_Previous_Links#The_Next_and_Previous_Pages
	$result = array();
	if ( is_singular( 'us_portfolio' ) ) {
		global $us_post_prevnext_exclude_ids;
		if ( $us_post_prevnext_exclude_ids === NULL ) {
			// Getting the list of portfolio items that are not supposed to have their own pages (external links or lightboxes)
			global $wpdb;
			$wpdb_query = 'SELECT `post_id` FROM `' . $wpdb->postmeta . '` ';
			$wpdb_query .= 'WHERE (`meta_key`=\'us_lightbox\' AND `meta_value`=\'1\') ';
			$wpdb_query .= 'OR (`meta_key`=\'us_custom_link\' AND `meta_value` != \'\')';
			$us_post_prevnext_exclude_ids = apply_filters( 'us_get_post_prevnext_exclude_ids', $wpdb->get_col( $wpdb_query ) );
			if ( ! empty( $us_post_prevnext_exclude_ids ) ) {
				add_filter( 'get_next_post_where', 'us_exclude_hidden_portfolios_from_prevnext' );
				add_filter( 'get_previous_post_where', 'us_exclude_hidden_portfolios_from_prevnext' );
			}
		}
		$in_same_term = ! ! us_get_option( 'portfolio_prevnext_category' );
		$next_post = get_next_post( $in_same_term, '', 'us_portfolio_category' );
		$prev_post = get_previous_post( $in_same_term, '', 'us_portfolio_category' );
	} else {
		$next_post = get_next_post( FALSE );
		$prev_post = get_previous_post( FALSE );
	}
	if ( ! empty( $prev_post ) ) {
		$result['prev'] = array(
			'link' => get_permalink( $prev_post->ID ),
			'title' => get_the_title( $prev_post->ID ),
			'meta' => __( 'Previous post', 'us' ),
		);
	}
	if ( ! empty( $next_post ) ) {
		$result['next'] = array(
			'link' => get_permalink( $next_post->ID ),
			'title' => get_the_title( $next_post->ID ),
			'meta' => __( 'Next post', 'us' ),
		);
	}

	return $result;
}

function us_exclude_hidden_portfolios_from_prevnext( $where ) {
	global $us_post_prevnext_exclude_ids;
	$where .= ' AND p.ID NOT IN (' . implode( ',', $us_post_prevnext_exclude_ids ) . ')';

	return $where;
}

add_filter( 'the_password_form', 'us_the_password_form' );
function us_the_password_form() {
	$template_vars = array(
		'type' => 'protectedpost',
		'action' => get_option( 'siteurl' ) . '/wp-login.php?action=postpass',
		'method' => 'post',
		'fields' => array(
			'info' => array(
				'type' => 'info',
				'title' => __( 'This post is password protected. To view it please enter your password below:', 'us' ),
			),
			'post_password' => array(
				'type' => 'password',
			),
			'submit' => array(
				'type' => 'submit',
				'title' => __( 'Submit', 'us' ),
				'btn_classes' => '',
			),
		),
	);

	return us_get_template( 'templates/form/form', $template_vars );
}
