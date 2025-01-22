<?php
/**
 * Loop Rating
 *
 * This template can be overridden by copying it to yourtheme/classic-commerce/loop/rating.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @author  WooThemes
 * @package ClassicCommerce/Templates
 * @version WC-3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( ! wc_review_ratings_enabled() ) {
	return;
}

echo wc_get_rating_html( $product->get_average_rating() ); // WordPress.XSS.EscapeOutput.OutputNotEscaped.
