<?php
/**
 * Admin View: Notice - PHP & WP minimum requirements.
 *
 * @package ClassicCommerce\Admin\Notices
 */

defined( 'ABSPATH' ) || exit;
?>
<div id="message" class="updated woocommerce-message">
	<a class="woocommerce-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'wc-hide-notice', WC_PHP_MIN_REQUIREMENTS_NOTICE ), 'woocommerce_hide_notices_nonce', '_wc_notice_nonce' ) ); ?>"><?php esc_html_e( 'Dismiss', 'classic-commerce' ); ?></a>

	<p>
		<?php
		echo wp_kses_post(
			sprintf(
				$msg . '<p><a href="%s" class="button button-primary">' . __( 'Learn how to upgrade', 'classic-commerce' ) . '</a></p>',
				add_query_arg(
					array(
						'utm_source'   => 'wpphpupdatebanner',
						'utm_medium'   => 'product',
						'utm_campaign' => 'woocommerceplugin',
						'utm_content'  => 'docs',
					),
					'https://classiccommerce.cc/docs/usage-and-maintenance/update-php-version/'
				)
			)
		);
		?>
	</p>
</div>