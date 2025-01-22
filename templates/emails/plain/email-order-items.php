<?php
/**
 * Email Order Items (plain)
 *
 * This template can be overridden by copying it to yourtheme/classic-commerce/emails/plain/email-order-items.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @author  WooThemes
 * @package ClassicCommerce/Templates/Emails/Plain
 * @version WC-3.7.0
 */

defined( 'ABSPATH' ) || exit;

foreach ( $items as $item_id => $item ) :
	if ( apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
		$product = $item->get_product();
        $sku           = '';
		$purchase_note = '';

        if ( is_object( $product ) ) {
			$sku           = $product->get_sku();
			$purchase_note = $product->get_purchase_note();
		}
		echo apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false );
        if ( $show_sku && $sku ) {
			echo ' (#' . $sku . ')';
		}
		echo ' X ' . apply_filters( 'woocommerce_email_order_item_quantity', $item->get_quantity(), $item );
		echo ' = ' . $order->get_formatted_line_subtotal( $item ) . "\n";

		// allow other plugins to add additional product information here
		do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, $plain_text );
		echo strip_tags( wc_display_item_meta( $item, array(
			'before'    => "\n- ",
			'separator' => "\n- ",
			'after'     => "",
			'echo'      => false,
			'autop'     => false,
		) ) );

		// allow other plugins to add additional product information here
		do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, $plain_text );
	}
	// Note
	if ( $show_purchase_note && $purchase_note ) {
		echo "\n" . do_shortcode( wp_kses_post( $purchase_note ) );
	}
	echo "\n\n";
endforeach;
