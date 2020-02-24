<?php
	if (isset($_POST['submit'])) {

        if (isset($_POST['show_calendar'])){
            $_POST['show_calendar'] = 1;
        }

		$save_array = 
			array(
				"show_calendar" => $_POST['show_calendar'],
			);
		if (!empty($ticketmachine_config)) {
			$wpdb->update(
				$wpdb->prefix . "ticketmachine_config",
				$save_array,
				array('id' => $ticketmachine_config->id)
			);
			?>
			<div class="notice notice-success is-dismissable">
				<p><?php echo __('Saved', 'ticketmachine'); ?>!</p>
			</div>
			<?php
		}else{
			?>
			<div class="notice notice-error is-dismissable">
				<p><?php echo __('Something went wrong', 'ticketmachine'); ?>!</p>
			</div>
			<?php
		}
		$ticketmachine_config = (object)$_POST;
	}
?>

<table class="form-table">
	<tbody>
		<tr>
			<th><label><?php echo __('Activate calendar?', 'ticketmachine'); ?></label></th>
            <td><input name="show_calendar" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_calendar){ ?>checked <?php } ?>/></td>
		</tr>

	</tbody>
</table>