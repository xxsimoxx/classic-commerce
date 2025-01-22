<?php
/**
 * Classic Commerce General Settings
 *
 * @package ClassicCommerce\Admin
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WC_Settings_General', false ) ) {
	return new WC_Settings_General();
}

/**
 * WC_Admin_Settings_General.
 */
class WC_Settings_General extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'general';
		$this->label = __( 'General', 'classic-commerce' );

		parent::__construct();
	}

	/**
	 * Get settings or the default section.
	 *
	 * @return array
	 */
	protected function get_settings_for_default_section() {

		$currency_code_options = get_woocommerce_currencies();

		foreach ( $currency_code_options as $code => $name ) {
			$currency_code_options[ $code ] = $name . ' (' . get_woocommerce_currency_symbol( $code ) . ') — ' . esc_html( $code );
		}

		$settings =
			array(

				array(
					'title' => __( 'Store Address', 'classic-commerce' ),
					'type'  => 'title',
					'desc'  => __( 'This is where your business is located. Tax rates and shipping rates will use this address.', 'classic-commerce' ),
					'id'    => 'store_address',
				),

				array(
					'title'    => __( 'Address line 1', 'classic-commerce' ),
					'desc'     => __( 'The street address for your business location.', 'classic-commerce' ),
					'id'       => 'woocommerce_store_address',
					'default'  => '',
					'type'     => 'text',
					'desc_tip' => true,
				),

				array(
					'title'    => __( 'Address line 2', 'classic-commerce' ),
					'desc'     => __( 'An additional, optional address line for your business location.', 'classic-commerce' ),
					'id'       => 'woocommerce_store_address_2',
					'default'  => '',
					'type'     => 'text',
					'desc_tip' => true,
				),

				array(
					'title'    => __( 'City', 'classic-commerce' ),
					'desc'     => __( 'The city in which your business is located.', 'classic-commerce' ),
					'id'       => 'woocommerce_store_city',
					'default'  => '',
					'type'     => 'text',
					'desc_tip' => true,
				),

				array(
					'title'    => __( 'Country / State', 'classic-commerce' ),
					'desc'     => __( 'The country and state or province, if any, in which your business is located.', 'classic-commerce' ),
					'id'       => 'woocommerce_default_country',
					'default'  => 'US:CA',
					'type'     => 'single_select_country',
					'desc_tip' => true,
				),

				array(
					'title'    => __( 'Postcode / ZIP', 'classic-commerce' ),
					'desc'     => __( 'The postal code, if any, in which your business is located.', 'classic-commerce' ),
					'id'       => 'woocommerce_store_postcode',
					'css'      => 'min-width:50px;',
					'default'  => '',
					'type'     => 'text',
					'desc_tip' => true,
				),

				array(
					'type' => 'sectionend',
					'id'   => 'store_address',
				),

				array(
					'title' => __( 'General options', 'classic-commerce' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'general_options',
				),

				array(
					'title'    => __( 'Selling location(s)', 'classic-commerce' ),
					'desc'     => __( 'This option lets you limit which countries you are willing to sell to.', 'classic-commerce' ),
					'id'       => 'woocommerce_allowed_countries',
					'default'  => 'all',
					'type'     => 'select',
					'class'    => 'wc-enhanced-select',
					'css'      => 'min-width: 350px;',
					'desc_tip' => true,
					'options'  => array(
						'all'        => __( 'Sell to all countries', 'classic-commerce' ),
						'all_except' => __( 'Sell to all countries, except for&hellip;', 'classic-commerce' ),
						'specific'   => __( 'Sell to specific countries', 'classic-commerce' ),
					),
				),

				array(
					'title'   => __( 'Sell to all countries, except for&hellip;', 'classic-commerce' ),
					'desc'    => '',
					'id'      => 'woocommerce_all_except_countries',
					'css'     => 'min-width: 350px;',
					'default' => '',
					'type'    => 'multi_select_countries',
				),

				array(
					'title'   => __( 'Sell to specific countries', 'classic-commerce' ),
					'desc'    => '',
					'id'      => 'woocommerce_specific_allowed_countries',
					'css'     => 'min-width: 350px;',
					'default' => '',
					'type'    => 'multi_select_countries',
				),

				array(
					'title'    => __( 'Shipping location(s)', 'classic-commerce' ),
					'desc'     => __( 'Choose which countries you want to ship to, or choose to ship to all locations you sell to.', 'classic-commerce' ),
					'id'       => 'woocommerce_ship_to_countries',
					'default'  => '',
					'type'     => 'select',
					'class'    => 'wc-enhanced-select',
					'desc_tip' => true,
					'options'  => array(
						''         => __( 'Ship to all countries you sell to', 'classic-commerce' ),
						'all'      => __( 'Ship to all countries', 'classic-commerce' ),
						'specific' => __( 'Ship to specific countries only', 'classic-commerce' ),
						'disabled' => __( 'Disable shipping &amp; shipping calculations', 'classic-commerce' ),
					),
				),

				array(
					'title'   => __( 'Ship to specific countries', 'classic-commerce' ),
					'desc'    => '',
					'id'      => 'woocommerce_specific_ship_to_countries',
					'css'     => '',
					'default' => '',
					'type'    => 'multi_select_countries',
				),

				array(
					'title'    => __( 'Default customer location', 'classic-commerce' ),
					'id'       => 'woocommerce_default_customer_address',
					'desc_tip' => __( 'This option determines a customers default location. The MaxMind GeoLite Database will be periodically downloaded to your wp-content directory if using geolocation.', 'classic-commerce' ),
					'default'  => 'base',
					'type'     => 'select',
					'class'    => 'wc-enhanced-select',
					'options'  => array(
						''                 => __( 'No location by default', 'classic-commerce' ),
						'base'             => __( 'Shop country/region', 'classic-commerce' ),
						'geolocation'      => __( 'Geolocate', 'classic-commerce' ),
						'geolocation_ajax' => __( 'Geolocate (with page caching support)', 'classic-commerce' ),
					),
				),

				array(
					'title'    => __( 'Enable taxes', 'classic-commerce' ),
					'desc'     => __( 'Enable tax rates and calculations', 'classic-commerce' ),
					'id'       => 'woocommerce_calc_taxes',
					'default'  => 'no',
					'type'     => 'checkbox',
					'desc_tip' => __( 'Rates will be configurable and taxes will be calculated during checkout.', 'classic-commerce' ),
				),

				array(
					'title'           => __( 'Enable coupons', 'classic-commerce' ),
					'desc'            => __( 'Enable the use of coupon codes', 'classic-commerce' ),
					'id'              => 'woocommerce_enable_coupons',
					'default'         => 'yes',
					'type'            => 'checkbox',
					'checkboxgroup'   => 'start',
					'show_if_checked' => 'option',
					'desc_tip'        => __( 'Coupons can be applied from the cart and checkout pages.', 'classic-commerce' ),
				),

				array(
					'desc'            => __( 'Calculate coupon discounts sequentially', 'classic-commerce' ),
					'id'              => 'woocommerce_calc_discounts_sequentially',
					'default'         => 'no',
					'type'            => 'checkbox',
					'desc_tip'        => __( 'When applying multiple coupons, apply the first coupon to the full price and the second coupon to the discounted price and so on.', 'classic-commerce' ),
					'show_if_checked' => 'yes',
					'checkboxgroup'   => 'end',
					'autoload'        => false,
				),

				array(
					'type' => 'sectionend',
					'id'   => 'general_options',
				),

				array(
					'title' => __( 'Currency options', 'classic-commerce' ),
					'type'  => 'title',
					'desc'  => __( 'The following options affect how prices are displayed on the frontend.', 'classic-commerce' ),
					'id'    => 'pricing_options',
				),

				array(
					'title'    => __( 'Currency', 'classic-commerce' ),
					'desc'     => __( 'This controls what currency prices are listed at in the catalog and which currency gateways will take payments in.', 'classic-commerce' ),
					'id'       => 'woocommerce_currency',
					'default'  => 'USD',
					'type'     => 'select',
					'class'    => 'wc-enhanced-select',
					'desc_tip' => true,
					'options'  => $currency_code_options,
				),

				array(
					'title'    => __( 'Currency position', 'classic-commerce' ),
					'desc'     => __( 'This controls the position of the currency symbol.', 'classic-commerce' ),
					'id'       => 'woocommerce_currency_pos',
					'class'    => 'wc-enhanced-select',
					'default'  => 'left',
					'type'     => 'select',
					'options'  => array(
						'left'        => __( 'Left', 'classic-commerce' ),
						'right'       => __( 'Right', 'classic-commerce' ),
						'left_space'  => __( 'Left with space', 'classic-commerce' ),
						'right_space' => __( 'Right with space', 'classic-commerce' ),
					),
					'desc_tip' => true,
				),

				array(
					'title'    => __( 'Thousand separator', 'classic-commerce' ),
					'desc'     => __( 'This sets the thousand separator of displayed prices.', 'classic-commerce' ),
					'id'       => 'woocommerce_price_thousand_sep',
					'css'      => 'width:50px;',
					'default'  => ',',
					'type'     => 'text',
					'desc_tip' => true,
				),

				array(
					'title'    => __( 'Decimal separator', 'classic-commerce' ),
					'desc'     => __( 'This sets the decimal separator of displayed prices.', 'classic-commerce' ),
					'id'       => 'woocommerce_price_decimal_sep',
					'css'      => 'width:50px;',
					'default'  => '.',
					'type'     => 'text',
					'desc_tip' => true,
				),

				array(
					'title'             => __( 'Number of decimals', 'classic-commerce' ),
					'desc'              => __( 'This sets the number of decimal points shown in displayed prices.', 'classic-commerce' ),
					'id'                => 'woocommerce_price_num_decimals',
					'css'               => 'width:50px;',
					'default'           => '2',
					'desc_tip'          => true,
					'type'              => 'number',
					'custom_attributes' => array(
						'min'  => 0,
						'step' => 1,
					),
				),

				array(
					'type' => 'sectionend',
					'id'   => 'pricing_options',
				),
			);

		return apply_filters( 'woocommerce_general_settings', $settings );
	}

	/**
	 * Output a color picker input box.
	 *
	 * @param mixed  $name Name of input.
	 * @param string $id ID of input.
	 * @param mixed  $value Value of input.
	 * @param string $desc (default: '') Description for input.
	 */
	public function color_picker( $name, $id, $value, $desc = '' ) {
		echo '<div class="color_box">' . wc_help_tip( $desc ) . '
			<input name="' . esc_attr( $id ) . '" id="' . esc_attr( $id ) . '" type="text" value="' . esc_attr( $value ) . '" class="colorpick" /> <div id="colorPickerDiv_' . esc_attr( $id ) . '" class="colorpickdiv"></div>
		</div>';
	}
}

return new WC_Settings_General();
