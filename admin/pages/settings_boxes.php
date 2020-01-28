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
			<th><label><?php echo __('Activate Boxes?', 'ticketmachine'); ?></label></th>
            <td><input name="show_boxes" type="checkbox" value=1 class="regular-text" <?php if($tm_config->show_boxes){ ?>checked<?php } ?>/></td>
		</tr>

	</tbody>
</table>