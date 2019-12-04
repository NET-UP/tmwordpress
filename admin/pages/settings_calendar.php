<?php
	if (isset($_POST['submit'])) {

        if (isset($_POST['show_calendar'])){
            $_POST['show_calendar'] = 1;
        }

		$save_array = 
			array(
				"show_calendar" => $_POST['show_calendar'],
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
			<th><label><?php echo __('Kalender aktivieren?', 'ticketmachine'); ?></label></th>
            <td><input name="show_calendar" type="checkbox" value=1 class="regular-text" <?php if($tm_config->show_calendar){ ?>checked <?php } ?>/></td>
		</tr>

	</tbody>
</table>