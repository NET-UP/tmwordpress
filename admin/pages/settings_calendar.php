<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	if (isset($_POST['submit'])) {

		if ( ! isset( $_POST['ticketmachine_settings_page_form_nonce'] ) || ! wp_verify_nonce( $_POST['ticketmachine_settings_page_form_nonce'], 'ticketmachine_action_save_settings' ) ) {
			print 'Sorry, your nonce did not verify.';
			exit;
		} else {
			$post = (object)$_POST;
			$errors = array();

			//validate
			if (!empty($post->show_calendar)){
				$post->show_calendar = true;
			}else{
				$post->show_calendar = false;
			}

			$save_array = 
				array(
					"show_calendar" => (bool)$post->show_calendar,
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
				$ticketmachine_config = $post;
			}else{
				?>
				<div class="notice notice-error is-dismissable">
					<p><?php esc_html_e('Something went wrong', 'ticketmachine'); ?>!</p>
				</div>
				<?php
			}
		}
	}
?>

<table class="form-table">
	<tbody>
		<tr>
			<th><label><?php esc_html_e('Activate calendar?', 'ticketmachine'); ?></label></th>
            <td><input name="show_calendar" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_calendar){ ?>checked <?php } ?>/></td>
		</tr>

	</tbody>
</table>