<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	if (isset($_POST['submit'])) {

		if ( ! isset( $_POST['ticketmachine_settings_page_form_nonce'] ) || ! wp_verify_nonce( $_POST['ticketmachine_settings_page_form_nonce'], 'ticketmachine_action_save_settings' ) ) {
			print 'Sorry, your nonce did not verify.';
			exit;
		} else {
			$post = (object)$_POST;
			$errors = array();
			
			$save_array = 
				array(
					"button_primary_background_color" => $post->button_primary_background_color,
					"button_primary_text_color" => $post->button_primary_text_color,
					"button_primary_border_color" => $post->button_primary_border_color,
					"button_primary_background_color_hover" => $post->button_primary_background_color_hover,
					"button_primary_text_color_hover" => $post->button_primary_text_color_hover,
					"button_primary_border_color_hover" => $post->button_primary_border_color_hover,

					"button_secondary_border_color_hover" => $post->button_secondary_border_color_hover,
					"button_secondary_background_color" => $post->button_secondary_background_color,
					"button_secondary_text_color" => $post->button_secondary_text_color,
					"button_secondary_border_color" => $post->button_secondary_border_color,
					"button_secondary_background_color_hover" => $post->button_secondary_background_color_hover,
					"button_secondary_text_color_hover" => $post->button_secondary_text_color_hover,
					"button_secondary_border_color_hover" => $post->button_secondary_border_color_hover,

					"link_text_color" => $post->link_text_color,
					"link_text_color_hover" => $post->link_text_color_hover,

					"date_text_color" => $post->date_text_color
				);

			//validation & sanitation
			foreach($save_array as $key => $color) {
				if(ctype_xdigit(substr($color,1)) && strlen(ltrim($color,"#"))==6 || empty($color)){ 
					$save_array[$key] = sanitize_hex_color($color);
				}else{
					$errors[] = sanitize_hex_color($color) . " is not a valid hex color.";
				}
			}

			print_r($errors);

			if (!empty($ticketmachine_design) && empty($errors)) {
				$wpdb->update(
					$wpdb->prefix . "ticketmachine_design",
					$save_array,
					array('id' => $ticketmachine_design->id)
				);
				?>
				<div class="notice notice-success is-dismissable">
					<p><?php esc_html_e('Saved', 'ticketmachine'); ?>!</p>
				</div>
				<?php
				$ticketmachine_design = $post;
			}else{
				?>
				<div class="notice notice-error is-dismissable">
					<p><?php esc_html_e('Something went wrong', 'ticketmachine'); ?>!</p>
				</div>
				<?php
			}

		}
	}
?>

<table class="form-table table-vertical-top">
	<tbody>

		<tr style="border-bottom: 1px solid #ccc;">
			<th><h2><?php esc_html_e('Primary Button', 'ticketmachine'); ?></h2></th>
			<td>
				<table class="form-table">
					<tr>
						<th><label><?php esc_html_e('Background color', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $ticketmachine_design->button_primary_background_color; ?>" data-default-color="#ffffff" name="button_primary_background_color" /></td>
						<th><label><?php esc_html_e('On hover', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $ticketmachine_design->button_primary_background_color_hover; ?>" data-default-color="#ffffff" name="button_primary_background_color_hover" /></td>
					</tr>		
					<tr>
						<th><label><?php esc_html_e('Text color', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $ticketmachine_design->button_primary_text_color; ?>" data-default-color="#ffffff" name="button_primary_text_color" /></td>
						<th><label><?php esc_html_e('On hover', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $ticketmachine_design->button_primary_text_color_hover; ?>" data-default-color="#ffffff" name="button_primary_text_color_hover" /></td>
					</tr>	
					<tr>
						<th><label><?php esc_html_e('Border color', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $ticketmachine_design->button_primary_border_color; ?>" data-default-color="#ffffff" name="button_primary_border_color" /></td>
						<th><label><?php esc_html_e('On hover', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $ticketmachine_design->button_primary_border_color_hover; ?>" data-default-color="#ffffff" name="button_primary_border_color_hover" /></td>
					</tr>
				</table>
			</td>
		</tr>
	
		<tr style="border-bottom: 1px solid #ccc;">
			<th><h2><?php esc_html_e('Secondary Button', 'ticketmachine'); ?></h2></th>
			<td>
				<table class="form-table">
					<tr>
						<th><label><?php esc_html_e('Background color', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $ticketmachine_design->button_secondary_background_color; ?>" data-default-color="#ffffff" name="button_secondary_background_color" /></td>
						<th><label><?php esc_html_e('On hover', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $ticketmachine_design->button_secondary_background_color_hover; ?>" data-default-color="#ffffff" name="button_secondary_background_color_hover" /></td>
					</tr>		
					<tr>
						<th><label><?php esc_html_e('Text color', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $ticketmachine_design->button_secondary_text_color; ?>" data-default-color="#ffffff" name="button_secondary_text_color" /></td>
						<th><label><?php esc_html_e('On hover', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $ticketmachine_design->button_secondary_text_color_hover; ?>" data-default-color="#ffffff" name="button_secondary_text_color_hover" /></td>
					</tr>	
					<tr>
						<th><label><?php esc_html_e('Border color', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $ticketmachine_design->button_secondary_border_color; ?>" data-default-color="#ffffff" name="button_secondary_border_color" /></td>
						<th><label><?php esc_html_e('On hover', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $ticketmachine_design->button_secondary_border_color_hover; ?>" data-default-color="#ffffff" name="button_secondary_border_color_hover" /></td>
					</tr>
				</table>
			</td>
		</tr>
	
		<tr style="border-bottom: 1px solid #ccc;">
			<th><h2><?php esc_html_e('Links', 'ticketmachine'); ?></h2></th>
			<td>
				<table class="form-table">	
					<tr>
						<th><label><?php esc_html_e('Text color', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $ticketmachine_design->link_text_color; ?>" data-default-color="#ffffff" name="link_text_color" /></td>
						<th><label><?php esc_html_e('On hover', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $ticketmachine_design->link_text_color_hover; ?>" data-default-color="#ffffff" name="link_text_color_hover" /></td>
					</tr>	
				</table>
			</td>
		</tr>
	
		<tr>
			<th><h2><?php esc_html_e('Date', 'ticketmachine'); ?></h2></th>
			<td>
				<table class="form-table">	
					<tr>
						<th><label><?php esc_html_e('Text color', 'ticketmachine'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo $ticketmachine_design->date_text_color; ?>" data-default-color="#ffffff" name="date_text_color" /></td>
					</tr>	
				</table>
			</td>
		</tr>

	</tbody>
</table>