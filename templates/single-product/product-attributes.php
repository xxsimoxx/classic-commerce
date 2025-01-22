<?php
/**
 * Product attributes
 *
 * Used by list_attributes() in the products class.
 *
 * This template can be overridden by copying it to yourtheme/classic-commerce/single-product/product-attributes.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @author  WooThemes
 * @package ClassicCommerce/Templates
 * @version WC-9.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $product_attributes ) {
	return;
}
?>
<table class="woocommerce-product-attributes shop_attributes" aria-label="<?php esc_attr_e( 'Product Details', 'classic-commerce' ); ?>">
	<?php foreach ( $product_attributes as $product_attribute_key => $product_attribute ) : ?>
		<tr class="woocommerce-product-attributes-item woocommerce-product-attributes-item--<?php echo esc_attr( $product_attribute_key ); ?>">
			<th class="woocommerce-product-attributes-item__label" scope="row"><?php echo wp_kses_post( $product_attribute['label'] ); ?></th>
			<td class="woocommerce-product-attributes-item__value"><?php echo wp_kses_post( $product_attribute['value'] ); ?></td>
		</tr>
	<?php endforeach; ?>
</table>
