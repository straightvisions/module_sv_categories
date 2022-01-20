<?php
	namespace sv100_companion;

	class sv_categories extends modules {
		public function init() {
			add_action('init', array($this, 'load'), 5);
		}
		public function load(){
			if($this->get_instance('sv100')) {
				add_action('init', function () {
					// filter name: sv100_companion_sv_categories_custom_fields
					foreach (apply_filters($this->get_prefix('custom_fields'), array('category')) as $taxonomy) {
						add_action($taxonomy . '_edit_form_fields', array($this, 'edit_category_form_fields'));
						add_action($taxonomy . '_add_form_fields', array($this, 'edit_category_form_fields'));
						add_action('edited_' . $taxonomy, array($this, 'edited_category'));
					}
				});

				if (!is_admin()) {
					add_action('pre_get_posts', array($this, 'pre_get_posts'));
				} else {
					add_action('admin_enqueue_scripts', array($this, 'media_upload'));
				}
			}
		}
		public function media_upload(){
			if(get_current_screen() && get_current_screen()->id === 'edit-category'){
				$this->get_script('media_upload')
					->set_type('js')
					->set_path('lib/js/backend/media_upload.js')
					->set_is_backend()
					->set_is_enqueued();
			}
		}
		public function pre_get_posts($query){
			$order_by		= get_term_meta(get_queried_object_id(), 'sv100_companion_sv_categories_order_by', true);
			$order			= get_term_meta(get_queried_object_id(), 'sv100_companion_sv_categories_order', true);

			if( $query->is_main_query() && ! is_admin() && $order_by && $order ) {
				$query->set( 'orderby', $order_by );
				$query->set( 'order', $order );
			}
		}

		public function get_template_style(): string{
			$template_style = get_term_meta( get_queried_object_id(), $this->get_prefix( 'template_style' ), true );

			return $template_style ? $template_style : '';
		}

		public function edit_category_form_fields( $term ) {
			$order_by			= get_term_meta( $term->term_id, $this->get_prefix( 'order_by' ), true );
			$order				= get_term_meta( $term->term_id, $this->get_prefix( 'order' ), true );
			$template_style		= get_term_meta( $term->term_id, $this->get_prefix( 'template_style' ), true );
			$page				= get_term_meta( $term->term_id, $this->get_prefix( 'page' ), true );
			$featured_image		= get_term_meta( $term->term_id, $this->get_prefix( 'featured_image' ), true );
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
											if(!isset($extra_style['entry_label']) || strlen($extra_style['entry_label']) === 0){
												continue;
											}
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
			<tr class="form-field term-<?php echo $this->get_prefix( 'featured_image' ); ?>-wrap">
				<th scope="row"><label for="<?php echo $this->get_prefix( 'featured_image' ); ?>"><?php _e( 'Category Featured Image', 'sv100_companion' ); ?></label></th>
				<td>
					<input type="hidden" id="<?php echo $this->get_prefix( 'featured_image' ); ?>" name="<?php echo $this->get_prefix( 'featured_image' ); ?>" class="custom_media_url" value="<?php echo $featured_image; ?>">
					<div id="<?php echo $this->get_prefix( 'featured_image_wrapper' ); ?>">
						<?php if ( $featured_image ) { ?>
							<?php echo wp_get_attachment_image ( $featured_image, 'thumbnail' ); ?>
						<?php } ?>
					</div>
					<p>
						<input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php _e( 'Add Image', 'sv100_companion' ); ?>" />
						<input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php _e( 'Remove Image', 'sv100_companion' ); ?>" />
					</p>
					<p class="description">Select Featured Image for Category</p>
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
			if( isset( $_POST[$this->get_prefix( 'featured_image' )] ) && '' !== $_POST[$this->get_prefix( 'featured_image' )] ){
				update_term_meta( $_POST['tag_ID'], $this->get_prefix( 'featured_image' ), $_POST[$this->get_prefix( 'featured_image' )] );
			}
/*
			if ( isset( $_POST[ $this->get_prefix( 'page' ) ] ) ) {
				update_term_meta( $_POST['tag_ID'], $this->get_prefix( 'page' ), $_POST[ $this->get_prefix( 'page' ) ] );
			}
*/
		}
	}