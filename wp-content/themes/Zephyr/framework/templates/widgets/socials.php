<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Header socials block
 *
 * (!) Important: this file is not intended to be overloaded, so use the below hooks for customizing instead
 *
 * @action Before the template: 'us_before_template:templates/widgets/socials'
 * @action After the template: 'us_after_template:templates/widgets/socials'
 */
$socials = array(
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

$output = '';

foreach ( $socials as $social_key => $social ) {
	$social_url = us_get_option( 'header_socials_' . $social_key );
	if ( ! $social_url ) {
		continue;
	}

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
		<a class="w-socials-item-link" target="_blank" href="' . $social_url . '">
			<span class="w-socials-item-link-hover"></span>
		</a>
		<div class="w-socials-item-popup">
			<span>' . $social . '</span>
		</div>
	</div>';
}

// Custom icon
$custom_icon = us_get_option( 'header_socials_custom_icon' );
$custom_link = us_get_option( 'header_socials_custom_url' );
if ( ! empty( $custom_icon ) AND ! empty( $custom_link ) ) {
	$output .= '<div class="w-socials-item custom">';
	$output .= '<a class="w-socials-item-link" target="_blank" href="' . esc_url( $custom_link ) . '">';
	$output .= '<span class="w-socials-item-link-hover"></span>';
	$output .= '<i class="' . us_prepare_icon_class( $custom_icon ) . '"></i>';
	$output .= '</a></div>';
}

if ( ! empty( $output ) ) {
	$output = '<div class="w-socials"><div class="w-socials-list">' . $output . '</div></div>';
}

echo $output;
