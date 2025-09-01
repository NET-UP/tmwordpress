<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	if (isset($_POST['submit'])) {

		if ( ! isset( $_POST['ticketmachine_settings_page_form_nonce'] ) || ! wp_verify_nonce( $_POST['ticketmachine_settings_page_form_nonce'], 'ticketmachine_action_save_settings' ) ) {
			print 'Sorry, your nonce did not verify.';
			exit;
		} else {
			$ticketmachine_post = (object)$_POST;
			$errors = array();

			//validate
			if (!empty($ticketmachine_post->show_boxes)){
				$ticketmachine_post->show_boxes = true;
			}else{
				$ticketmachine_post->show_boxes = false;
			}

			if(!in_array($ticketmachine_post->event_grouping, array("Month", "Year","None"))){
				$errors[] =  'Sorry, your groups did not verify.';
			}

			$save_array =
				array(
					"show_boxes" => (bool)$ticketmachine_post->show_boxes,
					"event_grouping" => sanitize_text_field($ticketmachine_post->event_grouping),
					"event_box_image_ratio" => (string)$ticketmachine_post->event_box_image_ratio
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
				$ticketmachine_config = $ticketmachine_post;
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

		<?php 
			$image_ratios = [
				[
					'value' => '1:1',
					'label' => __('Square', 'ticketmachine-event-manager')
				],
				[
					'value' => '3:4',
					'label' => __('Landscape', 'ticketmachine-event-manager')
				],
				[
					'value' => '4:3',
					'label' => __('Portrait', 'ticketmachine-event-manager')
				],
				[
					'value' => '16:9',
					'label' => __('Wide', 'ticketmachine-event-manager')
				]
			];
		?>
		<tr>
			<th><label><?php esc_html_e('Image ratio', 'ticketmachine-event-manager'); ?></label></th>
			<td>
				<select name="event_box_image_ratio">
					<?php
					foreach ($image_ratios as $image_ratio) {
						$selected = ($image_ratio['value'] == $ticketmachine_config->event_box_image_ratio) ? 'selected="selected"' : '';
						echo '<option value="' . $image_ratio['value'] . '" ' . $selected . '>' . $image_ratio['label'] . ' (' . $image_ratio['value'] . ')</option>';
					}
					?>
				</select>
			</td>
		</tr>
	</tbody>
</table>