<?php
/**
 * Admin View: Notice - Updating
 *
 * @package ClassicCommerce\Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cron_disabled       = defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON;
?>
<div id="message" class="updated woocommerce-message wc-connect">
	<p>
		<strong><?php esc_html_e( 'Classic Commerce database update', 'classic-commerce' ); ?></strong><br>
		<?php esc_html_e( 'Classic Commerce is updating the database in the background. The database update process may take a little while, so please be patient.', 'classic-commerce' ); ?>
		<?php
		if ( $cron_disabled ) {
			echo '<br>' . esc_html__( 'Note: WP CRON has been disabled on your install which may prevent this update from completing.', 'classic-commerce' );
		}
		?>
	</p>
</div>
