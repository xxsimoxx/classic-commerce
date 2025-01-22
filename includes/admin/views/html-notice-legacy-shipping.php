<?php
/**
 * Admin View: Notice - Legacy Shipping.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="message" class="updated woocommerce-message">
	<a class="woocommerce-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'wc-hide-notice', 'legacy_shipping' ), 'woocommerce_hide_notices_nonce', '_wc_notice_nonce' ) ); ?>">
		<?php esc_html_e( 'Dismiss', 'classic-commerce' ); ?>
	</a>

    <p class="main">
		<strong><?php esc_html_e( 'New:', 'woocommerce' ); ?> <?php esc_html_e( 'Shipping zones', 'classic-commerce' ); ?></strong> &#8211; <?php esc_html_e( 'a group of regions that can be assigned different shipping methods and rates.', 'classic-commerce' ); ?>
	</p>
	<p>
		<?php esc_html_e( 'Legacy shipping methods (flat rate, international flat rate, local pickup and delivery, and free shipping) are deprecated but will continue to work as normal for now. <b><em>They will be removed in future versions of WooCommerce</em></b>. We recommend disabling these and setting up new rates within shipping zones as soon as possible.', 'classic-commerce' ); ?>
	</p>

	<p class="submit">
		<?php if ( empty( $_GET['page'] ) || empty( $_GET['tab'] ) || 'wc-settings' !== $_GET['page'] || 'shipping' !== $_GET['tab'] ) : ?>
			<a class="button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=shipping' ) ); ?>">
				<?php esc_html_e( 'Setup shipping zones', 'classic-commerce' ); ?>
			</a>
		<?php endif; ?>
		<a class="button-secondary" href="https://classiccommerce.cc/docs/installation-and-setup/shipping/"><?php esc_html_e( 'Learn more about shipping zones', 'classic-commerce' ); ?></a>
	</p>
</div>
