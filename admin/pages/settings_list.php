<?php
	if (isset($_POST['submit'])) {

        if (isset($_POST['show_list'])){
            $_POST['show_list'] = 1;
        }

		$save_array = 
			array(
				"show_list" => $_POST['show_list'],
				"event_grouping" => $_POST['event_grouping'],
			);
		if (!empty($tm_config)) {
			$wpdb->update(
				$wpdb->prefix . "ticketmachine_config",
				$save_array,
				array('id' => $tm_config->id)
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
		$tm_config = (object)$_POST;
	}
?>

<table class="form-table">
	<tbody>
		<tr>
			<th><label><?php echo __('Activate List?', 'ticketmachine'); ?></label></th>
            <td><input name="show_list" type="checkbox" value=1 class="regular-text" <?php if($tm_config->show_list){ ?>checked<?php } ?>/></td>
		</tr>
		<tr>
			<th><label><?php echo __('Group events by', 'ticketmachine'); ?></label></th>
            <td>
				<select name="event_grouping">
					<option value="None" <?php if($tm_config->event_grouping == "None" || !isset($tm_config->event_grouping)){ ?>selected<?php } ?>><?php echo __('None', 'ticketmachine'); ?></option>
					<option value="Month" <?php if($tm_config->event_grouping == "Month"){ ?>selected<?php } ?>><?php echo __('Month', 'ticketmachine'); ?></option>
					<option value="Year" <?php if($tm_config->event_grouping == "Year"){ ?>selected<?php } ?>><?php echo __('Year', 'ticketmachine'); ?></option>
				</select>
			</td>
		</tr>

	</tbody>
</table>