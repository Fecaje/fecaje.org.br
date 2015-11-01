<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_social_links
 *
 * @var $shortcode {String} Current shortcode name
 * @var $shortcode_base {String} The original called shortcode name (differs if called an alias)
 * @var $atts {Array} Shortcode attributes
 * @var $content {String} Shortcode's inner content
 */

$atts = shortcode_atts( array(
	/**
	 * @var string Icons size: 'small' / 'medium' / 'large'
	 */
	'size' => 'small',
	/**
	 * @var string Icons alignment: 'left' / 'center' / 'right'
	 */
	'align' => 'left',
	/**
	 * @var bool Invert colors for all the icons?
	 */
	'inverted' => FALSE,
	/**
	 * @var bool Desaturate all the icons?
	 */
	'desaturated' => FALSE,
	/**
	 * @var string Email
	 */
	'email' => '',
	/**
	 * @var string Facebook link
	 */
	'facebook' => '',
	/**
	 * @var string Twitter
	 */
	'twitter' => '',
	/**
	 * @var string Google+ link
	 */
	'google' => '',
	/**
	 * @var string LinkedIn link
	 */
	'linkedin' => '',
	/**
	 * @var string YouTube link
	 */
	'youtube' => '',
	/**
	 * @var string Vimeo link
	 */
	'vimeo' => '',
	/**
	 * @var string Flickr link
	 */
	'flickr' => '',
	/**
	 * @var string Instagram link
	 */
	'instagram' => '',
	/**
	 * @var string Behance link
	 */
	'behance' => '',
	/**
	 * @var string Xing link
	 */
	'xing' => '',
	/**
	 * @var string Pinterest link
	 */
	'pinterest' => '',
	/**
	 * @var string Skype link
	 */
	'skype' => '',
	/**
	 * @var string Tumblr link
	 */
	'tumblr' => '',
	/**
	 * @var string Dribble link
	 */
	'dribbble' => '',
	/**
	 * @var string Vkontakte link
	 */
	'vk' => '',
	/**
	 * @var string SoundCloud link
	 */
	'soundcloud' => '',
	/**
	 * @var string Yelp link
	 */
	'yelp' => '',
	/**
	 * @var string Twitch link
	 */
	'twitch' => '',
	/**
	 * @var string DeviantArt link
	 */
	'deviantart' => '',
	/**
	 * @var string Foursquare link
	 */
	'foursquare' => '',
	/**
	 * @var string Github link
	 */
	'github' => '',
	/**
	 * @var string Odnoklassniki link
	 */
	'odnoklassniki' => '',
	/**
	 * @var string 500px link
	 */
	's500px' => '',
	/**
	 * @var string Houzz link
	 */
	'houzz' => '',
	/**
	 * @var string RSS link
	 */
	'rss' => '',
	/**
	 * @var string Custom link
	 */
	'custom_link' => '',
	/**
	 * @var string Custom link title
	 */
	'custom_title' => '',
	/**
	 * @var string Custom icon
	 */
	'custom_icon' => '',
	/**
	 * @var string Custom color
	 */
	'custom_color' => '#1abc9c',
	/**
	 * @var string Extra class name
	 */
	'el_class' => '',
), $atts );

$socials = array(
	'email' => 'Email',
	'facebook' => 'Facebook',
	'twitter' => 'Twitter',
	'google' => 'Google+',
	'linkedin' => 'LinkedIn',
	'youtube' => 'YouTube',
	'vimeo' => 'Vimeo',
	'flickr' => 'Flickr',
	'instagram' => 'Instagram',
	'behance' => 'Behance',
	'xing' => 'Xing',
	'pinterest' => 'Pinterest',
	'skype' => 'Skype',
	'tumblr' => 'Tumblr',
	'dribbble' => 'Dribbble',
	'vk' => 'Vkontakte',
	'soundcloud' => 'SoundCloud',
	'yelp' => 'Yelp',
	'twitch' => 'Twitch',
	'deviantart' => 'DeviantArt',
	'foursquare' => 'Foursquare',
	'github' => 'GitHub',
	'odnoklassniki' => 'Odnoklassniki',
	's500px' => '500px',
	'houzz' => 'Houzz',
	'rss' => 'RSS',
);

$classes = '';

global $us_socials_index;
// Social links indexes indexes start from 1
$us_socials_index = isset( $us_socials_index ) ? ( $us_socials_index + 1 ) : 1;

$classes .= ' size_' . $atts['size'];
$classes .= ' align_' . $atts['align'];

$style_id = 1 + ( $atts['inverted'] ? 1 : 0 ) + ( $atts['desaturated'] ? 2 : 0 );
$classes .= ' style_' . $style_id;

$classes .= ' index_' . $us_socials_index;
if ( $atts['el_class'] != '' ) {
	$classes .= ' ' . $atts['el_class'];
}

$output = '<div class="w-socials' . $classes . '"><div class="w-socials-list">';

foreach ( $socials as $social_key => $social ) {
	if ( empty( $atts[ $social_key ] ) ) {
		continue;
	}
	$social_url = $atts[ $social_key ];
	if ( $social_key == 'email' ) {
		if ( filter_var( $social_url, FILTER_VALIDATE_EMAIL ) ) {
			$social_url = 'mailto:' . $social_url;
		}
	} elseif ( $social_key == 'skype' ) {
		// Skype link may be some http(s): or skype: link. If protocol is not set, adding "skype:"
		if ( strpos( $social_url, ':' ) === FALSE ) {
			$social_url = 'skype:' . esc_attr( $social_url );
		}
	} else {
		$social_url = esc_url( $social_url );
	}
	$output .= '<div class="w-socials-item ' . $social_key . '">
				<a class="w-socials-item-link" target="_blank" href="' . $social_url . '"></a>
				<div class="w-socials-item-popup">
					<span>' . $social . '</span>
				</div>
			</div>';
}

// Custom icon
$custom_css = '';
$atts['custom_icon'] = trim( $atts['custom_icon'] );
if ( ! empty( $atts['custom_icon'] ) AND ! empty( $atts['custom_link'] ) ) {
	$output .= '<div class="w-socials-item custom">';
	$output .= '<a class="w-socials-item-link" target="_blank" href="' . esc_url( $atts['custom_link'] ) . '">';
	$output .= '<i class="' . us_prepare_icon_class( $atts['custom_icon'] ) . '"></i>';
	$output .= '</a>';
	$output .= '<div class="w-socials-item-popup"><span>' . $atts['custom_title'] . '</span></div>';
	$output .= '</div>';
	if ( ! empty( $atts['custom_color'] ) ) {
		$custom_css = <<<CSS
.w-socials.style_1.index_{$us_socials_index} .w-socials-item.custom .w-socials-item-link {
	color: {$atts['custom_color']};
	}
.no-touch .w-socials.index_{$us_socials_index} .w-socials-item.custom .w-socials-item-link:hover,
.w-socials.index_{$us_socials_index}.style_2 .w-socials-item.custom .w-socials-item-link {
	background-color: {$atts['custom_color']};
	}
CSS;
		$output .= '<style>' . $custom_css . '</style>';
	}
}

$output .= '</div></div>';

echo $output;
