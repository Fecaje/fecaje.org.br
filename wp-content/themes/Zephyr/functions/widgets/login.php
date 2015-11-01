<?php

class US_Widget_Login extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_login', 'description' => __( 'Login Form', 'us' ) );
		$control_ops = array();
		$this->WP_Widget( 'login', US_THEMENAME . ': ' . __( 'Login', 'us' ), $widget_ops, $control_ops );
	}

	function form( $instance ) {
		$defaults = array(
			'title' => '',
			'register' => '',
			'lostpass' => '',
			'login_redirect' => '',
			'logout_redirect' => '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'us' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>"/></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'register' ); ?>"><?php _e( 'Register URL:', 'us' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'register' ); ?>" name="<?php echo $this->get_field_name( 'register' ); ?>" type="text" value="<?php echo esc_url( $instance['register'] ); ?>"/></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'lostpass' ); ?>"><?php _e( 'Lost Password URL:', 'us' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'lostpass' ); ?>" name="<?php echo $this->get_field_name( 'lostpass' ); ?>" type="text" value="<?php echo esc_url( $instance['lostpass'] ); ?>"/></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'login_redirect' ); ?>"><?php _e( 'Login Redirect URL:', 'us' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'login_redirect' ); ?>" name="<?php echo $this->get_field_name( 'login_redirect' ); ?>" type="text" value="<?php echo esc_url( $instance['login_redirect'] ); ?>"/></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'logout_redirect' ); ?>"><?php _e( 'Logout Redirect URL:', 'us' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'logout_redirect' ); ?>" name="<?php echo $this->get_field_name( 'logout_redirect' ); ?>" type="text" value="<?php echo esc_url( $instance['logout_redirect'] ); ?>"/></label>
		</p>

		<?php
	}

	function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];
		if ( $title ) {
			echo '<h4>' . $title . '</h4>';
		}
		if ( is_user_logged_in() ) {
			global $current_user;
			get_currentuserinfo();

			?>
			<div class="w-profile">
				<a class="w-profile-link for_user" href="<?php echo admin_url( 'profile.php' ); ?>">
					<span class="w-profile-avatar"><?php echo get_avatar( get_current_user_id(), '64' ); ?></span>
					<span class="w-profile-name"><?php echo $current_user->display_name; ?></span>
				</a>
				<a class="w-profile-link for_logout" href="<?php if ( $instance['logout_redirect'] != '' ) {
					echo wp_logout_url( esc_url( $instance['logout_redirect'] ) );
				} else {
					echo wp_logout_url();
				} ?>"><?php echo __( 'Log Out', 'us' ) ?></a>
			</div>
		<?php } else { ?>
			<div class="w-form for_login">
				<form method="post" action="<?php echo site_url( 'wp-login.php' ); ?>" autocomplete="off">
					<?php if ( $instance['login_redirect'] != '' ) { ?>
						<input type="hidden" name="redirect_to" value="<?php echo esc_url( $instance['login_redirect'] ); ?>"><?php } ?>
					<div class="w-form-row">
						<div class="w-form-field">
							<input type="text" name="log">
							<i class="mdfi_social_person"></i>
							<span class="w-form-field-label"><?php _e( 'Username', 'us' ) ?></span>
							<span class="w-form-field-bar"></span>
						</div>
						<div class="w-form-state"></div>
					</div>
					<div class="w-form-row">
						<div class="w-form-field">
							<input type="password" name="pwd">
							<i class="mdfi_action_lock"></i>
							<span class="w-form-field-label"><?php _e( 'Password', 'us' ) ?></span>
							<span class="w-form-field-bar"></span>
						</div>
						<div class="w-form-state"></div>
					</div>
					<div class="w-form-row for_submit">
						<div class="w-form-field">
							<button class="w-btn style_raised color_primary">
								<span><?php _e( 'Log In', 'us' ) ?></span></button>
							<label for="rememberme"><input id="rememberme" type="checkbox" value="forever"
							                               name="rememberme"><?php _e( 'Remember Me', 'us' ) ?></label>
						</div>
					</div>
					<?php if ( $instance['register'] != '' OR $instance['lostpass'] != '' ) { ?>
						<div class="w-form-row for_links">
							<?php if ( $instance['register'] != '' ) { ?>
								<a class="w-form-row-link for_register" href="<?php echo esc_url( $instance['register'] ); ?>"><?php _e( 'Register', 'us' ) ?></a>
							<?php }
							if ( $instance['lostpass'] != '' ) { ?>
								<a class="w-form-row-link for_lostpass" href="<?php echo esc_url( $instance['lostpass'] ); ?>"><?php _e( 'Lost Password', 'us' ) ?></a>
							<?php } ?>
						</div>
					<?php } ?>
				</form>
			</div>
			<?php
		}
		echo $args['after_widget'];
	}
}

add_action( 'widgets_init', 'us_register_login_widget' );

function us_register_login_widget() {
	register_widget( 'US_Widget_Login' );
}
