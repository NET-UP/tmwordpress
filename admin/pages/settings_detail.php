<table class="form-table">
	<tbody>
		<tr>
			<th><label><?php echo __('Liste aktivieren?', 'ticketmachine'); ?></label></th>
            <td><input name="show_list" type="checkbox" value=1 class="regular-text" <?php if($tm_config->show_list){ ?>checked <?php } ?>/></td>
		</tr>

	</tbody>
</table>