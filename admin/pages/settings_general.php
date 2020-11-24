<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	if (isset($_POST['submit'])) {

		if ( ! isset( $_POST['ticketmachine_settings_page_form_nonce'] ) || ! wp_verify_nonce( $_POST['ticketmachine_settings_page_form_nonce'], 'ticketmachine_action_save_settings' ) ) {
			print 'Sorry, your nonce did not verify.';
			exit;
		} else {
			$tm_post = (object)$_POST;
			$errors = array();

			//validate
			if(empty($tm_post->events_slug_id) || !ctype_digit($tm_post->events_slug_id)){
				$errors[] = "Events page can not be empty";
			}

			if(empty($tm_post->event_slug_id) || !ctype_digit($tm_post->events_slug_id)){
				$errors[] = "Event page can not be empty";
			}

			if(empty($tm_post->privacy_slug_id) || !ctype_digit($tm_post->privacy_slug_id)){
				$errors[] = "Event page can not be empty";
			}

			$save_array = 
				array(
					"events_slug_id" => absint($tm_post->events_slug_id),
					"event_slug_id" => absint($tm_post->event_slug_id),
					"privacy_slug_id" => absint($tm_post->privacy_slug_id)
				);
			if (!empty($ticketmachine_config) && empty($errors)) {
				$wpdb->update(
					$wpdb->prefix . "ticketmachine_config",
					$save_array,
					array('id' => $ticketmachine_config->id)
				);
				?>
				<div class="notice notice-success is-dismissable">
					<p><?php esc_html_e('Saved', 'ticketmachine-event-manager'); ?>!</p>
				</div>
				<?php
				$ticketmachine_config = $tm_post;
			}else{
				?>
				<div class="notice notice-error is-dismissable">
					<p><?php esc_html_e('Something went wrong', 'ticketmachine-event-manager'); ?>!</p>
				</div>
				<?php
			}
		}
	}
?>

<table class="form-table">
	<tbody>
		<tr>
			<th><label><?php esc_html_e('Events overview page', 'ticketmachine-event-manager'); ?></label></th>
			<td>
				<select name="events_slug_id">
					<?php
						if( $pages = get_pages() ){
							foreach( $pages as $page ){
								$selected = ($page->ID == $ticketmachine_config->events_slug_id) ? 'selected="selected"' : '';
								echo '<option value="' . $page->ID . '" ' . $selected . '>' . $page->post_title . '</option>';
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th><label><?php esc_html_e('Event detail page', 'ticketmachine-event-manager'); ?></label></th>
			<td>
				<select name="event_slug_id">
					<?php
						if( $pages = get_pages() ){
							foreach( $pages as $page ){
								$selected = ($page->ID == $ticketmachine_config->event_slug_id) ? 'selected="selected"' : '';
								echo '<option value="' . $page->ID . '" ' . $selected . '>' . $page->post_title . '</option>';
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th><label><?php esc_html_e('Privacy policy page', 'ticketmachine-event-manager'); ?></label></th>
			<td>
				<select name="privacy_slug_id">
					<?php
						if( $pages = get_pages() ){
							foreach( $pages as $page ){
								$selected = ($page->ID == $ticketmachine_config->privacy_slug_id) ? 'selected="selected"' : '';
								echo '<option value="' . $page->ID . '" ' . $selected . '>' . $page->post_title . '</option>';
							}
						}
					?>
				</select>
			</td>
		</tr>

		<label class="hidden" style="display:none;">
			<?php echo esc_html__('I have read and agree to the', 'ticketmachine-event-manager') . ' ' . esc_html__('Privacy Policy', 'ticketmachine-event-manager'); ?>
		</label>

	</tbody>
</table>