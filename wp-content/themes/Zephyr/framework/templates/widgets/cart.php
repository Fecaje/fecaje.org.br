<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output WooCommerce shopping cart
 *
 * (!) Important: this file is not intended to be overloaded, so use the below hooks for customizing instead
 *
 * @action Before the template: 'us_before_template:templates/widgets/cart'
 * @action After the template: 'us_after_template:templates/widgets/cart'
 */

if ( ! class_exists( 'woocommerce' ) )
	return;

global $woocommerce;
$cart_subtotal = empty($woocommerce->cart->cart_contents_total) ? 0 : $woocommerce->cart->cart_contents_total;
$link = $woocommerce->cart->get_cart_url();
?><!-- CART -->
<div class="w-cart<?php if ($woocommerce->cart->cart_contents_count) echo ' has_items'; ?>">
	<div class="w-cart-h">
		<a class="w-cart-link" href="<?php echo $link; ?>">
			<span class="w-cart-quantity"><?php echo $woocommerce->cart->cart_contents_count; ?></span>
		</a>
		<div class="w-cart-notification">
			<span class="product-name"><?php echo __('Product', 'us'); ?></span>
			<?php echo __('was successfully added to your cart', 'us'); ?>
		</div>
		<div class="w-cart-dropdown">
			<?php the_widget('WC_Widget_Cart'); ?>
		</div>
	</div>
</div><?php
