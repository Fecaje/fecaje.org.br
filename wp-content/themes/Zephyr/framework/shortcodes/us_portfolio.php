<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_portfolio
 *
 * Listing of portfolio items.
 *
 * @var $shortcode string Current shortcode name
 * @var $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var $atts array Shortcode attributes
 * @var $content string Shortcode's inner content
 */

$atts = shortcode_atts( array(
	/**
	 * @var int Columns number: 2 / 3 / 4 / 5
	 */
	'columns' => 3,
	/**
	 * @var string Pagination type: 'none' / 'regular' / 'ajax'
	 */
	'pagination' => 'none',
	/**
	 * @var int Number of items per page (left empty to display all the items)
	 */
	'items' => 0,
	/**
	 * @var string Items style: 'style_1' / 'style_2' / ... / 'style_N'
	 */
	'style' => 'style_1',
	/**
	 * @var string Items text alignment: 'left' / 'center' / 'right'
	 */
	'align' => 'center',
	/**
	 * @var string Items ratio: '3x2' / '4x3' / '1x1' / '2x3' / '3x4'
	 */
	'ratio' => '1x1',
	/**
	 * @var string Items meta: '' / 'date' / 'categories'
	 */
	'meta' => '',
	/**
	 * @var string Filter type: 'none' / 'category'
	 */
	'filter' => 'none',
	/**
	 * @var bool Add indents between items?
	 */
	'with_indents' => FALSE,
	/**
	 * @var bool Posts order: 'date' / 'rand'
	 */
	'orderby' => 'date',
	/**
	 * @var string Comma-separated list of categories slugs
	 */
	'categories' => NULL,
	/**
	 * @var string Extra class name
	 */
	'el_class' => '',
), $atts );

$template_vars = array(
	'categories' => $atts['categories'],
	'style_name' => $atts['style'],
	'columns' => $atts['columns'],
	'ratio' => $atts['ratio'],
	'metas' => array( 'title', $atts['meta'] ),
	'align' => $atts['align'],
	'filter' => $atts['filter'],
	'with_indents' => $atts['with_indents'],
	'pagination' => $atts['pagination'],
	'orderby' => ( $atts['orderby'] == 'rand' ) ? 'rand' : 'date',
	'perpage' => intval( $atts['items'] ),
	'el_class' => $atts['el_class'],
);

// Current page
if ( $atts['pagination'] == 'regular' ) {
	$request_paged = is_front_page() ? 'page' : 'paged';
	if ( get_query_var( $request_paged ) ) {
		$template_vars['page'] = get_query_var( $request_paged );
	}
}

us_load_template( 'templates/portfolio/listing', $template_vars );


