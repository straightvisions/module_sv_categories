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
			add_action( 'edit_category_form_fields', array( $this, 'edit_category_form_fields' ) );
			add_action( 'edited_category', array( $this, 'edited_category' ) );
		}

		public function edit_category_form_fields( $term ) {
			$order_by = get_term_meta( $term->term_id, $this->get_prefix( 'order_by' ), true );
			$order = get_term_meta( $term->term_id, $this->get_prefix( 'order' ), true );
			?>
			<tr class="form-field term-<?php echo $this->get_prefix( 'order_by' ); ?>-wrap">
				<th scope="row"><label for="<?php echo $this->get_prefix( 'order_by' ); ?>"><?php _e( 'Order by', 'sv100_companion' ); ?></label></th>
				<td>
					<select name="<?php echo $this->get_prefix( 'order_by' ); ?>" id="<?php echo $this->get_prefix( 'order_by' ); ?>" class="postform">
						<option value="date" <?php echo $order_by === 'date' ? 'selected' : ''; ?>>
							<?php _e( 'Date', 'sv100_companion' ); ?>
						</option>
						<option value="title" <?php echo $order_by === 'title' ? 'selected' : ''; ?>>
							<?php _e( 'Title', 'sv100_companion' ); ?>
						</option>
					</select>
				</td>
			</tr>
            <tr class="form-field term-<?php echo $this->get_prefix( 'order' ); ?>-wrap">
                <th scope="row"><label for="<?php echo $this->get_prefix( 'order' ); ?>"><?php _e( 'Order', 'sv100_companion' ); ?></label></th>
                <td>
                    <select name="<?php echo $this->get_prefix( 'order' ); ?>" id="<?php echo $this->get_prefix( 'order' ); ?>" class="postform">
                        <option value="DESC" <?php echo $order === 'DESC' ? 'selected' : ''; ?>>
							<?php _e( 'Descending', 'sv100_companion' ); ?>
                        </option>
                        <option value="ASC" <?php echo $order === 'ASC' ? 'selected' : ''; ?>>
							<?php _e( 'Ascending', 'sv100_companion' ); ?>
                        </option>
                    </select>
                </td>
            </tr>
			<?php
		}
		
		public function edited_category() {
			if ( isset( $_POST[ $this->get_prefix( 'order_by' ) ] ) ) {
				update_term_meta( $_POST['tag_ID'], $this->get_prefix( 'order_by' ), $_POST[ $this->get_prefix( 'order_by' ) ] );
			}
			
			if ( isset( $_POST[ $this->get_prefix( 'order' ) ] ) ) {
				update_term_meta( $_POST['tag_ID'], $this->get_prefix( 'order' ), $_POST[ $this->get_prefix( 'order' ) ] );
			}
		}
	}