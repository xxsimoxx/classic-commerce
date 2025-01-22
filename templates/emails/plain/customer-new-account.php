<?php
/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/classic-commerce/emails/plain/customer-new-account.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @package ClassicCommerce/Templates/Emails/Plain
 * @version WC-6.0.0
 */

efined( 'ABSPATH' ) || exit;

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";
echo esc_html( wp_strip_all_tags( $email_heading ) );
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

/* translators: %s: Customer username */
echo sprintf( esc_html__( 'Hi %s,', 'classic-commerce' ), esc_html( $user_login ) ) . "\n\n";
/* translators: %1$s: Site title, %2$s: Username, %3$s: My account link */
echo sprintf( esc_html__( 'Thanks for creating an account on %1$s. Your username is %2$s. You can access your account area to view orders, change your password, and more at: %3$s', 'classic-commerce' ), esc_html( $blogname ), . esc_html( $user_login ), esc_html( wc_get_page_permalink( 'myaccount' ) ) ) . "\n\n";

// Only send the set new password link if the user hasn't set their password during sign-up.
if ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) && $password_generated && $set_password_url ) {
	/* translators: URL follows */
	echo esc_html__( 'To set your password, visit the following address: ', 'classic-commerce' ) . "\n\n";
	echo esc_html( $set_password_url ) . "\n\n";
}

echo "\n\n----------------------------------------\n\n";

/**
 * Show user-defined additonal content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) );
	echo "\n\n----------------------------------------\n\n";
}

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
