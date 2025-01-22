<?php
/**
 * Admin View: Page - Status Report.
 *
 * @package Classic Commerce
 */

defined( 'ABSPATH' ) || exit;

global $wpdb;

// This screen requires classes from the REST API.
if ( ! did_action( 'rest_api_init' ) ) {
	WC()->api->rest_api_includes();
}

if ( ! class_exists( 'WC_REST_System_Status_Controller', false ) ) {
	wp_die( 'Cannot load the REST API to access WC_REST_System_Status_Controller.' );
}

$report             = wc()->api->get_endpoint_data( '/wc/v3/system_status' );
$system_status      = new WC_REST_System_Status_Controller();
$environment        = $system_status->get_environment_info();
$database           = $system_status->get_database_info();
$post_type_counts   = $system_status->get_post_type_counts();
$active_plugins     = $system_status->get_active_plugins();
$inactive_plugins   = $system_status->get_inactive_plugins();
$dropins_mu_plugins = $system_status->get_dropins_mu_plugins();
$theme              = $system_status->get_theme_info();
$security           = $system_status->get_security_info();
$settings           = $system_status->get_settings();
$wp_pages           = $system_status->get_pages();
$plugin_updates     = new WC_Plugin_Updates();
$untested_plugins   = $plugin_updates->get_untested_plugins( WC()->version, 'minor' );
?>
<div class="updated woocommerce-message inline">
	<p>
		<?php esc_html_e( 'Please copy and paste this information in your ticket when contacting support:', 'classic-commerce' ); ?>
	</p>
	<p class="submit">
		<a href="#" class="button-primary debug-report"><?php esc_html_e( 'Get system report', 'classic-commerce' ); ?></a>
		<a class="button-secondary docs" href="https://classiccommerce.cc/docs/installation-and-setup/status-report/" target="_blank">
			<?php esc_html_e( 'Understanding the status report', 'classic-commerce' ); ?>
		</a>
	</p>
	<div id="debug-report">
		<textarea readonly="readonly"></textarea>
		<p class="submit">
			<button id="copy-for-support" class="button-primary" href="#" data-tip="<?php esc_attr_e( 'Copied!', 'classic-commerce' ); ?>">
				<?php esc_html_e( 'Copy for support', 'classic-commerce' ); ?>
			</button>
		</p>
		<p class="copy-error hidden">
			<?php esc_html_e( 'Copying to clipboard failed. Please press Ctrl/Cmd+C to copy.', 'classic-commerce' ); ?>
		</p>
	</div>
