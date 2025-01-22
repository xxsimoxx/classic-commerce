<?php
/**
 * REST API Customer Downloads controller
 *
 * Handles requests to the /customers/<customer_id>/downloads endpoint.
 *
 * @package ClassicCommerce\RestApi
 * @since   2.6.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * REST API Customers controller class.
 *
 * @package ClassicCommerce\RestApi
 * @extends WC_REST_Customer_Downloads_V2_Controller
 */
class WC_REST_Customer_Downloads_Controller extends WC_REST_Customer_Downloads_V2_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'wc/v3';
}
