<?php
/**
 * Order Notes
 *
 * @package     ClassicCommerce/Admin/Meta Boxes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Meta_Box_Order_Notes Class.
 */
class WC_Meta_Box_Order_Notes {

	/**
	 * Output the metabox.
	 *
	 * @param WP_Post $post Post object.
	 */
	public static function output( $post ) {
		global $post;

		$args = array(
			'order_id' => $post->ID,
		);

		$notes = wc_get_order_notes( $args );

		include __DIR__ . '/views/html-order-notes.php';
		?>
		<div class="add_note">
			<p>
				<label for="add_order_note"><?php esc_html_e( 'Add note', 'classic-commerce' ); ?> <?php echo wc_help_tip( __( 'Add a note for your reference, or add a customer note (the user will be notified).', 'classic-commerce' ) ); ?></label>
				<textarea type="text" name="order_note" id="add_order_note" class="input-text" cols="20" rows="5"></textarea>
			</p>
			<p>
				<label for="order_note_type" class="screen-reader-text"><?php esc_html_e( 'Note type', 'classic-commerce' ); ?></label>
				<select name="order_note_type" id="order_note_type">
					<option value=""><?php esc_html_e( 'Private note', 'classic-commerce' ); ?></option>
					<option value="customer"><?php esc_html_e( 'Note to customer', 'classic-commerce' ); ?></option>
				</select>
				<button type="button" class="add_note button"><?php esc_html_e( 'Add', 'classic-commerce' ); ?></button>
			</p>
		</div>
		<?php
	}
}
