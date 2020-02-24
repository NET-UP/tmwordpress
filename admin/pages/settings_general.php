<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	if (isset($_POST['submit'])) {
		$save_array = 
			array(
				"events_slug_id" => $_POST['events_slug_id'],
				"event_slug_id" => $_POST['event_slug_id']
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
			<th><label><?php echo __('Events overview page', 'ticketmachine'); ?></label></th>
			<td>
				<select name="events_slug_id">
					<?php
						if( $pages = get_pages() ){
							foreach( $pages as $page ){
								$selected = ($page->ID == $ticketmachine_config->events_slug_id) ? 'selected="selected"' : '';
								echo '<option value="' . $page->ID . '" ' . $selected . '>' . $page->post_title . '</option>';
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th><label><?php echo __('Event detail page', 'ticketmachine'); ?></label></th>
			<td>
				<select name="event_slug_id">
					<?php
						if( $pages = get_pages() ){
							foreach( $pages as $page ){
								$selected = ($page->ID == $ticketmachine_config->event_slug_id) ? 'selected="selected"' : '';
								echo '<option value="' . $page->ID . '" ' . $selected . '>' . $page->post_title . '</option>';
							}
						}
					?>
				</select>
			</td>
		</tr>

	</tbody>
</table>