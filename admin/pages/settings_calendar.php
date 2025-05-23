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
			if (!empty($ticketmachine_post->show_calendar)){
				$ticketmachine_post->show_calendar = true;
			}else{
				$ticketmachine_post->show_calendar = false;
			}

			if (!empty($ticketmachine_post->show_calendar_start_time)){
				$ticketmachine_post->show_calendar_start_time = true;
			}else{
				$ticketmachine_post->show_calendar_start_time = false;
			}


			$save_array = 
				array(
					"show_calendar" => (bool)$ticketmachine_post->show_calendar,
					"show_calendar_start_time" => (bool)$ticketmachine_post->show_calendar_start_time,
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
			<th><label><?php esc_html_e('Activate calendar?', 'ticketmachine-event-manager'); ?></label></th>
            <td><input name="show_calendar" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_calendar){ ?>checked <?php } ?>/></td>
		</tr>
		<tr>
			<th><label><?php esc_html_e('Show event start time?', 'ticketmachine-event-manager'); ?></label></th>
            <td><input name="show_calendar_start_time" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_calendar_start_time){ ?>checked <?php } ?>/></td>
		</tr>

	</tbody>
</table>