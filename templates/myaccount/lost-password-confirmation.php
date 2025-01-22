<?php
/**
 * Lost password confirmation text.
 *
 * This template can be overridden by copying it to yourtheme/classic-commerce/myaccount/lost-password-confirmation.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @package ClassicCommerce/Templates
 * @version WC-3.9.0
 */

defined( 'ABSPATH' ) || exit;

wc_print_notice( esc_html__( 'Password reset email has been sent.', 'classic-commerce' ) );
?>

<?php do_action( 'woocommerce_before_lost_password_confirmation_message' ); ?>

<p><?php echo esc_html( apply_filters( 'woocommerce_lost_password_confirmation_message', esc_html__( 'A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset.', 'classic-commerce' ) ) ); ?></p>

<?php do_action( 'woocommerce_after_lost_password_confirmation_message' ); ?>
