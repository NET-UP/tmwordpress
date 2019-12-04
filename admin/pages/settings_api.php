<?php
	if (isset($_POST['submit'])) {
		$save_array = 
			array(
				"api_client_id" => $_POST['api_client_id'],
				"api_client_secret" => $_POST['api_client_secret'],
				"organizer" => $_POST['organizer']
			);
		if (!empty($tm_config)) {
			$wpdb->update(
				$wpdb->prefix . "ticketmachine_config",
				$save_array,
				array('id' => $tm_config->id)
			);
			?>
			<div class="notice notice-success is-dismissable">
				<p><?php echo __('Gespeichert!'); ?></p>
        		<button type="button" class="notice-dismiss"></button>
			</div>
			<?php
		}else{
			?>
			<div class="notice notice-error is-dismissable">
				<p><?php echo __('Etwas ist schiefgelaufen.'); ?></p>
        		<button type="button" class="notice-dismiss"></button>
			</div>
			<?php
		}
		$tm_config = (object)$_POST;
	}
?>

<table class="form-table">
	<tbody>
		<tr>
			<th><label><?php echo __('API Client ID', 'ticketmachine'); ?></label></th>
			<td><input name="api_client_id" type="text" value="<?php echo $tm_config->api_client_id; ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label><?php echo __('API Client Secret', 'ticketmachine'); ?></label></th>
			<td><input name="api_client_secret" type="text" value="<?php echo $tm_config->api_client_secret; ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label><?php echo __('Organizer-Kürzel', 'ticketmachine'); ?></label></th>
			<td><input name="organizer" type="text" value="<?php echo $tm_config->organizer; ?>" class="regular-text" /></td>
		</tr>

	</tbody>
</table>