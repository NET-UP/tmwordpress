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
			if (!empty($post->show_list)){
				$post->show_list = 1;
			}else{
				$post->show_list = 0;
			}

			$save_array = 
				array(
					"show_list" => $post->show_list
				);

			if (!empty($ticketmachine_config) && empty($errors)) {
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
				print_r($save_array);
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
			<th><label><?php esc_html_e('Activate List?', 'ticketmachine'); ?></label></th>
            <td><input name="show_list" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_list){ ?>checked<?php } ?>/></td>
		</tr>

	</tbody>
</table>