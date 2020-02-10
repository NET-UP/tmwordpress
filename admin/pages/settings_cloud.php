<?php ?>

<table class="form-table">
	<tbody>
		<tr>
			<th><label><?php echo __('Activate Boxes?', 'ticketmachine'); ?></label></th>
            <td><input name="show_boxes" type="checkbox" value=1 class="regular-text" <?php if($tm_config->show_boxes){ ?>checked<?php } ?>/></td>
		</tr>
	</tbody>
</table>