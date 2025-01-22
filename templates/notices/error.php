<?php
/**
 * Show error messages
 *
 * This template can be overridden by copying it to yourtheme/classic-commerce/notices/error.php.
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
<ul class="woocommerce-error" role="alert">
	<?php foreach ( $notices as $notice ) : ?>
		<li<?php echo wc_get_notice_data_attr( $notice ); ?>>
            <?php echo wc_kses_notice( $notice['notice'] ); ?>
		</li>
	<?php endforeach; ?>
</ul>
