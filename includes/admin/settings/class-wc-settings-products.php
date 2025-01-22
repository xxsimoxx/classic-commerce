<?php
/**
 * Classic Commerce Product Settings
 *
 * @package ClassicCommerce\Admin
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WC_Settings_Products', false ) ) {
	return new WC_Settings_Products();
}

/**
 * WC_Settings_Products.
 */
class WC_Settings_Products extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'products';
		$this->label = __( 'Products', 'classic-commerce' );

		parent::__construct();
	}

	/**
	 * Get own sections.
	 *
	 * @return array
	 */
	protected function get_own_sections() {
		return array(
			''             => __( 'General', 'classic-commerce' ),
			'inventory'    => __( 'Inventory', 'classic-commerce' ),
			'downloadable' => __( 'Downloadable products', 'classic-commerce' ),
		);
	}

	/**
	 * Get settings for the detault section.
	 *
	 * @return array
	 */
	protected function get_settings_for_default_section() {
		$settings =
			array(
				array(
					'title' => __( 'Shop pages', 'classic-commerce' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'catalog_options',
				),
				array(
					'title'    => __( 'Shop page', 'classic-commerce' ),
					/* translators: %s: URL to settings. */
					'desc'     => sprintf( __( 'The base page can also be used in your <a href="%s">product permalinks</a>.', 'classic-commerce' ), admin_url( 'options-permalink.php' ) ),
					'id'       => 'woocommerce_shop_page_id',
					'type'     => 'single_select_page',
					'default'  => '',
					'class'    => 'wc-enhanced-select-nostd',
					'css'      => 'min-width:300px;',
					'desc_tip' => __( 'This sets the base page of your shop - this is where your product archive will be.', 'classic-commerce' ),
				),
				array(
					'title'         => __( 'Add to cart behaviour', 'classic-commerce' ),
					'desc'          => __( 'Redirect to the cart page after successful addition', 'classic-commerce' ),
					'id'            => 'woocommerce_cart_redirect_after_add',
					'default'       => 'no',
					'type'          => 'checkbox',
					'checkboxgroup' => 'start',
				),
				array(
					'desc'          => __( 'Enable AJAX add to cart buttons on archives', 'classic-commerce' ),
					'id'            => 'woocommerce_enable_ajax_add_to_cart',
					'default'       => 'yes',
					'type'          => 'checkbox',
					'checkboxgroup' => 'end',
				),
				array(
					'title'       => __( 'Placeholder image', 'classic-commerce' ),
					'id'          => 'woocommerce_placeholder_image',
					'type'        => 'text',
					'default'     => '',
					'class'       => '',
					'css'         => '',
					'placeholder' => __( 'Enter attachment ID or URL to an image', 'classic-commerce' ),
					'desc_tip'    => __( 'This is the attachment ID, or image URL, used for placeholder images in the product catalog. Products with no image will use this.', 'classic-commerce' ),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'catalog_options',
				),

				array(
					'title' => __( 'Measurements', 'classic-commerce' ),
					'type'  => 'title',
					'id'    => 'product_measurement_options',
				),

				array(
					'title'    => __( 'Weight unit', 'classic-commerce' ),
					'desc'     => __( 'This controls what unit you will define weights in.', 'classic-commerce' ),
					'id'       => 'woocommerce_weight_unit',
					'class'    => 'wc-enhanced-select',
					'css'      => 'min-width:300px;',
					'default'  => 'kg',
					'type'     => 'select',
					'options'  => array(
						'kg'  => __( 'kg', 'classic-commerce' ),
						'g'   => __( 'g', 'classic-commerce' ),
						'lbs' => __( 'lbs', 'classic-commerce' ),
						'oz'  => __( 'oz', 'classic-commerce' ),
					),
					'desc_tip' => true,
				),

				array(
					'title'    => __( 'Dimensions unit', 'classic-commerce' ),
					'desc'     => __( 'This controls what unit you will define lengths in.', 'classic-commerce' ),
					'id'       => 'woocommerce_dimension_unit',
					'class'    => 'wc-enhanced-select',
					'css'      => 'min-width:300px;',
					'default'  => 'cm',
					'type'     => 'select',
					'options'  => array(
						'm'  => __( 'm', 'classic-commerce' ),
						'cm' => __( 'cm', 'classic-commerce' ),
						'mm' => __( 'mm', 'classic-commerce' ),
						'in' => __( 'in', 'classic-commerce' ),
						'yd' => __( 'yd', 'classic-commerce' ),
					),
					'desc_tip' => true,
				),

				array(
					'type' => 'sectionend',
					'id'   => 'product_measurement_options',
				),

				array(
					'title' => __( 'Reviews', 'classic-commerce' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'product_rating_options',
				),

				array(
					'title'           => __( 'Enable reviews', 'classic-commerce' ),
					'desc'            => __( 'Enable product reviews', 'classic-commerce' ),
					'id'              => 'woocommerce_enable_reviews',
					'default'         => 'yes',
					'type'            => 'checkbox',
					'checkboxgroup'   => 'start',
					'show_if_checked' => 'option',
				),

				array(
					'desc'            => __( 'Show "verified owner" label on customer reviews', 'classic-commerce' ),
					'id'              => 'woocommerce_review_rating_verification_label',
					'default'         => 'yes',
					'type'            => 'checkbox',
					'checkboxgroup'   => '',
					'show_if_checked' => 'yes',
					'autoload'        => false,
				),

				array(
					'desc'            => __( 'Reviews can only be left by "verified owners"', 'classic-commerce' ),
					'id'              => 'woocommerce_review_rating_verification_required',
					'default'         => 'no',
					'type'            => 'checkbox',
					'checkboxgroup'   => 'end',
					'show_if_checked' => 'yes',
					'autoload'        => false,
				),

				array(
					'title'           => __( 'Product ratings', 'classic-commerce' ),
					'desc'            => __( 'Enable star rating on reviews', 'classic-commerce' ),
					'id'              => 'woocommerce_enable_review_rating',
					'default'         => 'yes',
					'type'            => 'checkbox',
					'checkboxgroup'   => 'start',
					'show_if_checked' => 'option',
				),

				array(
					'desc'            => __( 'Star ratings should be required, not optional', 'classic-commerce' ),
					'id'              => 'woocommerce_review_rating_required',
					'default'         => 'yes',
					'type'            => 'checkbox',
					'checkboxgroup'   => 'end',
					'show_if_checked' => 'yes',
					'autoload'        => false,
				),

				array(
					'type' => 'sectionend',
					'id'   => 'product_rating_options',
				),
			);

		$settings = apply_filters( 'woocommerce_products_general_settings', $settings );
		return apply_filters( 'woocommerce_product_settings', $settings );
	}

	/**
	 * Get settings for the inventory section.
	 *
	 * @return array
	 */
	protected function get_settings_for_inventory_section() {
		$settings =
			array(
				array(
					'title' => __( 'Inventory', 'classic-commerce' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'product_inventory_options',
				),

				array(
					'title'   => __( 'Manage stock', 'classic-commerce' ),
					'desc'    => __( 'Enable stock management', 'classic-commerce' ),
					'id'      => 'woocommerce_manage_stock',
					'default' => 'yes',
					'type'    => 'checkbox',
				),

				array(
					'title'             => __( 'Hold stock (minutes)', 'classic-commerce' ),
					'desc'              => __( 'Hold stock (for unpaid orders) for x minutes. When this limit is reached, the pending order will be cancelled. Leave blank to disable.', 'classic-commerce' ),
					'id'                => 'woocommerce_hold_stock_minutes',
					'type'              => 'number',
					'custom_attributes' => array(
						'min'  => 0,
						'step' => 1,
					),
					'css'               => 'width: 80px;',
					'default'           => '60',
					'autoload'          => false,
					'class'             => 'manage_stock_field',
				),

				array(
					'title'         => __( 'Notifications', 'classic-commerce' ),
					'desc'          => __( 'Enable low stock notifications', 'classic-commerce' ),
					'id'            => 'woocommerce_notify_low_stock',
					'default'       => 'yes',
					'type'          => 'checkbox',
					'checkboxgroup' => 'start',
					'autoload'      => false,
					'class'         => 'manage_stock_field',
				),

				array(
					'desc'          => __( 'Enable out of stock notifications', 'classic-commerce' ),
					'id'            => 'woocommerce_notify_no_stock',
					'default'       => 'yes',
					'type'          => 'checkbox',
					'checkboxgroup' => 'end',
					'autoload'      => false,
					'class'         => 'manage_stock_field',
				),

				array(
					'title'    => __( 'Notification recipient(s)', 'classic-commerce' ),
					'desc'     => __( 'Enter recipients (comma separated) that will receive this notification.', 'classic-commerce' ),
					'id'       => 'woocommerce_stock_email_recipient',
					'type'     => 'text',
					'default'  => get_option( 'admin_email' ),
					'css'      => 'width: 250px;',
					'autoload' => false,
					'desc_tip' => true,
					'class'    => 'manage_stock_field',
				),

				array(
					'title'             => __( 'Low stock threshold', 'classic-commerce' ),
					'desc'              => __( 'When product stock reaches this amount you will be notified via email.', 'classic-commerce' ),
					'id'                => 'woocommerce_notify_low_stock_amount',
					'css'               => 'width:50px;',
					'type'              => 'number',
					'custom_attributes' => array(
						'min'  => 0,
						'step' => 1,
					),
					'default'           => '2',
					'autoload'          => false,
					'desc_tip'          => true,
					'class'             => 'manage_stock_field',
				),

				array(
					'title'             => __( 'Out of stock threshold', 'classic-commerce' ),
					'desc'              => __( 'When product stock reaches this amount the stock status will change to "out of stock" and you will be notified via email. This setting does not affect existing "in stock" products.', 'classic-commerce' ),
					'id'                => 'woocommerce_notify_no_stock_amount',
					'css'               => 'width:50px;',
					'type'              => 'number',
					'custom_attributes' => array(
						'min'  => 0,
						'step' => 1,
					),
					'default'           => '0',
					'desc_tip'          => true,
					'class'             => 'manage_stock_field',
				),

				array(
					'title'   => __( 'Out of stock visibility', 'classic-commerce' ),
					'desc'    => __( 'Hide out of stock items from the catalog', 'classic-commerce' ),
					'id'      => 'woocommerce_hide_out_of_stock_items',
					'default' => 'no',
					'type'    => 'checkbox',
				),

				array(
					'title'    => __( 'Stock display format', 'classic-commerce' ),
					'desc'     => __( 'This controls how stock quantities are displayed on the frontend.', 'classic-commerce' ),
					'id'       => 'woocommerce_stock_format',
					'css'      => 'min-width:150px;',
					'class'    => 'wc-enhanced-select',
					'default'  => '',
					'type'     => 'select',
					'options'  => array(
						''           => __( 'Always show quantity remaining in stock e.g. "12 in stock"', 'classic-commerce' ),
						'low_amount' => __( 'Only show quantity remaining in stock when low e.g. "Only 2 left in stock"', 'classic-commerce' ),
						'no_amount'  => __( 'Never show quantity remaining in stock', 'classic-commerce' ),
					),
					'desc_tip' => true,
				),

				array(
					'type' => 'sectionend',
					'id'   => 'product_inventory_options',
				),
			);

		return apply_filters( 'woocommerce_inventory_settings', $settings );
	}

	/**
	 * Get settings for the downloadable section.
	 *
	 * @return array
	 */
	protected function get_settings_for_downloadable_section() {
		$settings =
			array(
				array(
					'title' => __( 'Downloadable products', 'classic-commerce' ),
					'type'  => 'title',
					'id'    => 'digital_download_options',
				),

				array(
					'title'    => __( 'File download method', 'classic-commerce' ),
					'desc'     => sprintf(
						/* translators: 1: X-Accel-Redirect 2: X-Sendfile 3: mod_xsendfile */
						__( 'Forcing downloads will keep URLs hidden, but some servers may serve large files unreliably. If supported, %1$s / %2$s can be used to serve downloads instead (server requires %3$s).', 'classic-commerce' ),
						'<code>X-Accel-Redirect</code>',
						'<code>X-Sendfile</code>',
						'<code>mod_xsendfile</code>'
					),
					'id'       => 'woocommerce_file_download_method',
					'type'     => 'select',
					'class'    => 'wc-enhanced-select',
					'css'      => 'min-width:300px;',
					'default'  => 'force',
					'desc_tip' => true,
					'options'  => array(
						'force'     => __( 'Force downloads', 'classic-commerce' ),
						'xsendfile' => __( 'X-Accel-Redirect/X-Sendfile', 'classic-commerce' ),
						'redirect'  => apply_filters( 'woocommerce_redirect_only_method_is_secure', false ) ? __( 'Redirect only', 'classic-commerce' ) : __( 'Redirect only (Insecure)', 'classic-commerce' ),
					),
					'autoload' => false,
				),

				array(
					'desc'          => __( 'Allow using redirect mode (insecure) as a last resort', 'classic-commerce' ),
					'id'            => 'woocommerce_downloads_redirect_fallback_allowed',
					'type'          => 'checkbox',
					'default'       => 'no',
					'desc_tip'      => sprintf(
						/* translators: %1$s is a link to the Classic Commerce documentation. */
						__( 'If the "Force Downloads" or "X-Accel-Redirect/X-Sendfile" download method is selected but does not work, the system will use the "Redirect" method as a last resort.', 'classic-commerce' ),
					),
					'checkboxgroup' => 'start',
					'autoload'      => false,
				),

				array(
					'title'         => __( 'Access restriction', 'classic-commerce' ),
					'desc'          => __( 'Downloads require login', 'classic-commerce' ),
					'id'            => 'woocommerce_downloads_require_login',
					'type'          => 'checkbox',
					'default'       => 'no',
					'desc_tip'      => __( 'This setting does not apply to guest purchases.', 'classic-commerce' ),
					'checkboxgroup' => 'start',
					'autoload'      => false,
				),

				array(
					'desc'          => __( 'Grant access to downloadable products after payment', 'classic-commerce' ),
					'id'            => 'woocommerce_downloads_grant_access_after_payment',
					'type'          => 'checkbox',
					'default'       => 'yes',
					'desc_tip'      => __( 'Enable this option to grant access to downloads when orders are "processing", rather than "completed".', 'classic-commerce' ),
					'checkboxgroup' => 'end',
					'autoload'      => false,
				),

                array(
					'title'    => __( 'Open in browser', 'woocommerce' ),
					'desc'     => __( 'Open downloadable files in the browser, instead of saving them to the device.', 'woocommerce' ),
					'id'       => 'woocommerce_downloads_deliver_inline',
					'type'     => 'checkbox',
					'default'  => false,
					'desc_tip' => __( 'Customers can still save the file to their device, but by default file will be opened instead of being downloaded (does not work with redirects).', 'classic-commerce' ),
					'autoload' => false,
				),

				array(
					'title'    => __( 'Filename', 'classic-commerce' ),
					'desc'     => __( 'Append a unique string to filename for security', 'classic-commerce' ),
					'id'       => 'woocommerce_downloads_add_hash_to_filename',
					'type'     => 'checkbox',
					'default'  => 'yes',
					'desc_tip' => sprintf(
					// translators: Link to Classic Commerce Docs.
						__( "Not required if your download directory is protected. Files already uploaded will not be affected.", 'classic-commerce' ),
					),
				),

				array(
					'type' => 'sectionend',
					'id'   => 'digital_download_options',
				),
			);

		return apply_filters( 'woocommerce_downloadable_products_settings', $settings );
	}

	/**
	 * Save settings and trigger the 'woocommerce_update_options_'.id action.
	 */
	public function save() {
		$this->save_settings_for_current_section();

		/*
		 * Product->Inventory has a setting `Out of stock visibility`.
		 * Because of this, we need to recount the terms to keep them in-sync.
		 */
		wc_recount_all_terms();

		$this->do_update_options_action();
	}
}

return new WC_Settings_Products();
