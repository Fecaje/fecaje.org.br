<?php

class US_Widget_Socials extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_socials', 'description' => __( 'Social Links', 'us' ) );
		$control_ops = array();
		$this->WP_Widget( 'socials', US_THEMENAME . ': ' . __( 'Social Links', 'us' ), $widget_ops, $control_ops );
	}

	function form( $instance ) {
		$defaults = array(
			'title' => '',
			'size' => '',
			'email' => '',
			'facebook' => '',
			'twitter' => '',
			'google' => '',
			'linkedin' => '',
			'youtube' => '',
			'vimeo' => '',
			'flickr' => '',
			'instagram' => '',
			'behance' => '',
			'xing' => '',
			'pinterest' => '',
			'skype' => '',
			'tumblr' => '',
			'dribbble' => '',
			'vk' => '',
			'soundcloud' => '',
			'yelp' => '',
			'twitch' => '',
			'deviantart' => '',
			'foursquare' => '',
			'github' => '',
			'odnoklassniki' => '',
			's500px' => '',
			'houzz' => '',
			'rss' => '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		// Compatibility with the previous theme version
		if ( isset( $instance['size'] ) ) {
			if ( $instance['size'] == 'normal' ) {
				$instance['size'] = 'medium';
			} elseif ( $instance['size'] == 'big' ) {
				$instance['size'] = 'large';
			}
		}
		$instance['style'] = isset( $instance['style'] ) ? $instance['style'] : '1';

		$output = '';

		$output .= '<p><label for="' . esc_attr( $this->get_field_id( 'title' ) ) . '">' . __( 'Title', 'us' ) . ':</label>';
		$output .= '<input class="widefat" id="' . esc_attr( $this->get_field_id( 'title' ) ) . '" ';
		$output .= 'name="' . esc_attr( $this->get_field_name( 'title' ) ) . '" type="text" value="' . esc_attr( $instance['title'] ) . '" /></p>';
		$output .= '<p><label for="' . esc_attr( $this->get_field_id( 'size' ) ) . '">' . __( 'Size', 'us' ) . ':</label>';
		$output .= '<select name="' . esc_attr( $this->get_field_name( 'size' ) ) . '" id="' . esc_attr( $this->get_field_id( 'size' ) ) . '" class="widefat">';
		$output .= '<option value="medium"' . selected( $instance['size'], 'medium', FALSE ) . '>' . __( 'Medium', 'us' ) . '</option>';
		$output .= '<option value="small"' . selected( $instance['size'], 'small', FALSE ) . '>' . __( 'Small', 'us' ) . '</option>';
		$output .= '<option value="large"' . selected( $instance['size'], 'large', FALSE ) . '>' . __( 'Large', 'us' ) . '</option>';
		$output .= '</select></p>';
		$output .= '<p><label for="' . esc_attr( $this->get_field_id( 'style' ) ) . '">' . __( 'Style', 'us' ) . ':</label>';
		$output .= '<select name="' . esc_attr( $this->get_field_name( 'style' ) ) . '" id="' . esc_attr( $this->get_field_id( 'style' ) ) . '" class="widefat">';
		$output .= '<option value="1"' . selected( $instance['style'], '1', FALSE ) . '>' . __( 'Normal', 'us' ) . '</option>';
		$output .= '<option value="2"' . selected( $instance['style'], '2', FALSE ) . '>' . __( 'Inverted', 'us' ) . '</option>';
		$output .= '<option value="3"' . selected( $instance['style'], '3', FALSE ) . '>' . __( 'Desaturated', 'us' ) . '</option>';
		$output .= '<option value="4"' . selected( $instance['style'], '4', FALSE ) . '>' . __( 'Inverted and Desaturated', 'us' ) . '</option>';
		$output .= '</select></p>';

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

		foreach ( $socials as $social_key => $social ) {
			$output .= '<p><label for="' . esc_attr( $this->get_field_id( $social_key ) ) . '">' . $social . ':</label>';
			$output .= '<input class="widefat" id="' . esc_attr( $this->get_field_id( $social_key ) ) . '" name="' . esc_attr( $this->get_field_name( $social_key ) ) . '" ';
			$output .= 'type="text" value="' . esc_attr( $instance[ $social_key ] ) . '" /></p>';
		}

		echo $output;
	}

	function widget( $args, $instance ) {
		// Compatibility with the previous theme version
		if ( isset( $instance['size'] ) ) {
			if ( $instance['size'] == 'normal' ) {
				$instance['size'] = 'medium';
			} elseif ( $instance['size'] == 'big' ) {
				$instance['size'] = 'large';
			}
		}
		$instance['style'] = isset( $instance['style'] ) ? $instance['style'] : '1';

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

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

		echo $args['before_widget'];
		if ( $title ) {
			echo '<h4>' . $title . '</h4>';
		}
		$output = '<div class="w-socials size_' . $instance['size'] . ' style_' . $instance['style'] . '">
			<div class="w-socials-h">
				<div class="w-socials-list">';

		foreach ( $socials as $social_key => $social ) {
			if ( empty( $instance[ $social_key ] ) ) {
				continue;
			}
			$social_url = $instance[ $social_key ];
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

		$output .= '</div></div></div>';

		echo $output;
		echo $args['after_widget'];
	}
}

add_action( 'widgets_init', 'us_register_socials_widget' );

function us_register_socials_widget() {
	register_widget( 'US_Widget_Socials' );
}
