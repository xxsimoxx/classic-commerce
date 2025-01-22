<?php
/**
 * Cross-sells
 *
 * This template can be overridden by copying it to yourtheme/classic-commerce/cart/cross-sells.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @author  WooThemes
 * @package ClassicCommerce/Templates
 * @version WC-3.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( $cross_sells ) : ?>

	<div class="cross-sells">
    <?php
		$heading = apply_filters( 'woocommerce_product_cross_sells_products_heading', __( 'You may be interested in&hellip;', 'classic-commerce' ) );

		if ( $heading ) :
			?>
			<h2><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>

		<?php woocommerce_product_loop_start(); ?>

			<?php foreach ( $cross_sells as $cross_sell ) : ?>

				<?php
				 	$post_object = get_post( $cross_sell->get_id() );

					setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited, Squiz.PHP.DisallowMultipleAssignments.Found

					wc_get_template_part( 'content', 'product' );
                    ?>

			<?php endforeach; ?>

		<?php woocommerce_product_loop_end(); ?>

	</div>

    <?php
endif;

wp_reset_postdata();
