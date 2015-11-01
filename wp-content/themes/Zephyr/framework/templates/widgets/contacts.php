<?php defined('ABSPATH') OR die('This script cannot be accessed directly.');

/**
 * Output header contacts block
 *
 * (!) Important: this file is not intended to be overloaded, so use the below hooks for customizing instead
 *
 * @action Before the template: 'us_before_template:templates/widgets/contacts'
 * @action After the template: 'us_after_template:templates/widgets/contacts'
 */
?>

<div class="w-contacts">
	<div class="w-contacts-list">
	<?php if ( us_get_option( 'header_contacts_phone' ) ): ?>
		<div class="w-contacts-item for_phone">
			<span class="w-contacts-item-value"><?php echo wp_kses( us_get_option( 'header_contacts_phone' ), '<a><strong><span>' ) ?></span>
		</div>
	<?php endif; /* header phone */ ?>
	<?php if ( us_get_option( 'header_contacts_email' ) ): ?>
		<div class="w-contacts-item for_email">
			<span class="w-contacts-item-value">
				<a href="mailto:<?php echo sanitize_email( us_get_option( 'header_contacts_email' ) ) ?>">
					<?php echo us_get_option( 'header_contacts_email' ) ?>
				</a>
			</span>
		</div>
	<?php endif; /* header email */ ?>
	<?php if ( us_get_option( 'header_contacts_custom_text' ) ): ?>
		<div class="w-contacts-item for_custom">
			<?php
			if ( us_get_option( 'header_contacts_custom_icon' ) ) {
				?><i class="<?php echo us_prepare_icon_class( us_get_option( 'header_contacts_custom_icon' ) ) ?>"></i><?php
			}
			?>
			<span class="w-contacts-item-value"><?php echo wp_kses( us_get_option( 'header_contacts_custom_text' ), '<a><strong><span>') ?></span>
		</div>
	<?php endif; /* header custom text */ ?>
	</div>
</div>

