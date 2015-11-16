<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Header language switcher block
 *
 * (!) Important: this file is not intended to be overloaded, so use the below hooks for customizing instead
 *
 * @action Before the template: 'us_before_template:templates/widgets/contacts'
 * @action After the template: 'us_after_template:templates/widgets/contacts'
 */

$output = '';
if ( us_get_option( 'header_language_source', 'own' ) == 'wpml' AND function_exists( 'icl_get_languages' ) ) {
	// fallback for WPML options
	$widget_options = array(
		'icl_lso_flags' => 1,
		'icl_lso_native_lang' => 1,
		'icl_lso_display_lang' => 1,
	);
	$wpml_options =  get_option( 'icl_sitepress_settings'  );
	foreach ( $widget_options as $option => $value ) {
		if ( isset( $wpml_options[$option] ) ) {
			$widget_options[$option] = $wpml_options[$option];
		}
	}

	$languages = icl_get_languages( 'skip_missing=0' );
	if ( count( $languages ) > 1 ) {
		$output .= '<div class="w-lang layout_dropdown"><div class="w-lang-h">';
		$output .= '<div class="w-lang-list">';
		foreach ( $languages as $language ) {
			if ( $language['active'] ) {
				$current_language = $language;
				continue;
			}
			$output .= '<a class="w-lang-item lang_' . esc_attr( $language['language_code'] ) . '" href="' . esc_url( $language['url'] ) . '">';
			if ( $widget_options['icl_lso_flags'] ) {
				$output .= '<span class="w-lang-item-icon"><img class="w-lang-item-flag" alt="flag" src="' . $language['country_flag_url'] . '"></span>';
			}

			if ( $widget_options['icl_lso_native_lang'] == 1 ) {
				$output .= '<span class="w-lang-item-title">';
				$output .= $language['native_name'];
				if ( $widget_options['icl_lso_display_lang'] AND ( $language['native_name'] != $language['translated_name'] ) ) {
					$output .= ' ('.$language['translated_name'].')';
				}
				$output .= '</span>';
			} elseif ( $widget_options['icl_lso_display_lang'] == 1 ) {
				$output .= '<span class="w-lang-item-title">';
				$output .= $language['translated_name'];
				$output .= '</span>';
			}
			$output .= '</a>';
		}
		$output .= '</div>';
		if ( isset( $current_language ) ) {
			$output .= '<div class="w-lang-current"><span class="w-lang-item"><span class="w-lang-item-icon">';
			if ( $widget_options['icl_lso_flags'] ) {
				$output .= '<img class="w-lang-item-flag" alt="flag" src="' . $current_language['country_flag_url'] . '"></span>';
			}
			if ( $widget_options['icl_lso_native_lang'] == 1 ) {
				$output .= '<span class="w-lang-item-title">';
				$output .= $current_language['native_name'];
				if ( $widget_options['icl_lso_display_lang'] AND ( $current_language['native_name'] != $current_language['translated_name'] ) ) {
					$output .= ' ('.$current_language['translated_name'].')';
				}
				$output .= '</span>';
			} elseif ( $widget_options['icl_lso_display_lang'] == 1 ) {
				$output .= '<span class="w-lang-item-title">';
				$output .= $current_language['translated_name'];
				$output .= '</span>';
			}
			$output .= '</span></div>';
		}
		$output .= '</div></div>';
	}
} elseif ( us_get_option( 'header_language_source', 'own' ) == 'own' ) {
	$output .= '<div class="w-lang layout_dropdown has_title"><div class="w-lang-h">';
	$output .= '<div class="w-lang-list">';
	for ( $i = 1; $i <= us_get_option( 'header_link_qty', 2 ); $i ++ ) {
		$output .= '<a class="w-lang-item" href="';
		if ( substr( us_get_option( 'header_link_' . $i . '_url' ), 0, 4 ) == 'http' ) {
			$output .= esc_url( us_get_option( 'header_link_' . $i . '_url' ) );
		} else {
			$output .= esc_url( '//' . us_get_option( 'header_link_' . $i . '_url' ) );
		}
		$output .= '"><span class="w-lang-item-icon"></span>';
		$output .= '<span class="w-lang-item-title">' . us_get_option( 'header_link_' . $i . '_label' ) . '</span>';
		$output .= '</a>';
	}
	$output .= '</div>';
	$output .= '<div class="w-lang-current"><span class="w-lang-item"><span class="w-lang-item-icon"></span>';
	$output .= '<span class="w-lang-item-title">' . us_get_option( 'header_link_title' ) . '</span>';
	$output .= '</span></div>';
	$output .= '</div></div>';
}
echo $output;
