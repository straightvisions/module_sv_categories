<?php
	namespace sv100_companion;
	
	/**
	 * @version         4.000
	 * @author			straightvisions GmbH
	 * @package			sv100
	 * @copyright		2019 straightvisions GmbH
	 * @link			https://straightvisions.com
	 * @since			1.000
	 * @license			See license.txt or https://straightvisions.com
	 */
	
	class sv_categories extends modules {
		public function init() {
			// @todo Add function to save new order_by field on category creation
			//add_action( 'category_add_form_fields', array( $this, 'category_add_form_fields' ) );
			add_action( 'edit_category_form_fields', array( $this, 'edit_category_form_fields' ) );
			add_action( 'edited_category', array( $this, 'edited_category' ) );
		}
		
		public function category_add_form_fields() {
			$order_by = get_term_meta( $_POST['tag_ID'], '_order_by', true );
			?>
			<div class="form-field term-order-by-wrap">
				<label for="order_by"><?php _e( 'Order by', 'sv100_companion' ); ?></label>
				<select name="order_by" id="order_by" class="postform">
					<option value="date" <?php echo $order_by === 'date' ? 'selected' : ''; ?>>
						<?php _e( 'Release date', 'sv100_companion' ); ?>
					</option>
					<option value="title" <?php echo $order_by === 'title' ? 'selected' : ''; ?>>
						<?php _e( 'Title', 'sv100_companion' ); ?>
					</option>
				</select>
			</div>
			<?php
		}
		
		public function edit_category_form_fields( $term ) {
			$order_by = get_term_meta( $term->term_id, '_order_by', true );
			?>
			<tr class="form-field term-order-by-wrap">
				<th scope="row"><label for="order_by"><?php _e( 'Order by', 'sv100_companion' ); ?></label></th>
				<td>
					<select name="order_by" id="order_by" class="postform">
						<option value="date" <?php echo $order_by === 'date' ? 'selected' : ''; ?>>
							<?php _e( 'Release date', 'sv100_companion' ); ?>
						</option>
						<option value="title" <?php echo $order_by === 'title' ? 'selected' : ''; ?>>
							<?php _e( 'Title', 'sv100_companion' ); ?>
						</option>
					</select>
				</td>
			</tr>
			<?php
		}
		
		public function edited_category() {
			if ( isset( $_POST['order_by'] ) ) {
				update_term_meta( $_POST['tag_ID'], '_order_by', $_POST['order_by'] );
			}
		}
	}