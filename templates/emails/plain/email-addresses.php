<?php
/**
 * Email Addresses (plain)
 *
 * This template can be overridden by copying it to yourtheme/classic-commerce/emails/plain/email-addresses.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @package ClassicCommerce/Templates/Emails/Plain
 * @version WC-5.6.0
 */

defined( 'ABSPATH' ) || exit;

echo "\n" . esc_html( wc_strtoupper( esc_html__( 'Billing address', 'classic-commerce' ) ) ) . "\n\n";
echo preg_replace( '#<br\s*/?>#i', "\n", $order->get_formatted_billing_address() ) . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

if ( $order->get_billing_phone() ) {
	echo $order->get_billing_phone() . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

if ( $order->get_billing_email() ) {
	echo $order->get_billing_email() . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() ) {
	$shipping = $order->get_formatted_shipping_address();

	if ( $shipping ) {
		echo "\n" . esc_html( wc_strtoupper( esc_html__( 'Shipping address', 'classic-commerce' ) ) ) . "\n\n";
		echo preg_replace( '#<br\s*/?>#i', "\n", $shipping ) . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        if ( $order->get_shipping_phone() ) {
			echo $order->get_shipping_phone() . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}
