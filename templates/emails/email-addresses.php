<?php
/**
 * Email Addresses
 *
 * This template can be overridden by copying it to yourtheme/classic-commerce/emails/email-addresses.php.
 *
 * @see https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @author WooThemes
 * @package ClassicCommerce/Templates/Emails
 * @version WC-8.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$text_align = is_rtl() ? 'right' : 'left';
$address    = $order->get_formatted_billing_address();
$shipping   = $order->get_formatted_shipping_address();
?><table id="addresses" cellspacing="0" cellpadding="0" style="width: 100%; vertical-align: top; margin-bottom: 40px; padding:0;" border="0">
	<tr>
		<td style="text-align:<?php echo esc_attr( $text_align ); ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; border:0; padding:0;" valign="top" width="50%">
			<h2><?php esc_html_e( 'Billing address', 'woocommerce' ); ?></h2>
			<address class="address">
				<?php echo wp_kses_post( $address ? $address : esc_html__( 'N/A', 'classic-commerce' ) ); ?>
				<?php if ( $order->get_billing_phone() ) : ?>
					<br/><?php echo wc_make_phone_clickable( $order->get_billing_phone() ); ?>
				<?php endif; ?>
				<?php if ( $order->get_billing_email() ) : ?>
					<br/><?php echo esc_html( $order->get_billing_email() ); ?>
				<?php endif; ?>
				<?php
				/**
				 * Fires after the core address fields in emails.
				 *
				 * @since WC-8.6.0
				 *
				 * @param string $type Address type. Either 'billing' or 'shipping'.
				 * @param WC_Order $order Order instance.
				 * @param bool $sent_to_admin If this email is being sent to the admin or not.
				 * @param bool $plain_text If this email is plain text or not.
				 */
				do_action( 'woocommerce_email_customer_address_section', 'billing', $order, $sent_to_admin, false );
				?>
			</address>
		</td>
		<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && $shipping ) : ?>
			<td style="text-align:<?php echo esc_attr( $text_align ); ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; padding:0;" valign="top" width="50%">
				<h2><?php esc_html_e( 'Shipping address', 'classic-commerce' ); ?></h2>
				<address class="address">
					<?php echo wp_kses_post( $shipping ); ?>
					<?php if ( $order->get_shipping_phone() ) : ?>
						<br /><?php echo wc_make_phone_clickable( $order->get_shipping_phone() ); ?>
					<?php endif; ?>
					<?php
					/**
					 * Fires after the core address fields in emails.
					 *
					 * @since WC-8.6.0
					 *
					 * @param string $type Address type. Either 'billing' or 'shipping'.
					 * @param WC_Order $order Order instance.
					 * @param bool $sent_to_admin If this email is being sent to the admin or not.
					 * @param bool $plain_text If this email is plain text or not.
					 */
					do_action( 'woocommerce_email_customer_address_section', 'shipping', $order, $sent_to_admin, false );
					?>
				</address>
			</td>
		<?php endif; ?>
	</tr>
</table>
