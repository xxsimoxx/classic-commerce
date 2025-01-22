<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/classic-commerce/single-product/related.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @author  WooThemes
 * @package ClassicCommerce/Templates
 * @version WC-3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $related_products ) : ?>

	<section class="related products">

		<?php
		$heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'Related products', 'classic-commerce' ) );

		if ( $heading ) :
			?>
			<h2><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>

		<?php woocommerce_product_loop_start(); ?>

			<?php foreach ( $related_products as $related_product ) : ?>

				<?php
				 	$post_object = get_post( $related_product->get_id() );

					setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

					wc_get_template_part( 'content', 'product' );
                ?>

			<?php endforeach; ?>

		<?php woocommerce_product_loop_end(); ?>

	</section>

    <?php
endif;

wp_reset_postdata();
