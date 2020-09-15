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
			if (!empty($tm_post->show_boxes)){
				$tm_post->show_boxes = true;
			}else{
				$tm_post->show_boxes = false;
			}

			if(!in_array($tm_post->event_grouping, array("Month", "Year","None"))){
				$errors[] =  'Sorry, your groups did not verify.';
			}

			$save_array =
				array(
					"show_boxes" => (bool)$tm_post->show_boxes,
					"event_grouping" => sanitize_text_field($tm_post->event_grouping),
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
			<th><label><?php esc_html_e('Activate Boxes?', 'ticketmachine-event-manager'); ?></label></th>
            <td><input name="show_boxes" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_boxes){ ?>checked<?php } ?>/></td>
		</tr>
		<tr>
			<th><label><?php esc_html_e('Group events by', 'ticketmachine-event-manager'); ?></label></th>
            <td>
				<select name="event_grouping">
					<option value="Month" <?php if($ticketmachine_config->event_grouping == "Month"){ ?>selected<?php } ?>><?php esc_html_e('Month', 'ticketmachine-event-manager'); ?></option>
					<option value="Year" <?php if($ticketmachine_config->event_grouping == "Year" || !isset($ticketmachine_config->event_grouping)){ ?>selected<?php } ?>><?php esc_html_e('Year', 'ticketmachine-event-manager'); ?></option>
					<option value="None" <?php if($ticketmachine_config->event_grouping == "None"){ ?>selected<?php } ?>><?php esc_html_e('None', 'ticketmachine-event-manager'); ?></option>
				</select>
			</td>
		</tr>

	</tbody>
</table>