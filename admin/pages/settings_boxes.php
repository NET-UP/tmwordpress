<?php
	if (isset($_POST['submit'])) {

        if (isset($_POST['show_boxes'])){
            $_POST['show_boxes'] = 1;
        }

		$save_array = 
			array(
				"show_boxes" => $_POST['show_boxes'],
				"event_grouping" => $_POST['event_grouping'],
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
			<th><label><?php echo __('Activate Boxes?', 'ticketmachine'); ?></label></th>
            <td><input name="show_boxes" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_boxes){ ?>checked<?php } ?>/></td>
		</tr>
		<tr>
			<th><label><?php echo __('Group events by', 'ticketmachine'); ?></label></th>
            <td>
				<select name="event_grouping">
					<option value="Month" <?php if($ticketmachine_config->event_grouping == "Month"){ ?>selected<?php } ?>><?php echo __('Month', 'ticketmachine'); ?></option>
					<option value="Year" <?php if($ticketmachine_config->event_grouping == "Year" || !isset($ticketmachine_config->event_grouping)){ ?>selected<?php } ?>><?php echo __('Year', 'ticketmachine'); ?></option>
					<option value="None" <?php if($ticketmachine_config->event_grouping == "None"){ ?>selected<?php } ?>><?php echo __('None', 'ticketmachine'); ?></option>
				</select>
			</td>
		</tr>

	</tbody>
</table>