<?php
/**
 * The Template for displaying products in a product category. Simply includes the archive template
 *
 * This template can be overridden by copying it to yourtheme/classic-commerce/taxonomy-product-cat.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @package ClassicCommerce/Templates
 * @version WC-4.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

wc_get_template( 'archive-product.php' );
