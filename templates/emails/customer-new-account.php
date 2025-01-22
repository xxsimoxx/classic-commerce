<?php
/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/classic-commerce/emails/customer-new-account.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @package ClassicCommerce/Templates/Emails
 * @version WC-6.0.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: Customer username */ ?>
<p><?php printf( esc_html__( 'Hi %s,', 'classic-commerce' ), esc_html( $user_login ) ); ?></p>
<?php /* translators: %1$s: Site title, %2$s: Username, %3$s: My account link */ ?>
<p><?php printf( esc_html__( 'Thanks for creating an account on %1$s. Your username is %2$s. You can access your account area to view orders, change your password, and more at: %3$s', 'classic-commerce' ), esc_html( $blogname ), '<strong>' . esc_html( $user_login ) . '</strong>', make_clickable( esc_url( wc_get_page_permalink( 'myaccount' ) ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<?php if ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) && $password_generated && $set_password_url ) : ?>
	<?php // If the password has not been set by the user during the sign up process, send them a link to set a new password ?>
	<p><a href="<?php echo esc_attr( $set_password_url ); ?>"><?php printf( esc_html__( 'Click here to set your new password.', 'classic-commerce' ) ); ?></a></p>
<?php endif; ?>

<p><?php esc_html_e( 'We look forward to seeing you soon.', 'classic-commerce' ); ?></p>

<?php
do_action( 'woocommerce_email_footer', $email );
