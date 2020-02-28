<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	if (isset($_POST['submit'])) {

		if ( ! isset( $_POST['ticketmachine_settings_page_form_nonce'] ) || ! wp_verify_nonce( $_POST['ticketmachine_settings_page_form_nonce'], 'ticketmachine_action_save_settings' ) ) {
			print 'Sorry, your nonce did not verify.';
			exit;
		} else {
			$post = (object)$_POST;

			
			echo $post->show_boxes;
			//sanitize
			$post->show_boxes = absint($post->show_boxes);

			//validate
			if (!empty($post->show_boxes)){
				$post->show_boxes = true;
			}else{
				$post->show_boxes = false;
			}

			if(!in_array($post->event_grouping, array("Month", "Year","None"))){
				$errors[] =  'Sorry, your groups did not verify.';
			}

			$save_array =
				array(
					"show_boxes" => (bool)$post->show_boxes,
					"event_grouping" => sanitize_text_field($post->event_grouping),
				);
			if (!empty($ticketmachine_config)) {
				$wpdb->update(
					$wpdb->prefix . "ticketmachine_config",
					$save_array,
					array('id' => $ticketmachine_config->id)
				);
				?>
				<div class="notice notice-success is-dismissable">
					<p><?php esc_html_e('Saved', 'ticketmachine'); ?>!</p>
				</div>
				<?php
			}else{
				?>
				<div class="notice notice-error is-dismissable">
					<p><?php esc_html_e('Something went wrong', 'ticketmachine'); ?>!</p>
				</div>
				<?php
			}
			$ticketmachine_config = (object)$_POST;
		}
	}
?>

<table class="form-table">
	<tbody>
		<tr>
			<th><label><?php esc_html_e('Activate Boxes?', 'ticketmachine'); ?></label></th>
            <td><input name="show_boxes" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_boxes){ ?>checked<?php } ?>/></td>
		</tr>
		<tr>
			<th><label><?php esc_html_e('Group events by', 'ticketmachine'); ?></label></th>
            <td>
				<select name="event_grouping">
					<option value="Month" <?php if($ticketmachine_config->event_grouping == "Month"){ ?>selected<?php } ?>><?php esc_html_e('Month', 'ticketmachine'); ?></option>
					<option value="Year" <?php if($ticketmachine_config->event_grouping == "Year" || !isset($ticketmachine_config->event_grouping)){ ?>selected<?php } ?>><?php esc_html_e('Year', 'ticketmachine'); ?></option>
					<option value="None" <?php if($ticketmachine_config->event_grouping == "None"){ ?>selected<?php } ?>><?php esc_html_e('None', 'ticketmachine'); ?></option>
				</select>
			</td>
		</tr>

	</tbody>
</table>