</div>
<table class="wc_status_table widefat" cellspacing="0" id="status">
	<thead>
		<tr>
			<th colspan="3" data-export-label="WordPress Environment"><h2><?php esc_html_e( 'WordPress environment', 'classic-commerce' ); ?></h2></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td data-export-label="WordPress address (URL)"><?php esc_html_e( 'WordPress address (URL)', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'The root URL of your site.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td><?php echo esc_html( $environment['site_url'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Site address (URL)"><?php esc_html_e( 'Site address (URL)', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'The homepage URL of your site.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td><?php echo esc_html( $environment['home_url'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="CC Version"><?php esc_html_e( 'Classic Commerce version', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'The version of Classic Commerce installed on your site.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td><?php echo esc_html( $environment['cc_version'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="WC Version"><?php esc_html_e( 'WooCommerce equivalent version', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'Equivalent version of WooCommerce installed on your site.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td><?php echo esc_html( $environment['version'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Action Scheduler Version"><?php esc_html_e( 'Action Scheduler package', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'Action Scheduler package running on your site.', 'classic-commerce' ) ); ?></td>
			<td>
				<?php
				if ( class_exists( 'ActionScheduler_Versions' ) && class_exists( 'ActionScheduler' ) ) {
					$version = ActionScheduler_Versions::instance()->latest_version();
					$path    = ActionScheduler::plugin_path( '' );
				} else {
					$version = null;
				}

				if ( ! is_null( $version ) ) {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span> ' . esc_html( $version ) . ' <code class="private">' . esc_html( $path ) . '</code></mark> ';
				} else {
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . esc_html__( 'Unable to detect the Action Scheduler package.', 'classic-commerce' ) . '</mark>';
				}
				?>
			</td>
		</tr>
        <tr>
			<td data-export-label="Log Directory Writable"><?php esc_html_e( 'Log directory writable', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'Several Classic Commerce extensions can write logs which makes debugging problems easier. The directory must be writable for this to happen.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td>
				<?php
				if ( $environment['log_directory_writable'] ) {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span> <code class="private">' . esc_html( $environment['log_directory'] ) . '</code></mark> ';
				} else {
					/* Translators: %1$s: Log directory, %2$s: Log directory constant */
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( 'To allow logging, make %1$s writable or define a custom %2$s.', 'classic-commerce' ), '<code>' . esc_html( $environment['log_directory'] ) . '</code>', '<code>WC_LOG_DIR</code>' ) . '</mark>';
				}
				?>
			</td>
		</tr>
		<tr>
			<td data-export-label="CMS Version"><?php esc_html_e( 'CMS version', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'The version of the CMS installed on your site.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td>
				<?php
				// Check if Core is ClassicPress
				if ( function_exists( 'classicpress_version' ) ) {
					$latest_version = get_transient( 'classic_commerce_system_status_cp_version_check' );
					$classicpress_version = classicpress_version();
					$cp_url = 'https://api.classicpress.net/upgrade/' . $classicpress_version . '.json';

					if ( false === $latest_version ) {
						$version_check = wp_remote_get( $cp_url );
						$api_response  = json_decode( wp_remote_retrieve_body( $version_check ), true );
						if ( $api_response && isset( $api_response['offers'], $api_response['offers'][0], $api_response['offers'][0]['version'] ) ) {
							$latest_version = $api_response['offers'][0]['version'];
						} else {
							$latest_version = $classicpress_version;
						}
						set_transient( 'classic_commerce_system_status_cp_version_check', $latest_version, DAY_IN_SECONDS );
					}

					if ( version_compare( $classicpress_version, $latest_version, '<' ) ) {
						/* Translators: %1$s: Current version, %2$s: New version */
						echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( '%1$s - There is a newer version of ClassicPress available (%2$s)', 'classic-commerce' ), esc_html( $classicpress_version ), esc_html( $latest_version ) ) . '</mark>';
					} else {
						echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark> ' . sprintf( esc_html__( 'You are running ClassicPress Version %s', 'classic-commerce' ), esc_html( $classicpress_version ) );
					}
				} else {
						echo sprintf( esc_html__( 'You are running WordPress Version %s', 'classic-commerce' ), esc_html( $environment['wp_version'] ) );
				}
				?>
			</td>
		</tr>
		<tr>
			<td data-export-label="WP Multisite"><?php esc_html_e( 'WordPress multisite', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'Whether or not you have WordPress Multisite enabled.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td><?php echo ( $environment['wp_multisite'] ) ? '<span class="dashicons dashicons-yes"></span>' : '&ndash;'; ?></td>
		</tr>
		<tr>
			<td data-export-label="WP Memory Limit"><?php esc_html_e( 'WordPress memory limit', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'The maximum amount of memory (RAM) that your site can use at one time.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td>
				<?php
				if ( $environment['wp_memory_limit'] < 67108864 ) {
					/* Translators: %1$s: Memory limit, %2$s: Docs link. */
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( '%1$s - We recommend setting memory to at least 64MB. See: %2$s', 'classic-commerce' ), esc_html( size_format( $environment['wp_memory_limit'] ) ), '<a href="https://wordpress.org/support/article/editing-wp-config-php/#increasing-memory-allocated-to-php" target="_blank">' . esc_html__( 'Increasing memory allocated to PHP', 'classic-commerce' ) . '</a>' ) . '</mark>';
				} else {
					echo '<mark class="yes">' . esc_html( size_format( $environment['wp_memory_limit'] ) ) . '</mark>';
				}
				?>
			</td>
		</tr>
		<tr>
			<td data-export-label="WP Debug Mode"><?php esc_html_e( 'WordPress debug mode', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'Displays whether or not WordPress is in Debug Mode.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td>
				<?php if ( $environment['wp_debug_mode'] ) : ?>
					<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
				<?php else : ?>
					<mark class="no">&ndash;</mark>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="WP Cron"><?php esc_html_e( 'WordPress cron', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'Displays whether or not WP Cron Jobs are enabled.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td>
				<?php if ( $environment['wp_cron'] ) : ?>
					<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
				<?php else : ?>
					<mark class="no">&ndash;</mark>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Language"><?php esc_html_e( 'Language', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'The current language used by WordPress. Default = English', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td><?php echo esc_html( $environment['language'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="External object cache"><?php esc_html_e( 'External object cache', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'Displays whether or not WordPress is using an external object cache.', 'classic-commerce' ) ); ?></td>
			<td>
				<?php if ( $environment['external_object_cache'] ) : ?>
					<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
				<?php else : ?>
					<mark class="no">&ndash;</mark>
				<?php endif; ?>
			</td>
		</tr>
	</tbody>
</table>
<table class="wc_status_table widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3" data-export-label="Server Environment"><h2><?php esc_html_e( 'Server environment', 'classic-commerce' ); ?></h2></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td data-export-label="Server Info"><?php esc_html_e( 'Server info', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'Information about the web server that is currently hosting your site.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td><?php echo esc_html( $environment['server_info'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="PHP Version"><?php esc_html_e( 'PHP version', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'The version of PHP installed on your hosting server.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td>
				<?php echo '<mark class="yes">' . esc_html( $environment['php_version'] ) . '</mark>'; ?>
			</td>
		</tr>
		<?php if ( function_exists( 'ini_get' ) ) : ?>
			<tr>
				<td data-export-label="PHP Post Max Size"><?php esc_html_e( 'PHP post max size', 'classic-commerce' ); ?>:</td>
				<td class="help"><?php echo wc_help_tip( esc_html__( 'The largest filesize that can be contained in one post.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td><?php echo esc_html( size_format( $environment['php_post_max_size'] ) ); ?></td>
			</tr>
			<tr>
				<td data-export-label="PHP Time Limit"><?php esc_html_e( 'PHP time limit', 'classic-commerce' ); ?>:</td>
				<td class="help"><?php echo wc_help_tip( esc_html__( 'The amount of time (in seconds) that your site will spend on a single operation before timing out (to avoid server lockups)', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td><?php echo esc_html( $environment['php_max_execution_time'] ); ?></td>
			</tr>
			<tr>
				<td data-export-label="PHP Max Input Vars"><?php esc_html_e( 'PHP max input vars', 'classic-commerce' ); ?>:</td>
				<td class="help"><?php echo wc_help_tip( esc_html__( 'The maximum number of variables your server can use for a single function to avoid overloads.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td><?php echo esc_html( $environment['php_max_input_vars'] ); ?></td>
			</tr>
			<tr>
				<td data-export-label="cURL Version"><?php esc_html_e( 'cURL version', 'classic-commerce' ); ?>:</td>
				<td class="help"><?php echo wc_help_tip( esc_html__( 'The version of cURL installed on your server.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td><?php echo esc_html( $environment['curl_version'] ); ?></td>
			</tr>
			<tr>
				<td data-export-label="SUHOSIN Installed"><?php esc_html_e( 'SUHOSIN installed', 'classic-commerce' ); ?>:</td>
				<td class="help"><?php echo wc_help_tip( esc_html__( 'Suhosin is an advanced protection system for PHP installations. It was designed to protect your servers on the one hand against a number of well known problems in PHP applications and on the other hand against potential unknown vulnerabilities within these applications or the PHP core itself. If enabled on your server, Suhosin may need to be configured to increase its data submission limits.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td><?php echo $environment['suhosin_installed'] ? '<span class="dashicons dashicons-yes"></span>' : '&ndash;'; ?></td>
			</tr>
		<?php endif; ?>

		<?php

		if ( $environment['mysql_version'] ) :
			?>
			<tr>
				<td data-export-label="MySQL Version"><?php esc_html_e( 'MySQL version', 'classic-commerce' ); ?>:</td>
				<td class="help"><?php echo wc_help_tip( esc_html__( 'The version of MySQL installed on your hosting server.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td>
					<?php
					if ( version_compare( $environment['mysql_version'], '5.6', '<' ) && ! strstr( $environment['mysql_version_string'], 'MariaDB' ) ) {
						/* Translators: %1$s: MySQL version, %2$s: Recommended MySQL version. */
						echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( '%1$s - We recommend a minimum MySQL version of 5.6. See: %2$s', 'classic-commerce' ), esc_html( $environment['mysql_version_string'] ), '<a href="https://wordpress.org/about/requirements/" target="_blank">' . esc_html__( 'WordPress requirements', 'classic-commerce' ) . '</a>' ) . '</mark>';
					} else {
						echo '<mark class="yes">' . esc_html( $environment['mysql_version_string'] ) . '</mark>';
					}
					?>
				</td>
			</tr>
		<?php endif; ?>
		<tr>
			<td data-export-label="Max Upload Size"><?php esc_html_e( 'Max upload size', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'The largest filesize that can be uploaded to your WordPress installation.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td><?php echo esc_html( size_format( $environment['max_upload_size'] ) ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Default Timezone is UTC"><?php esc_html_e( 'Default timezone is UTC', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'The default timezone for your server.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td>
				<?php
				if ( 'UTC' !== $environment['default_timezone'] ) {
					/* Translators: %s: default timezone.. */
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( 'Default timezone is %s - it should be UTC', 'classic-commerce' ), esc_html( $environment['default_timezone'] ) ) . '</mark>';
				} else {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				}
				?>
			</td>
		</tr>
		<tr>
			<td data-export-label="fsockopen/cURL"><?php esc_html_e( 'fsockopen/cURL', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'Payment gateways can use cURL to communicate with remote servers to authorize payments, other plugins may also use it when communicating with remote services.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td>
				<?php
				if ( $environment['fsockopen_or_curl_enabled'] ) {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} else {
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . esc_html__( 'Your server does not have fsockopen or cURL enabled - PayPal IPN and other scripts which communicate with other servers will not work. Contact your hosting provider.', 'classic-commerce' ) . '</mark>';
				}
				?>
			</td>
		</tr>
		<tr>
			<td data-export-label="SoapClient"><?php esc_html_e( 'SoapClient', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'Some webservices like shipping use SOAP to get information from remote servers, for example, live shipping quotes from FedEx require SOAP to be installed.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td>
				<?php
				if ( $environment['soapclient_enabled'] ) {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} else {
					/* Translators: %s classname and link. */
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( 'Your server does not have the %s class enabled - some gateway plugins which use SOAP may not work as expected.', 'classic-commerce' ), '<a href="https://php.net/manual/en/class.soapclient.php">SoapClient</a>' ) . '</mark>';
				}
				?>
			</td>
		</tr>
		<tr>
			<td data-export-label="DOMDocument"><?php esc_html_e( 'DOMDocument', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'HTML/Multipart emails use DOMDocument to generate inline CSS in templates.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td>
				<?php
				if ( $environment['domdocument_enabled'] ) {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} else {
					/* Translators: %s: classname and link. */
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( 'Your server does not have the %s class enabled - HTML/Multipart emails, and also some extensions, will not work without DOMDocument.', 'classic-commerce' ), '<a href="https://php.net/manual/en/class.domdocument.php">DOMDocument</a>' ) . '</mark>';
				}
				?>
			</td>
		</tr>
		<tr>
			<td data-export-label="GZip"><?php esc_html_e( 'GZip', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'GZip (gzopen) is used to open the GEOIP database from MaxMind.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td>
				<?php
				if ( $environment['gzip_enabled'] ) {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} else {
					/* Translators: %s: classname and link. */
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( 'Your server does not support the %s function - this is required to use the GeoIP database from MaxMind.', 'classic-commerce' ), '<a href="https://php.net/manual/en/zlib.installation.php">gzopen</a>' ) . '</mark>';
				}
				?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Multibyte String"><?php esc_html_e( 'Multibyte string', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'Multibyte String (mbstring) is used to convert character encoding, like for emails or converting characters to lowercase.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td>
				<?php
				if ( $environment['mbstring_enabled'] ) {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} else {
					/* Translators: %s: classname and link. */
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( 'Your server does not support the %s functions - this is required for better character encoding. Some fallbacks will be used instead for it.', 'classic-commerce' ), '<a href="https://php.net/manual/en/mbstring.installation.php">mbstring</a>' ) . '</mark>';
				}
				?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Remote Post"><?php esc_html_e( 'Remote post', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'PayPal uses this method of communicating when sending back transaction information.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td>
				<?php
				if ( $environment['remote_post_successful'] ) {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} else {
					/* Translators: %s: function name. */
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( '%s failed. Contact your hosting provider.', 'classic-commerce' ), 'wp_remote_post()' ) . ' ' . esc_html( $environment['remote_post_response'] ) . '</mark>';
				}
				?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Remote Get"><?php esc_html_e( 'Remote get', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'Classic Commerce plugins may use this method of communication when checking for plugin updates.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td>
				<?php
				if ( $environment['remote_get_successful'] ) {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} else {
					/* Translators: %s: function name. */
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( '%s failed. Contact your hosting provider.', 'classic-commerce' ), 'wp_remote_get()' ) . ' ' . esc_html( $environment['remote_get_response'] ) . '</mark>';
				}
				?>
			</td>
		</tr>
		<?php
		$rows = apply_filters( 'woocommerce_system_status_environment_rows', array() );
		foreach ( $rows as $row ) {
			if ( ! empty( $row['success'] ) ) {
				$css_class = 'yes';
				$icon      = '<span class="dashicons dashicons-yes"></span>';
			} else {
				$css_class = 'error';
				$icon      = '<span class="dashicons dashicons-no-alt"></span>';
			}
			?>
			<tr>
				<td data-export-label="<?php echo esc_attr( $row['name'] ); ?>"><?php echo esc_html( $row['name'] ); ?>:</td>
				<td class="help"><?php echo esc_html( isset( $row['help'] ) ? $row['help'] : '' ); ?></td>
				<td>
					<mark class="<?php echo esc_attr( $css_class ); ?>">
						<?php echo wp_kses_post( $icon ); ?> <?php echo wp_kses_data( ! empty( $row['note'] ) ? $row['note'] : '' ); ?>
					</mark>
				</td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>
<table id="status-database" class="wc_status_table widefat" cellspacing="0">
	<thead>
	<tr>
		<th colspan="3" data-export-label="Database">
			<h2>
				<?php
					esc_html_e( 'Database', 'classic-commerce' );
					self::output_tables_info();
				?>
			</h2>
		</th>
	</tr>
	</thead>
	<tbody>
		<tr>
			<td data-export-label="WC Database Version"><?php esc_html_e( 'Classic Commerce database version', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'The database version for Classic Commerce. Note that it doesn&#10076;t match Classic Commerce version and that is normal. This should be the same as your WooCommerce equivalent version.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td><?php echo esc_html( $database['wc_database_version'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="WC Database Prefix"><?php esc_html_e( 'Database prefix', 'classic-commerce' ); ?></td>
			<td class="help">&nbsp;</td>
			<td>
				<?php
				if ( strlen( $database['database_prefix'] ) > 20 ) {
					/* Translators: %1$s: Database prefix, %2$s: Docs link. */
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( '%1$s - We recommend using a prefix with less than 20 characters. See: %2$s', 'classic-commerce' ), esc_html( $database['database_prefix'] ), '<a href="https://classiccommerce.cc/usage-and-maintenance/update-database-table-prefix/" target="_blank">' . esc_html__( 'How to update your database table prefix', 'classic-commerce' ) . '</a>' ) . '</mark>';
				} else {
					echo '<mark class="yes">' . esc_html( $database['database_prefix'] ) . '</mark>';
				}
				?>
			</td>
		</tr>

        <?php if ( ! empty( $database['database_size'] ) && ! empty( $database['database_tables'] ) ) : ?>
			<tr>
				<td><?php esc_html_e( 'Total Database Size', 'classic-commerce' ); ?></td>
				<td class="help">&nbsp;</td>
				<td><?php printf( '%.2fMB', esc_html( $database['database_size']['data'] + $database['database_size']['index'] ) ); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'Database Data Size', 'classic-commerce' ); ?></td>
				<td class="help">&nbsp;</td>
				<td><?php printf( '%.2fMB', esc_html( $database['database_size']['data'] ) ); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'Database Index Size', 'classic-commerce' ); ?></td>
				<td class="help">&nbsp;</td>
				<td><?php printf( '%.2fMB', esc_html( $database['database_size']['index'] ) ); ?></td>
			</tr>
			<?php foreach ( $database['database_tables']['woocommerce'] as $table => $table_data ) { ?>
				<tr>
					<td><?php echo esc_html( $table ); ?></td>
					<td class="help">&nbsp;</td>
					<td>
						<?php
						if ( ! $table_data ) {
							echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . esc_html__( 'Table does not exist', 'classic-commerce' ) . '</mark>';
						} else {
							/* Translators: %1$f: Table size, %2$f: Index size, %3$s Engine. */
							printf( esc_html__( 'Data: %1$.2fMB + Index: %2$.2fMB + Engine %3$s', 'classic-commerce' ), esc_html( wc_format_decimal( $table_data['data'], 2 ) ), esc_html( wc_format_decimal( $table_data['index'], 2 ) ), esc_html( $table_data['engine'] ) );
						}
						?>
					</td>
				</tr>
			<?php } ?>

			<?php foreach ( $database['database_tables']['other'] as $table => $table_data ) { ?>
				<tr>
					<td><?php echo esc_html( $table ); ?></td>
					<td class="help">&nbsp;</td>
					<td>
						<?php
							/* Translators: %1$f: Table size, %2$f: Index size, %3$s Engine. */
							printf( esc_html__( 'Data: %1$.2fMB + Index: %2$.2fMB + Engine %3$s', 'classic-commerce' ), esc_html( wc_format_decimal( $table_data['data'], 2 ) ), esc_html( wc_format_decimal( $table_data['index'], 2 ) ), esc_html( $table_data['engine'] ) );
						?>
					</td>
				</tr>
			<?php } ?>
		<?php else : ?>
			<tr>
				<td><?php esc_html_e( 'Database information:', 'classic-commerce' ); ?></td>
				<td class="help">&nbsp;</td>
				<td>
					<?php
					esc_html_e(
						'Unable to retrieve database information. Usually, this is not a problem, and it only means that your install is using a class that replaces the WordPress database class (e.g., HyperDB) and Classic Commerce is unable to get database information.',
						'classic-commerce'
					);
					?>
				</td>
			</tr>
		<?php endif; ?>
	</tbody>
</table>
<?php if ( $post_type_counts ) : ?>
	<table class="wc_status_table widefat" cellspacing="0">
		<thead>
		<tr>
			<th colspan="3" data-export-label="Post Type Counts"><h2><?php esc_html_e( 'Post Type Counts', 'classic-commerce' ); ?></h2></th>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $post_type_counts as $post_type ) {
			?>
			<tr>
				<td><?php echo esc_html( $post_type->type ); ?></td>
				<td class="help">&nbsp;</td>
				<td><?php echo absint( $post_type->count ); ?></td>
			</tr>
            <?php } ?>
            <?php endif; ?>
	</tbody>
</table>
<table class="wc_status_table widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3" data-export-label="Security"><h2><?php esc_html_e( 'Security', 'classic-commerce' ); ?></h2></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td data-export-label="Secure connection (HTTPS)"><?php esc_html_e( 'Secure connection (HTTPS)', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'Is the connection to your store secure?', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td>
				<?php if ( $security['secure_connection'] ) : ?>
					<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
				<?php else : ?>
					<mark class="error"><span class="dashicons dashicons-warning"></span>
					<?php
					/* Translators: %s: docs link. */
					echo wp_kses_post( sprintf( __( 'Your store is not using HTTPS. <a href="%s" target="_blank">Learn more about HTTPS and SSL Certificates</a>.', 'classic-commerce' ), 'https://classiccommerce.cc/docs/installation-and-setup/ssl-and-https/' ) );
					?>
					</mark>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Hide errors from visitors"><?php esc_html_e( 'Hide errors from visitors', 'classic-commerce' ); ?></td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'Error messages can contain sensitive information about your store environment. These should be hidden from untrusted visitors.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td>
				<?php if ( $security['hide_errors'] ) : ?>
					<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
				<?php else : ?>
					<mark class="error"><span class="dashicons dashicons-warning"></span><?php esc_html_e( 'Error messages should not be shown to visitors.', 'classic-commerce' ); ?></mark>
				<?php endif; ?>
			</td>
		</tr>
	</tbody>
</table>
<table class="wc_status_table widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3" data-export-label="Active Plugins (<?php echo count( $active_plugins ); ?>)"><h2><?php esc_html_e( 'Active plugins', 'classic-commerce' ); ?> (<?php echo count( $active_plugins ); ?>)</h2></th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ( $active_plugins as $plugin ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			if ( ! empty( $plugin['name'] ) ) {
				$dirname = dirname( $plugin['plugin'] );

				// Link the plugin name to the plugin url if available.
				$plugin_name = esc_html( $plugin['name'] );
				if ( ! empty( $plugin['url'] ) ) {
					$plugin_name = '<a href="' . esc_url( $plugin['url'] ) . '" aria-label="' . esc_attr__( 'Visit plugin homepage', 'classic-commerce' ) . '" target="_blank">' . $plugin_name . '</a>';
				}

				$version_string = '';
				$network_string = '';
				if ( strstr( $plugin['url'], 'woothemes.com' ) || strstr( $plugin['url'], 'woocommerce.com' ) ) {
					if ( ! empty( $plugin['version_latest'] ) && version_compare( $plugin['version_latest'], $plugin['version'], '>' ) ) {
						/* translators: %s: plugin latest version */
						$version_string = ' &ndash; <strong style="color:red;">' . sprintf( esc_html__( '%s is available', 'classic-commerce' ), $plugin['version_latest'] ) . '</strong>';
					}

					if ( false !== $plugin['network_activated'] ) {
						$network_string = ' &ndash; <strong style="color:black;">' . esc_html__( 'Network enabled', 'classic-commerce' ) . '</strong>';
					}
				}
				$untested_string = '';
				if ( array_key_exists( $plugin['plugin'], $untested_plugins ) ) {
					$untested_string = ' &ndash; <strong style="color:red;">' . esc_html__( 'Not tested with the active version of Classic Commerce', 'classic-commerce' ) . '</strong>';
				}
				?>
				<tr>
					<td><?php echo wp_kses_post( $plugin_name ); ?></td>
					<td class="help">&nbsp;</td>
					<td>
					<?php
						/* translators: %s: plugin author */
						printf( esc_html__( 'by %s', 'classic-commerce' ), esc_html( $plugin['author_name'] ) );
						echo ' &ndash; ' . esc_html( $plugin['version'] ) . $version_string . $untested_string . $network_string; // WPCS: XSS ok.
					?>
					</td>
				</tr>
				<?php
			}
		}
		?>
	</tbody>
</table>
<table class="wc_status_table widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3" data-export-label="Inactive Plugins (<?php echo count( $inactive_plugins ); ?>)"><h2><?php esc_html_e( 'Inactive plugins', 'classic-commerce' ); ?> (<?php echo count( $inactive_plugins ); ?>)</h2></th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ( $inactive_plugins as $plugin ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			if ( ! empty( $plugin['name'] ) ) {
				$dirname = dirname( $plugin['plugin'] );

				// Link the plugin name to the plugin url if available.
				$plugin_name = esc_html( $plugin['name'] );
				if ( ! empty( $plugin['url'] ) ) {
					$plugin_name = '<a href="' . esc_url( $plugin['url'] ) . '" aria-label="' . esc_attr__( 'Visit plugin homepage', 'classic-commerce' ) . '" target="_blank">' . $plugin_name . '</a>';
				}

				$version_string = '';
				$network_string = '';
				if ( strstr( $plugin['url'], 'woothemes.com' ) || strstr( $plugin['url'], 'woocommerce.com' ) ) {
					if ( ! empty( $plugin['version_latest'] ) && version_compare( $plugin['version_latest'], $plugin['version'], '>' ) ) {
						/* translators: %s: plugin latest version */
						$version_string = ' &ndash; <strong style="color:red;">' . sprintf( esc_html__( '%s is available', 'classic-commerce' ), $plugin['version_latest'] ) . '</strong>';
					}

					if ( false !== $plugin['network_activated'] ) {
						$network_string = ' &ndash; <strong style="color:black;">' . esc_html__( 'Network enabled', 'classic-commerce' ) . '</strong>';
					}
				}
				$untested_string = '';
				if ( array_key_exists( $plugin['plugin'], $untested_plugins ) ) {
					$untested_string = ' &ndash; <strong style="color:red;">' . esc_html__( 'Not tested with the active version of Classic Commerce', 'classic-commerce' ) . '</strong>';
				}
				?>
				<tr>
					<td><?php echo wp_kses_post( $plugin_name ); ?></td>
					<td class="help">&nbsp;</td>
					<td>
					<?php
						/* translators: %s: plugin author */
						printf( esc_html__( 'by %s', 'classic-commerce' ), esc_html( $plugin['author_name'] ) );
						echo ' &ndash; ' . esc_html( $plugin['version'] ) . $version_string . $untested_string . $network_string; // WPCS: XSS ok.
					?>
					</td>
				</tr>
				<?php
			}
		}
		?>
	</tbody>
</table>
<?php
if ( 0 < count( $dropins_mu_plugins['dropins'] ) ) :
	?>
	<table class="wc_status_table widefat" cellspacing="0">
		<thead>
			<tr>
				<th colspan="3" data-export-label="Dropin Plugins (<?php echo count( $dropins_mu_plugins['dropins'] ); ?>)"><h2><?php esc_html_e( 'Dropin Plugins', 'classic-commerce' ); ?> (<?php echo count( $dropins_mu_plugins['dropins'] ); ?>)</h2></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ( $dropins_mu_plugins['dropins'] as $dropin ) {
				?>
				<tr>
					<td><?php echo wp_kses_post( $dropin['plugin'] ); ?></td>
					<td class="help">&nbsp;</td>
					<td><?php echo wp_kses_post( $dropin['name'] ); ?>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
	<?php
endif;
if ( 0 < count( $dropins_mu_plugins['mu_plugins'] ) ) :
	?>
	<table class="wc_status_table widefat" cellspacing="0">
		<thead>
			<tr>
				<th colspan="3" data-export-label="Must Use Plugins (<?php echo count( $dropins_mu_plugins['mu_plugins'] ); ?>)"><h2><?php esc_html_e( 'Must Use Plugins', 'classic-commerce' ); ?> (<?php echo count( $dropins_mu_plugins['mu_plugins'] ); ?>)</h2></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ( $dropins_mu_plugins['mu_plugins'] as $mu_plugin ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				$plugin_name = esc_html( $mu_plugin['name'] );
				if ( ! empty( $mu_plugin['url'] ) ) {
					$plugin_name = '<a href="' . esc_url( $mu_plugin['url'] ) . '" aria-label="' . esc_attr__( 'Visit plugin homepage', 'classic-commerce' ) . '" target="_blank">' . $plugin_name . '</a>';
				}
				?>
				<tr>
					<td><?php echo wp_kses_post( $plugin_name ); ?></td>
					<td class="help">&nbsp;</td>
					<td>
					<?php
						/* translators: %s: plugin author */
						printf( esc_html__( 'by %s', 'classic-commerce' ), esc_html( $mu_plugin['author_name'] ) );
						echo ' &ndash; ' . esc_html( $mu_plugin['version'] );
					?>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
<?php endif; ?>
<table class="wc_status_table widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3" data-export-label="Settings"><h2><?php esc_html_e( 'Settings', 'classic-commerce' ); ?></h2></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td data-export-label="Legacy API Enabled"><?php esc_html_e( 'Legacy API enabled', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'Does your site have the Legacy REST API enabled?', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td><?php echo $settings['api_enabled'] ? '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>' : '<mark class="no">&ndash;</mark>'; ?></td>
		</tr>
		<tr>
			<td data-export-label="Force SSL"><?php esc_html_e( 'Force SSL', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'Does your site force a SSL Certificate for transactions?', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td><?php echo $settings['force_ssl'] ? '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>' : '<mark class="no">&ndash;</mark>'; ?></td>
		</tr>
		<tr>
			<td data-export-label="Currency"><?php esc_html_e( 'Currency', 'classic-commerce' ); ?></td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'What currency prices are listed at in the catalog and which currency gateways will take payments in.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td><?php echo esc_html( $settings['currency'] ); ?> (<?php echo esc_html( $settings['currency_symbol'] ); ?>)</td>
		</tr>
		<tr>
			<td data-export-label="Currency Position"><?php esc_html_e( 'Currency position', 'classic-commerce' ); ?></td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'The position of the currency symbol.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td><?php echo esc_html( $settings['currency_position'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Thousand Separator"><?php esc_html_e( 'Thousand separator', 'classic-commerce' ); ?></td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'The thousand separator of displayed prices.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td><?php echo esc_html( $settings['thousand_separator'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Decimal Separator"><?php esc_html_e( 'Decimal separator', 'classic-commerce' ); ?></td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'The decimal separator of displayed prices.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td><?php echo esc_html( $settings['decimal_separator'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Number of Decimals"><?php esc_html_e( 'Number of decimals', 'classic-commerce' ); ?></td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'The number of decimal points shown in displayed prices.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td><?php echo esc_html( $settings['number_of_decimals'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Taxonomies: Product Types"><?php esc_html_e( 'Taxonomies: Product types', 'classic-commerce' ); ?></td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'A list of taxonomy terms that can be used in regard to order/product statuses.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td>
				<?php
				$display_terms = array();
				foreach ( $settings['taxonomies'] as $slug => $name ) {
					$display_terms[] = strtolower( $name ) . ' (' . $slug . ')';
				}
				echo implode( ', ', array_map( 'esc_html', $display_terms ) );
				?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Taxonomies: Product Visibility"><?php esc_html_e( 'Taxonomies: Product visibility', 'classic-commerce' ); ?></td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'A list of taxonomy terms used for product visibility.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td>
				<?php
				$display_terms = array();
				foreach ( $settings['product_visibility_terms'] as $slug => $name ) {
					$display_terms[] = strtolower( $name ) . ' (' . $slug . ')';
				}
				echo implode( ', ', array_map( 'esc_html', $display_terms ) );
				?>
			</td>
		</tr>
	</tbody>
</table>
<table class="wc_status_table widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3" data-export-label="WC Pages"><h2><?php esc_html_e( 'Classic Commerce pages', 'classic-commerce' ); ?></h2></th>
		</tr>
	</thead>
	<tbody>
		<?php
		$alt = 1;
		foreach ( $wp_pages as $_page ) {
			$found_error = false;

			if ( $_page['page_id'] ) {
				/* Translators: %s: page name. */
				$page_name = '<a href="' . get_edit_post_link( $_page['page_id'] ) . '" aria-label="' . sprintf( esc_html__( 'Edit %s page', 'classic-commerce' ), esc_html( $_page['page_name'] ) ) . '">' . esc_html( $_page['page_name'] ) . '</a>';
			} else {
				$page_name = esc_html( $_page['page_name'] );
			}

			echo '<tr><td data-export-label="' . esc_attr( $page_name ) . '">' . wp_kses_post( $page_name ) . ':</td>';
			/* Translators: %s: page name. */
			echo '<td class="help">' . wc_help_tip( sprintf( esc_html__( 'The URL of your %s page (along with the Page ID).', 'classic-commerce' ), $page_name ) ) . '</td><td>';

			// Page ID check.
			if ( ! $_page['page_set'] ) {
				echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . esc_html__( 'Page not set', 'classic-commerce' ) . '</mark>';
				$found_error = true;
			} elseif ( ! $_page['page_exists'] ) {
				echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . esc_html__( 'Page ID is set, but the page does not exist', 'classic-commerce' ) . '</mark>';
				$found_error = true;
			} elseif ( ! $_page['page_visible'] ) {
				/* Translators: %s: docs link. */
				echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . wp_kses_post( sprintf( __( 'Page visibility should be <a href="%s" target="_blank">public</a>', 'classic-commerce' ), 'https://wordpress.org/support/article/content-visibility/' ) ) . '</mark>';
				$found_error = true;
			} else {
				// Shortcode check.
				if ( $_page['shortcode_required'] ) {
					if ( ! $_page['shortcode_present'] ) {
						echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( 'Page does not contain the shortcode.', 'classic-commerce' ), esc_html( $_page['shortcode'] ) ) . '</mark>';
						$found_error = true;
					}
				}
			}

			if ( ! $found_error ) {
				echo '<mark class="yes">#' . absint( $_page['page_id'] ) . ' - ' . esc_html( str_replace( home_url(), '', get_permalink( $_page['page_id'] ) ) ) . '</mark>';
			}

			echo '</td></tr>';
		}
		?>
	</tbody>
</table>
<table class="wc_status_table widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3" data-export-label="Theme"><h2><?php esc_html_e( 'Theme', 'classic-commerce' ); ?></h2></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td data-export-label="Name"><?php esc_html_e( 'Name', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'The name of the current active theme.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td><?php echo esc_html( $theme['name'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Version"><?php esc_html_e( 'Version', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'The installed version of the current active theme.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td><?php echo esc_html( $theme['version'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Author URL"><?php esc_html_e( 'Author URL', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'The theme developers URL.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td><?php echo esc_html( $theme['author_url'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Child Theme"><?php esc_html_e( 'Child theme', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'Displays whether or not the current theme is a child theme.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td>
				<?php
				if ( $theme['is_child_theme'] ) {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} else {
					/* Translators: %s docs link. */
					echo '<span class="dashicons dashicons-no-alt"></span> &ndash; ' . wp_kses_post( sprintf( __( 'If you are modifying Classic Commerce on a parent theme that you did not build personally we recommend using a child theme. See: <a href="%s" target="_blank">How to create a child theme</a>', 'classic-commerce' ), 'https://developer.wordpress.org/themes/advanced-topics/child-themes/' ) );
				}
				?>
				</td>
		</tr>
		<?php if ( $theme['is_child_theme'] ) : ?>
			<tr>
				<td data-export-label="Parent Theme Name"><?php esc_html_e( 'Parent theme name', 'classic-commerce' ); ?>:</td>
				<td class="help"><?php echo wc_help_tip( esc_html__( 'The name of the parent theme.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td><?php echo esc_html( $theme['parent_name'] ); ?></td>
			</tr>
			<tr>
				<td data-export-label="Parent Theme Version"><?php esc_html_e( 'Parent theme version', 'classic-commerce' ); ?>:</td>
				<td class="help"><?php echo wc_help_tip( esc_html__( 'The installed version of the parent theme.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td><?php echo esc_html( $theme['parent_version'] ); ?></td>
			</tr>
			<tr>
				<td data-export-label="Parent Theme Author URL"><?php esc_html_e( 'Parent theme author URL', 'classic-commerce' ); ?>:</td>
				<td class="help"><?php echo wc_help_tip( esc_html__( 'The parent theme developers URL.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
				<td><?php echo esc_html( $theme['parent_author_url'] ); ?></td>
			</tr>
		<?php endif ?>
		<tr>
			<td data-export-label="Classic Commerce Support"><?php esc_html_e( 'Classic Commerce support', 'classic-commerce' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( esc_html__( 'Displays whether or not the current active theme declares Classic Commerce support.', 'classic-commerce' ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></td>
			<td>
				<?php
				if ( ! $theme['has_woocommerce_support'] ) {
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . esc_html__( 'Not declared', 'classic-commerce' ) . '</mark>';
				} else {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				}
				?>
			</td>
		</tr>
	</tbody>
</table>
<table class="wc_status_table widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3" data-export-label="Templates"><h2><?php esc_html_e( 'Templates', 'classic-commerce' ); ?><?php echo wc_help_tip( esc_html__( 'This section shows any files that are overriding the default Classic Commerce template pages.', 'classic-commerce' ) ); ?></h2></th>
		</tr>
	</thead>
	<tbody>
		<?php if ( $theme['has_woocommerce_file'] ) : ?>
		<tr>
			<td data-export-label="Archive Template"><?php esc_html_e( 'Archive template', 'classic-commerce' ); ?>:</td>
			<td class="help">&nbsp;</td>
			<td><?php esc_html_e( 'Your theme has a classic-commerce.php file, you will not be able to override the classic-commerce/archive-product.php custom template since classic-commerce.php has priority over archive-product.php. This is intended to prevent display issues.', 'classic-commerce' ); ?></td>
		</tr>
		<?php endif ?>
		<?php if ( ! empty( $theme['overrides'] ) ) : ?>
			<tr>
				<td data-export-label="Overrides"><?php esc_html_e( 'Overrides', 'classic-commerce' ); ?></td>
				<td class="help">&nbsp;</td>
				<td>
					<?php
					$total_overrides = count( $theme['overrides'] );
					for ( $i = 0; $i < $total_overrides; $i++ ) {
						$override = $theme['overrides'][ $i ];
						
                        echo esc_html( $override['file'] );
						
						if ( ( count( $theme['overrides'] ) - 1 ) !== $i ) {
							echo ', ';
						}
						echo '<br />';
					}
					?>
				</td>
			</tr>
		<?php else : ?>
			<tr>
				<td data-export-label="Overrides"><?php esc_html_e( 'Overrides', 'classic-commerce' ); ?>:</td>
				<td class="help">&nbsp;</td>
				<td>&ndash;</td>
			</tr>
		<?php endif; ?>
        <?php if ( true === $theme['has_outdated_templates'] ) : ?>
			<tr>
				<td data-export-label="Outdated Templates"><?php esc_html_e( 'Outdated templates', 'classic-commerce' ); ?>:</td>
				<td class="help">&nbsp;</td>
				<td>
					<mark class="error">
						<span class="dashicons dashicons-warning"></span>
					</mark>
					<a href="https://classiccommerce.cc/docs/usage-and-maintenance/fix-outdated-templates/" target="_blank">
						<?php esc_html_e( 'Learn how to update', 'classic-commerce' ); ?>
					</a> |
					<mark class="info">
						<span class="dashicons dashicons-info"></span>
					</mark>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-status&tab=tools' ) ); ?>">
						<?php esc_html_e( 'Clear system status theme info cache', 'classic-commerce' ); ?>
					</a>
				</td>
			</tr>
		<?php endif; ?>
	</tbody>
</table>

<?php do_action( 'woocommerce_system_status_report' ); ?>

<table class="wc_status_table widefat" cellspacing="0">
	<thead>
	<tr>
		<th colspan="3" data-export-label="Status report information"><h2><?php esc_html_e( 'Status report information', 'classic-commerce' ); ?><?php echo wc_help_tip( esc_html__( 'This section shows information about this status report.', 'classic-commerce' ) ); ?></h2></th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td data-export-label="Generated at"><?php esc_html_e( 'Generated at', 'classic-commerce' ); ?>:</td>
		<td class="help">&nbsp;</td>
		<td><?php echo esc_html( current_time( 'Y-m-d H:i:s P' ) ); ?></td>

	</tr>
	</tbody>
</table>
