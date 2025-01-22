<?php
/**
 * "Order received" message.
 *
 * This template can be overridden by copying it to yourtheme/classic-commerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion Classic Commerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @package ClassicCommerce\Templates
 * @version WC-8.3.0
 *
 * @var WC_Order|false $order
 */

defined( 'ABSPATH' ) || exit;
?>

<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
	<?php
	/**
	 * Filter the message shown after a checkout is complete.
	 *
	 * @since 2.2.0
	 *
	 * @param string         $message The message.
	 * @param WC_Order|false $order   The order created during checkout, or false if order data is not available.
	 */
	$message = apply_filters(
		'woocommerce_thankyou_order_received_text',
		esc_html( __( 'Thank you. Your order has been received.', 'classic-commerce' ) ),
		$order
	);

    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo $message;
	?>
</p>