<?php
/**
 * Show messages
 *
 * This template can be overridden by copying it to yourtheme/classic-commerce/notices/success.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @package ClassicCommerce/Templates
 * @version WC-3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $notices ) {
	return;
}

?>

<?php foreach ( $notices as $notice ) : ?>
	<div class="woocommerce-message"<?php echo wc_get_notice_data_attr( $notice ); ?> role="alert">
		<?php echo wc_kses_notice( $notice['notice'] ); ?>
	</div>
<?php endforeach; ?>
