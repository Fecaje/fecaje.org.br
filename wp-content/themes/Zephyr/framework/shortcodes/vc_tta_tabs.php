<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: vc_tta_tabs
 *
 * Overloaded by UpSolution custom implementation.
 *
 * @var $shortcode {String} Current shortcode name
 * @var $shortcode_base {String} The original called shortcode name (differs if called an alias)
 * @var $atts {Array} Shortcode attributes
 * @var $content {String} Shortcode's inner content
 */

$atts = shortcode_atts( array(
	/**
	 * @var bool Act as toggle?
	 * @for [vc_tta_accordion]
	 */
	'toggle' => FALSE,
	/**
	 * @var string Text alignment: 'left' / 'center' / 'right'
	 * @for [vc_tta_accordion], [vc_tta_tour]
	 */
	'c_align' => 'left',
	/**
	 * @var string Icon: '' / 'chevron' / 'plus' / 'triangle'
	 * @for [vc_tta_accordion]
	 */
	'c_icon' => 'chevron',
	/**
	 * @var string Icon position: 'left' / 'right'
	 * @for [vc_tta_accordion]
	 */
	'c_position' => 'right',
	/**
	 * @var string Tabs layout: '' / 'timeline'
	 * @for [vc_tta_tabs]
	 */
	'layout' => '',
	/**
	 * @var string Tabs position: 'left' / 'right'
	 * @for [vc_tta_tour]
	 */
	'tab_position' => 'left',
	/**
	 * @var string Tabs size: 'auto' / '10' / '20' / '30' / '40' / '50'
	 * @for [vc_tta_tour]
	 */
	'controls_size' => 'auto',
	/**
	 * @var string Extra class
	 * @for [vc_tta_accordion], [vc_tta_tabs], [vc_tta_tour]
	 */
	'el_class' => '',
), $atts );

$classes = '';
$list_classes = '';

// Extract tab attributes for future html preparations
global $us_tabs_atts;
preg_match_all( '/\[vc_tta_section([^\]]*?)\]/i', $content, $matches, PREG_OFFSET_CAPTURE );
$us_tabs_atts = isset( $matches[0] ) ? $matches[0] : array();
$active_tab_indexes = array();
foreach ( $us_tabs_atts as $index => $tab_atts ) {
	$us_tabs_atts[ $index ] = shortcode_parse_atts( '[' . rtrim( $tab_atts[0], '[]' ) . ' ]' );
	if ( isset( $us_tabs_atts[ $index ]['active'] ) AND $us_tabs_atts[ $index ]['active'] ) {
		$active_tab_indexes[] = $index;
	}
}
// If none of the tabs is active, the first one will be
if ( empty( $active_tab_indexes ) AND ! empty( $us_tabs_atts ) AND ! $atts['toggle'] ) {
	$active_tab_indexes[] = 0;
	$us_tabs_atts[0]['active'] = 'yes';
}

// Inheriging some of the attributes to the sections
foreach ( $us_tabs_atts as $index => $tab_atts ) {
	$us_tabs_atts[ $index ]['c_position'] = $atts['c_position'];
}

$layout = 'default';
if ( $atts['layout'] == 'timeline' ) {
	$layout = 'timeline';
} elseif ( $shortcode_base == 'vc_tta_tabs' ) {
	$list_classes .= ' hidden';
} elseif ( $shortcode_base == 'vc_tta_tour' ) {
	$layout = 'ver';
	$classes .= ' navpos_' . $atts['tab_position'] . ' navwidth_' . $atts['controls_size'] . ' title_' . $atts['c_align'];
}

$classes .= ' layout_' . $layout;
$list_classes .= ' items_' . count( $us_tabs_atts );

// Accordion-specific settings
if ( $shortcode_base == 'vc_tta_accordion' ) {
	$classes .= ' accordion';
	if ( $atts['toggle'] ) {
		$classes .= ' type_togglable';
	}
	$classes .= ' title_' . $atts['c_align'];
	if ( ! empty( $atts['c_icon'] ) ) {
		$classes .= ' icon_' . $atts['c_icon'] . ' iconpos_' . $atts['c_position'];
	} else {
		$classes .= ' icon_none';
	}
} else {
	// For accordion state of tabs
	$classes .= ' icon_chevron iconpos_right';
}

if ( ! empty( $atts['el_class'] ) ) {
	$classes .= ' ' . $atts['el_class'];
}

$output = '<div class="w-tabs' . $classes . ' ">';

// Preparing tab titles
$output .= '<div class="w-tabs-list' . $list_classes . '"><div class="w-tabs-list-h">';
foreach ( $us_tabs_atts as $index => $tab_atts ) {
	$tab_atts['title'] = isset( $tab_atts['title'] ) ? $tab_atts['title'] : '';
	$tab_atts['i_position'] = isset( $tab_atts['i_position'] ) ? $tab_atts['i_position'] : 'left';
	$active_class = ( isset( $tab_atts['active'] ) AND $tab_atts['active'] ) ? ' active' : '';
	$icon_class = isset( $tab_atts['icon'] ) ? ' with_icon' : '';
	$output .= '<div class="w-tabs-item' . $active_class . $icon_class . '"><div class="w-tabs-item-h">';
	if ( isset( $tab_atts['icon'] ) AND $tab_atts['i_position'] == 'left' ) {
		$output .= '<i class="' . us_prepare_icon_class( $tab_atts['icon'] ) . '"></i>';
	}
	$output .= '<span class="w-tabs-item-title">' . $tab_atts['title'] . '</span>';
	if ( isset( $tab_atts['icon'] ) AND $tab_atts['i_position'] == 'right' ) {
		$output .= '<i class="' . us_prepare_icon_class( $tab_atts['icon'] ) . '"></i>';
	}
	$output .= '</div></div>' . "\n";
}
$output .= '</div></div>';

// Handling inner tabs
global $us_tab_index;
$us_tab_index = 0;
$output .= '<div class="w-tabs-sections"><div class="w-tabs-sections-h">' . do_shortcode( $content ) . '</div></div></div>';

echo $output;
