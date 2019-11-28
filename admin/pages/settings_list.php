<?php
	if (isset($_POST['submit'])) {
		echo "lol";

        if (isset($_POST['show_list'])){
            $_POST['show_list'] = 1;
        }

		$save_array = 
			array(
				"show_list" => $_POST['show_list'],
			);
		if (!empty($tm_config)) {
			$wpdb->update(
				$wpdb->prefix . "ticketmachine_config",
				$save_array,
				array('id' => $tm_config->id)
			);
		}
		$tm_config = (object)$_POST;
	}
?>

<table class="form-table">
	<tbody>
		<tr>
			<th><label><?php echo __('Liste aktivieren?', 'ticketmachine'); ?></label></th>
            <td><input name="show_list" type="checkbox" value=1 class="regular-text" <?php if($tm_config->show_list){ ?>checked <?php } ?>/></td>
		</tr>

	</tbody>
</table>