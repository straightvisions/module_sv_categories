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
			add_action('init', function(){
				// filter name: sv100_companion_modules_sv_categories_custom_fields
				foreach(apply_filters($this->get_prefix('custom_fields'), array('category')) as $taxonomy){
					add_action( $taxonomy.'_edit_form_fields', array( $this, 'edit_category_form_fields' ) );
					add_action( $taxonomy.'_add_form_fields', array( $this, 'edit_category_form_fields' ) );

					add_action( 'edited_'.$taxonomy, array( $this, 'edited_category' ) );
				}
			});
		}

		public function edit_category_form_fields( $term ) {
			$order_by			= get_term_meta( $term->term_id, $this->get_prefix( 'order_by' ), true );
			$order				= get_term_meta( $term->term_id, $this->get_prefix( 'order' ), true );
			$template_style		= get_term_meta( $term->term_id, $this->get_prefix( 'template_style' ), true );
			$page				= get_term_meta( $term->term_id, $this->get_prefix( 'page' ), true );
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
			<tr class="form-field term-<?php echo $this->get_prefix( 'template_style' ); ?>-wrap">
				<th scope="row"><label for="<?php echo $this->get_prefix( 'template_style' ); ?>"><?php _e( 'Template Style', 'sv100_companion' ); ?></label></th>
				<td>
					<select name="<?php echo $this->get_prefix( 'template_style' ); ?>" id="<?php echo $this->get_prefix( 'template_style' ); ?>" class="postform">
						<option value=""><?php _e( 'Default', 'sv100_companion' ); ?></option>
						<?php
							if($this->get_instance('sv100')){
								if($this->get_instance('sv100')->get_module('sv_archive')){
									$extra_styles	= $this->get_instance('sv100')->get_module('sv_archive')->get_setting('extra_styles')->get_data();

									if(is_array($extra_styles) && count($extra_styles) > 0){
										foreach($extra_styles as $extra_style){
											?>
											<option value="<?php echo $extra_style['slug']; ?>" <?php echo $template_style === $extra_style['slug'] ? 'selected' : ''; ?>><?php echo $extra_style['entry_label']; ?></option>
											<?php
										}
									}
								}
							}
						?>
					</select>
				</td>
			</tr>
			<!--
			<tr class="form-field term-<?php echo $this->get_prefix( 'page' ); ?>-wrap">
				<th scope="row"><label for="<?php echo $this->get_prefix( 'page' ); ?>"><?php _e( 'Page', 'sv100_companion' ); ?></label></th>
				<td>
					<?php wp_dropdown_pages(array(
						'name'		=> $this->get_prefix( 'page' ),
						'id'		=> $this->get_prefix( 'page' ),
						'class'		=> 'postform',
						'selected'	=> $page,
						'show_option_none'		=> __('--- Default Archive Index ---')
					)); ?>
					<p class="description">Selected Page will be shown instead of archive.</p>
				</td>
			</tr>
			-->
			<?php
		}
		
		public function edited_category() {
			if ( isset( $_POST[ $this->get_prefix( 'order_by' ) ] ) ) {
				update_term_meta( $_POST['tag_ID'], $this->get_prefix( 'order_by' ), $_POST[ $this->get_prefix( 'order_by' ) ] );
			}
			
			if ( isset( $_POST[ $this->get_prefix( 'order' ) ] ) ) {
				update_term_meta( $_POST['tag_ID'], $this->get_prefix( 'order' ), $_POST[ $this->get_prefix( 'order' ) ] );
			}

			if ( isset( $_POST[ $this->get_prefix( 'template_style' ) ] ) ) {
				update_term_meta( $_POST['tag_ID'], $this->get_prefix( 'template_style' ), $_POST[ $this->get_prefix( 'template_style' ) ] );
			}
/*
			if ( isset( $_POST[ $this->get_prefix( 'page' ) ] ) ) {
				update_term_meta( $_POST['tag_ID'], $this->get_prefix( 'page' ), $_POST[ $this->get_prefix( 'page' ) ] );
			}
*/
		}
	}