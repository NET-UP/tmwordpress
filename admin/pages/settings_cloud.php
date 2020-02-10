<?php ?>

<table class="form-table">
	<tbody>
		<tr>
			<th><label><?php echo __('Activate Boxes?', 'ticketmachine'); ?></label></th>
            <td><input name="show_boxes" type="checkbox" value=1 class="regular-text" <?php if($tm_config->show_boxes){ ?>checked<?php } ?>/></td>
		</tr>
		<tr>
			<th><label><?php echo __('Group events by', 'ticketmachine'); ?></label></th>
            <td>
				<select name="event_grouping">
					<option value="Month" <?php if($tm_config->event_grouping == "Month"){ ?>selected<?php } ?>><?php echo __('Month', 'ticketmachine'); ?></option>
					<option value="Year" <?php if($tm_config->event_grouping == "Year" || !isset($tm_config->event_grouping)){ ?>selected<?php } ?>><?php echo __('Year', 'ticketmachine'); ?></option>
					<option value="None" <?php if($tm_config->event_grouping == "None"){ ?>selected<?php } ?>><?php echo __('None', 'ticketmachine'); ?></option>
				</select>
			</td>
		</tr>

	</tbody>
</table>