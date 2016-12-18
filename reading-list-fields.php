<?php
	
	function readinglist_add_metabox_dates() {

		add_meta_box(
			'bookinfo',
			'Book Information',
			'readinglist_book_info_callback',
			'book',
			'normal',
			'core'
		);
	}
	add_action('add_meta_boxes', 'readinglist_add_metabox_dates');


	function readinglist_book_info_callback( $post ) {
		wp_nonce_field(basename(__FILE__), 'readinglist_nonce');

		$readinglist_stored_meta = get_post_meta($post->ID);

?>
		<div>
			<div class="meta-row">
				<div class="meta-th">

					<label class="readinglist-meta-label" for="author">Author(s)</label>
					<input class="readinglist-meta-input" type="text" name="author" id="author" value="<?php if(!empty($readinglist_stored_meta['author'])) echo esc_attr($readinglist_stored_meta['author'][0]); ?>"\>

					<label class="readinglist-meta-label" for="start-date">Start Date:</label>
					<input class="readinglist-meta-input" onchange="enable_calculate_btn()" type="date" name="start_date" id="start_date" value="<?php if(!empty($readinglist_stored_meta['start_date'])) echo esc_attr($readinglist_stored_meta['start_date'][0]); ?>"\>
					
					<label class="readinglist-meta-label" for="end-date">End Date:</label>
					<input class="readinglist-meta-input" onchange="enable_calculate_btn()" type="date" name="end_date" id="end_date" value="<?php if(!empty($readinglist_stored_meta['end_date'])) echo esc_attr($readinglist_stored_meta['end_date'][0]); ?>"\>

					<label class="readinglist-meta-label" for="isbn">ISBN</label>
					<input class="readinglist-meta-input" type="text" name="isbn" id="isbn" value="<?php if(!empty($readinglist_stored_meta['isbn'])) echo esc_attr($readinglist_stored_meta['isbn'][0]); ?>"\>

					<label class="readinglist-meta-label" for="pagecount"># of Pages</label>
					<input class="readinglist-meta-input" onchange="enable_calculate_btn()" type="number" name="pagecount" id="pagecount" value="<?php if(!empty($readinglist_stored_meta['pagecount'])) echo esc_attr($readinglist_stored_meta['pagecount'][0]); ?>"\>

					<label class="readinglist-meta-label" for="amazonlink">Amazon Link</label>
					<input class="readinglist-meta-input" type="text" name="amazonlink" id="amazonlink" value="<?php if(!empty($readinglist_stored_meta['amazonlink'])) echo esc_attr($readinglist_stored_meta['amazonlink'][0]); ?>"\>

					<label class="readinglist-meta-label" for="averagepages">Pages/Day</label>
					<input class="readinglist-meta-input" type="text" name="averagepages" id="averagepages" value="<?php if(!empty($readinglist_stored_meta['averagepages'])) echo esc_attr($readinglist_stored_meta['averagepages'][0]); ?>"\>
				</div>
			</div>
		</div>
<?php
	}

	function readinglist_meta_save($post_id) {

		$is_autosave = wp_is_post_autosave($post_id);
		$is_revision = wp_is_post_revision($post_id);
		$is_valid_nonce = (isset($_POST['readinglist_nonce']) && wp_verify_nonce($_POST['readinglist_nonce'], basename(__FILE__))) ? 'true' : 'false';

		if($is_autosave || $is_revision || !$is_valid_nonce) {
			return;
		}

		if(isset($_POST['author'])) {
			update_post_meta($post_id, 'author', sanitize_text_field($_POST['author']));
		}
		if(isset($_POST['start_date'])) {
			update_post_meta($post_id, 'start_date', sanitize_text_field($_POST['start_date']));
		}
		if(isset($_POST['end_date'])) {
			update_post_meta($post_id, 'end_date', sanitize_text_field($_POST['end_date']));
		}
		if(isset($_POST['isbn'])) {
			update_post_meta($post_id, 'isbn', sanitize_text_field($_POST['isbn']));
		}
		if(isset($_POST['pagecount'])) {
			update_post_meta($post_id, 'pagecount', sanitize_text_field($_POST['pagecount']));
		}
		if(isset($_POST['amazonlink'])) {
			update_post_meta($post_id, 'amazonlink', sanitize_text_field($_POST['amazonlink']));
		}
		if(isset($_POST['averagepages'])) {
			update_post_meta($post_id, 'averagepages', sanitize_text_field($_POST['averagepages']));
		}



	}
	add_action('save_post', 'readinglist_meta_save');

?>