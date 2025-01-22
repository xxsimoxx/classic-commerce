<?php
/**
 * Displayed when no products are found matching the current query
 *
 * This template can be overridden by copying it to yourtheme/classic-commerce/loop/no-products-found.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @author  WooThemes
 * @package ClassicCommerce/Templates
 * @version WC-7.8.0
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="woocommerce-no-products-found">
	<?php wc_print_notice( esc_html__( 'No products were found matching your selection.', 'classic-commerce' ), 'notice' ); ?>
</div>
