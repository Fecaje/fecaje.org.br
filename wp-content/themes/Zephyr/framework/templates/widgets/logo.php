<?php defined('ABSPATH') OR die('This script cannot be accessed directly.');

/**
 * Header logo
 *
 * (!) Important: this file is not intended to be overloaded, so use the below hooks for customizing instead
 *
 * @action Before the template: 'us_before_template:templates/widgets/contacts'
 * @action After the template: 'us_after_template:templates/widgets/contacts'
 */

$class_name = '';
if ( us_get_option( 'logo_type', 'text' ) == 'text' ) {
	$class_name .= ' with_title';
	$logo_text = us_get_option( 'logo_text' ) ? us_get_option( 'logo_text' ) : bloginfo( 'name' );
}else{
	if ( us_get_option( 'logo_image_transparent' ) ) {
		$class_name = ' with_transparent';
	}
	$default_logo_url = us_get_option( 'logo_image' );
	$transparent_logo_url = us_get_option( 'logo_image_transparent' );
}
$home_url = function_exists( 'icl_get_home_url' ) ? icl_get_home_url() : esc_url( home_url( '/' ) );
?>

<div class="w-logo <?php echo $class_name ?>">
	<a class="w-logo-link" href="<?php echo $home_url ?>">
<?php if ( us_get_option( 'logo_type', 'text' ) == 'img' ): ?>
		<span class="w-logo-img">
<?php if ( $default_logo_url ): ?>
			<img class="for_default" src="<?php echo esc_url( $default_logo_url ); ?>" alt="<?php bloginfo('name'); ?>">
<?php endif; ?>
<?php if ( $transparent_logo_url ): ?>
			<img class="for_transparent" src="<?php echo esc_url( $transparent_logo_url ); ?>" alt="<?php bloginfo('name'); ?>">
<?php endif; ?>
		</span>
<?php else: ?>
		<span class="w-logo-title"><?php echo $logo_text ?></span>
<?php endif; ?>
	</a>
</div>
