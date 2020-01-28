<?php
	if (isset($_POST['submit'])) {
		$save_array = 
			array(
				"events_slug" => $_POST['events_slug'],
				"event_slug" => $_POST['event_slug']
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
			<th><label><?php echo __('Events overview page', 'ticketmachine'); ?></label></th>
			<td>
				<?php
					$dropdown_args = array(
						'post_type'        => 'page',
						'selected'         => $tm_config->events_slug_id,
						'name'             => 'event_slug_id',
						'sort_column'      => 'post_title',
						'echo'             => 0
					);
					$pages = wp_dropdown_pages( $dropdown_args );
				?>
			</td>
		</tr>
		<tr>
			<th><label><?php echo __('Event detail page', 'ticketmachine'); ?></label></th>
			<td>
				<?php
					$dropdown_args = array(
						'post_type'        => 'page',
						'selected'         => $tm_config->event_slug_id,
						'name'             => 'event_slug_id',
						'sort_column'      => 'post_title',
						'echo'             => 0
					);
					$pages = wp_dropdown_pages( $dropdown_args );
				?>
			</td>
		</tr>

	</tbody>
</table>