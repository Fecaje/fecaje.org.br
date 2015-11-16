<?php

add_action( 'admin_menu', 'us_add_info_home_page', 9 );
function us_add_info_home_page() {
	add_menu_page( US_THEMENAME . ': Home', US_THEMENAME, 'manage_options', 'us-home', 'us_welcome_page', NULL, '59.001' );
	add_submenu_page( 'us-home', US_THEMENAME . ': Home', __( 'About the Theme', 'us' ), 'manage_options', 'us-home', 'us_welcome_page', 11 );
}

function us_welcome_page() {
	$help_domain = 'https://help.us-themes.com';
	?>
	<div class="wrap about-wrap us-body">

		<div class="us-header">
			<h1><?php echo sprintf( __( 'Welcome to <strong>%s</strong>', 'us' ), US_THEMENAME . ' ' . US_THEMEVERSION ) ?></h1>

			<div class="about-text">
				<strong><?php _e( 'The Latest Design Trend with Great Attention to Details.', 'us' ) ?></strong>
				<?php _e( 'We made every small element, motion and interaction so neat and lovely, that you can entirely focus on the big picture. Hope you\'ll enjoy it the same as we do!', 'us' ) ?>
			</div>
		</div>

		<div class="us-content">
			<div class="feature-section us-cols">
				<div class="one-third">
					<h4><i class="dashicons dashicons-download"></i><?php _ex( 'Import Demo Content', 'noun', 'us' ) ?></h4>

					<p><?php _e( 'If you have installed this theme for the first time, you can import demo content. It will be a good start to build your site.', 'us' ) ?></p>
					<a class="button us-button" href="<?php echo admin_url( 'admin.php?page=us-demo-import' ); ?>">
						<?php _e( 'Go to Demo Import', 'us' ) ?></a>
				</div>
				<div class="one-third">
					<h4><i class="dashicons dashicons-admin-appearance"></i><?php _e( 'Customize Appearance', 'us' ) ?>
					</h4>

					<p><?php _e( 'If you\'re looking to customize the look and feel of your site (colors, layouts, display options), just go to the Theme Options panel.', 'us' ) ?></p>
					<a class="button us-button" href="<?php echo admin_url( 'admin.php?page=us-theme-options' ); ?>"><?php _e( 'Go to Theme Options', 'us' ) ?></a>
				</div>
				<div class="one-third">
					<h4><i class="dashicons dashicons-admin-network"></i><?php _e( 'Validate Your Theme', 'us' ) ?></h4>

					<p><?php _e( 'For more convenient work validate your theme, which unlocks automatic updates, additional extensions, and more.', 'us' ) ?></p>

					<div class="us-feature-disabled"><?php _e( 'Coming Soon!', 'us' ) ?></div>
					<!-- <a class="button us-button" href="<?php echo admin_url( 'admin.php?page=us-product-validation' ); ?>"><?php _e( 'Go to Product Validation', 'us' ) ?></a> -->
				</div>
			</div>
			<div class="feature-section us-cols">
				<div class="one-third">
					<h4>
						<i class="dashicons dashicons-editor-help"></i><?php echo sprintf( __( '%s Knowledge Base', 'us' ), US_THEMENAME ) ?>
					</h4>

					<p><?php echo sprintf( __( '%s has an extensive well structured documentation as a separate site, which is constantly being improved and replenished.', 'us' ), US_THEMENAME ) ?></p>
					<a class="button us-button" href="<?php echo $help_domain ?>/<?php echo strtolower( US_THEMENAME ) ?>/" target="_blank">
						<?php _e( 'Go to Knowledge Base', 'us' ) ?></a>
				</div>
				<div class="one-third">
					<h4><i class="dashicons dashicons-sos"></i><?php _e( 'Support Portal', 'us' ) ?></h4>

					<p><?php _e( 'If you need some help with the theme, just register at our support portal and create a ticket. We are really proud of our support.', 'us' ) ?></p>
					<a class="button us-button" href="<?php echo $help_domain ?>/<?php echo strtolower( US_THEMENAME ) ?>/tickets/" target="_blank">
						<?php _e( 'Go to Support Portal', 'us' ) ?></a>
				</div>
				<div class="one-third">
					<h4><i class="dashicons dashicons-clock"></i><?php _e( 'Theme Changelog', 'us' ) ?></h4>

					<p><?php _e( 'To see whats new the latest version has, go to the changelog page. Also it\'s a best way to see how the theme evolves from initial release.', 'us' ) ?></p>
					<a class="button us-button" href="<?php echo $help_domain ?>/<?php echo strtolower( US_THEMENAME ) ?>/changelog/" target="_blank">
						<?php _e( 'View the Changelog', 'us' ) ?></a>
				</div>
			</div>
		</div>

	</div>
	<?php
}
