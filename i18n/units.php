<?php
/**
 * Units
 *
 * Returns a multidimensional array of measurement units and their labels.
 * Unit labels should be defined in English and translated native through localization files.
 *
 * @package WooCommerce\i18n
 * @version
 */

defined( 'ABSPATH' ) || exit;

return array(
	'weight'     => array(
		'kg'  => __( 'kg', 'classic-commerce' ),
		'g'   => __( 'g', 'classic-commerce' ),
		'lbs' => __( 'lbs', 'classic-commerce' ),
		'oz'  => __( 'oz', 'classic-commerce' ),
	),
	'dimensions' => array(
		'm'  => __( 'm', 'classic-commerce' ),
		'cm' => __( 'cm', 'classic-commerce' ),
		'mm' => __( 'mm', 'classic-commerce' ),
		'in' => __( 'in', 'classic-commerce' ),
		'yd' => __( 'yd', 'classic-commerce' ),
	),
);
