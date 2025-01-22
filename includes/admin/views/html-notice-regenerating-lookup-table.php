<?php
/**
 * Admin View: Notice - Regenerating product lookup table.
 *
 * @package ClassicCommerce/admin
 */

defined( 'ABSPATH' ) || exit;

$cron_disabled       = defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON;
?>
<div id="message" class="updated woocommerce-message">
	<p>
        <strong><?php esc_html_e( 'Classic Commerce is updating product data in the background', 'classic-commerce' ); ?></strong><br>
        <?php
        esc_html_e( 'Product display, sorting, and reports may not be accurate until this finishes. It will take a few minutes and this notice will disappear when complete.', 'classic-commerce' );

		if ( $cron_disabled ) {
			echo '<br>' . esc_html__( 'Note: WP CRON has been disabled on your install which may prevent this update from completing.', 'classic-commerce' );
		}
		?>
	</p>
</div>