

<?php

	if (isset($_POST['submit'])) {
		$save_array = 
			array(
				"button_primary_background_color" => $_POST['button_primary_background_color'],
				"button_primary_text_color" => $_POST['button_primary_text_color'],
				"button_primary_border_color" => $_POST['button_primary_border_color'],
				"button_primary_background_color_hover" => $_POST['button_primary_background_color_hover'],
				"button_primary_text_color_hover" => $_POST['button_primary_text_color_hover'],
				"button_primary_border_color_hover" => $_POST['button_primary_border_color_hover'],

				"button_secondary_border_color_hover" => $_POST['button_secondary_border_color_hover'],
				"button_secondary_background_color" => $_POST['button_secondary_background_color'],
				"button_secondary_text_color" => $_POST['button_secondary_text_color'],
				"button_secondary_border_color" => $_POST['button_secondary_border_color'],
				"button_secondary_background_color_hover" => $_POST['button_secondary_background_color_hover'],
				"button_secondary_text_color_hover" => $_POST['button_secondary_text_color_hover'],
				"button_secondary_border_color_hover" => $_POST['button_secondary_border_color_hover'],

				"link_text_color" => $_POST['link_text_color'],
				"link_text_color_hover" => $_POST['link_text_color_hover'],

				"date_text_color" => $_POST['date_text_color']
			);
		if (!empty($tm_design)) {
			$wpdb->update(
				$wpdb->prefix . "ticketmachine_design",
				$save_array,
				array('id' => $tm_design->id)
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
		$tm_design = (object)$_POST;

	}
?>

<table class="form-table">
	<tbody>

		<tr style="border-bottom: 1px solid #ccc;">
			<th><h2><?php echo __('Primary Button', 'ticketmachine'); ?></h2></th>
			<td>
				<table class="form-table">
					<tr>
						<th><label><?php echo __('Background color', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $tm_design->button_primary_background_color; ?>" data-default-color="#ffffff" name="button_primary_background_color" /></td>
						<th><label><?php echo __('On hover', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $tm_design->button_primary_background_color_hover; ?>" data-default-color="#ffffff" name="button_primary_background_color_hover" /></td>
					</tr>		
					<tr>
						<th><label><?php echo __('Text color', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $tm_design->button_primary_text_color; ?>" data-default-color="#ffffff" name="button_primary_text_color" /></td>
						<th><label><?php echo __('On hover', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $tm_design->button_primary_text_color_hover; ?>" data-default-color="#ffffff" name="button_primary_text_color_hover" /></td>
					</tr>	
					<tr>
						<th><label><?php echo __('Border color', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $tm_design->button_primary_border_color; ?>" data-default-color="#ffffff" name="button_primary_border_color" /></td>
						<th><label><?php echo __('On hover', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $tm_design->button_primary_border_color_hover; ?>" data-default-color="#ffffff" name="button_primary_border_color_hover" /></td>
					</tr>
				</table>
			</td>
		</tr>
	
		<tr style="border-bottom: 1px solid #ccc;">
			<th><h2><?php echo __('Secondary Button', 'ticketmachine'); ?></h2></th>
			<td>
				<table class="form-table">
					<tr>
						<th><label><?php echo __('Background color', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $tm_design->button_secondary_background_color; ?>" data-default-color="#ffffff" name="button_secondary_background_color" /></td>
						<th><label><?php echo __('On hover', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $tm_design->button_secondary_background_color_hover; ?>" data-default-color="#ffffff" name="button_secondary_background_color_hover" /></td>
					</tr>		
					<tr>
						<th><label><?php echo __('Text color', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $tm_design->button_secondary_text_color; ?>" data-default-color="#ffffff" name="button_secondary_text_color" /></td>
						<th><label><?php echo __('On hover', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $tm_design->button_secondary_text_color_hover; ?>" data-default-color="#ffffff" name="button_secondary_text_color_hover" /></td>
					</tr>	
					<tr>
						<th><label><?php echo __('Border color', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $tm_design->button_secondary_border_color; ?>" data-default-color="#ffffff" name="button_secondary_border_color" /></td>
						<th><label><?php echo __('On hover', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $tm_design->button_secondary_border_color_hover; ?>" data-default-color="#ffffff" name="button_secondary_border_color_hover" /></td>
					</tr>
				</table>
			</td>
		</tr>
	
		<tr style="border-bottom: 1px solid #ccc;">
			<th><h2><?php echo __('Links', 'ticketmachine'); ?></h2></th>
			<td>
				<table class="form-table">	
					<tr>
						<th><label><?php echo __('Text color', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $tm_design->link_text_color; ?>" data-default-color="#ffffff" name="link_text_color" /></td>
						<th><label><?php echo __('On hover', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $tm_design->link_text_color_hover; ?>" data-default-color="#ffffff" name="link_text_color_hover" /></td>
					</tr>	
				</table>
			</td>
		</tr>
	
		<tr>
			<th><h2><?php echo __('Date', 'ticketmachine'); ?></h2></th>
			<td>
				<table class="form-table">	
					<tr>
						<th><label><?php echo __('Text color', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $tm_design->date_text_color; ?>" data-default-color="#ffffff" name="date_text_color" /></td>
					</tr>	
				</table>
			</td>
		</tr>

	</tbody>
</table>