<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	if (isset($_POST['submit'])) {

		if ( ! isset( $_POST['ticketmachine_settings_page_form_nonce'] ) || ! wp_verify_nonce( $_POST['ticketmachine_settings_page_form_nonce'], 'ticketmachine_action_save_settings' ) ) {
			print 'Sorry, your nonce did not verify.';
			exit;
		} else {
			$tm_post = (object)$_POST;
			$errors = array();
			
			$save_array = 
				array(
					"container_background_color" => $tm_post->container_background_color,
					"box_text_color" => $tm_post->box_text_color,
					"box_meta_color" => $tm_post->box_text_color,
					"box_header_color" => $tm_post->box_text_color,
					"box_border_color" => $tm_post->box_border_color,

					"button_primary_background_color" => $tm_post->button_primary_background_color,
					"button_primary_text_color" => $tm_post->button_primary_text_color,
					"button_primary_border_color" => $tm_post->button_primary_border_color,
					"button_primary_background_color_hover" => $tm_post->button_primary_background_color_hover,
					"button_primary_text_color_hover" => $tm_post->button_primary_text_color_hover,
					"button_primary_border_color_hover" => $tm_post->button_primary_border_color_hover,

					"button_secondary_border_color_hover" => $tm_post->button_secondary_border_color_hover,
					"button_secondary_background_color" => $tm_post->button_secondary_background_color,
					"button_secondary_text_color" => $tm_post->button_secondary_text_color,
					"button_secondary_border_color" => $tm_post->button_secondary_border_color,
					"button_secondary_background_color_hover" => $tm_post->button_secondary_background_color_hover,
					"button_secondary_text_color_hover" => $tm_post->button_secondary_text_color_hover,
					"button_secondary_border_color_hover" => $tm_post->button_secondary_border_color_hover,

					"link_text_color" => $tm_post->link_text_color,
					"link_text_color_hover" => $tm_post->link_text_color_hover,

					"date_text_color" => $tm_post->date_text_color
				);

			//validation & sanitation
			foreach($save_array as $key => $color) {
				if(ctype_xdigit(substr($color,1)) && strlen(ltrim($color,"#"))==6 || ctype_xdigit(substr($color,1)) && strlen(ltrim($color,"#"))==3 || empty($color)){ 
					$save_array[$key] = sanitize_hex_color($color);
				}else{
					$errors[] = sanitize_text_field($color) . " is not a valid hex color.";
				}
			}

			if (!empty($ticketmachine_design) && empty($errors)) {
				$wpdb->update(
					$wpdb->prefix . "ticketmachine_design",
					$save_array,
					array('id' => $ticketmachine_design->id)
				);
				?>
				<div class="notice notice-success is-dismissable">
					<p><?php esc_html_e('Saved', 'ticketmachine-event-manager'); ?>!</p>
				</div>
				<?php
				$ticketmachine_design = $tm_post;
			}else{
				?>
				<div class="notice notice-error is-dismissable">
					<p><?php esc_html_e('Something went wrong', 'ticketmachine-event-manager'); ?>!</p>
						<?php 
							if(!empty($errors)){
								foreach($errors as $error) {
									?>
									<p><?php echo $error ?>!</p>
									<?php
								}
							}
						?>
					}
				</div>
				<?php
			}

		}
	}
?>

<table class="form-table table-vertical-top">
	<tbody>

		<tr style="border-bottom: 1px solid #ccc;">
			<th><h2><?php esc_html_e('General', 'ticketmachine-event-manager'); ?></h2></th>
			<td>
				<table class="form-table">
					<tr>
						<th><label><?php esc_html_e('Background color', 'ticketmachine-event-manager'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo esc_html($ticketmachine_design->container_background_color); ?>" data-default-color="#ffffff" name="container_background_color" /></td>
					</tr>		
					<tr>
						<th><label><?php esc_html_e('Text color', 'ticketmachine-event-manager'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo esc_html($ticketmachine_design->box_text_color); ?>" data-default-color="#222222" name="box_text_color" /></td>
					</tr>
					<tr>
						<th><label><?php esc_html_e('Border color', 'ticketmachine-event-manager'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo esc_html($ticketmachine_design->box_border_color); ?>" data-default-color="#dddddd" name="box_border_color" /></td>
					</tr>
				</table>
			</td>
		</tr>

		<tr style="border-bottom: 1px solid #ccc;">
			<th><h2><?php esc_html_e('Primary Button', 'ticketmachine-event-manager'); ?></h2></th>
			<td>
				<table class="form-table">
					<tr>
						<th><label><?php esc_html_e('Background color', 'ticketmachine-event-manager'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo esc_html($ticketmachine_design->button_primary_background_color); ?>" data-default-color="#ffffff" name="button_primary_background_color" /></td>
						<th><label><?php esc_html_e('On hover', 'ticketmachine-event-manager'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo esc_html($ticketmachine_design->button_primary_background_color_hover); ?>" data-default-color="#ffffff" name="button_primary_background_color_hover" /></td>
					</tr>		
					<tr>
						<th><label><?php esc_html_e('Text color', 'ticketmachine-event-manager'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo esc_html($ticketmachine_design->button_primary_text_color); ?>" data-default-color="#ffffff" name="button_primary_text_color" /></td>
						<th><label><?php esc_html_e('On hover', 'ticketmachine-event-manager'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo esc_html($ticketmachine_design->button_primary_text_color_hover); ?>" data-default-color="#ffffff" name="button_primary_text_color_hover" /></td>
					</tr>	
					<tr>
						<th><label><?php esc_html_e('Border color', 'ticketmachine-event-manager'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo esc_html($ticketmachine_design->button_primary_border_color); ?>" data-default-color="#ffffff" name="button_primary_border_color" /></td>
						<th><label><?php esc_html_e('On hover', 'ticketmachine-event-manager'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo esc_html($ticketmachine_design->button_primary_border_color_hover); ?>" data-default-color="#ffffff" name="button_primary_border_color_hover" /></td>
					</tr>
				</table>
			</td>
		</tr>
	
		<tr style="border-bottom: 1px solid #ccc;">
			<th><h2><?php esc_html_e('Secondary Button', 'ticketmachine-event-manager'); ?></h2></th>
			<td>
				<table class="form-table">
					<tr>
						<th><label><?php esc_html_e('Background color', 'ticketmachine-event-manager'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo esc_html($ticketmachine_design->button_secondary_background_color); ?>" data-default-color="#ffffff" name="button_secondary_background_color" /></td>
						<th><label><?php esc_html_e('On hover', 'ticketmachine-event-manager'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo esc_html($ticketmachine_design->button_secondary_background_color_hover); ?>" data-default-color="#ffffff" name="button_secondary_background_color_hover" /></td>
					</tr>		
					<tr>
						<th><label><?php esc_html_e('Text color', 'ticketmachine-event-manager'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo esc_html($ticketmachine_design->button_secondary_text_color); ?>" data-default-color="#ffffff" name="button_secondary_text_color" /></td>
						<th><label><?php esc_html_e('On hover', 'ticketmachine-event-manager'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo esc_html($ticketmachine_design->button_secondary_text_color_hover); ?>" data-default-color="#ffffff" name="button_secondary_text_color_hover" /></td>
					</tr>	
					<tr>
						<th><label><?php esc_html_e('Border color', 'ticketmachine-event-manager'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo esc_html($ticketmachine_design->button_secondary_border_color); ?>" data-default-color="#ffffff" name="button_secondary_border_color" /></td>
						<th><label><?php esc_html_e('On hover', 'ticketmachine-event-manager'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo esc_html($ticketmachine_design->button_secondary_border_color_hover); ?>" data-default-color="#ffffff" name="button_secondary_border_color_hover" /></td>
					</tr>
				</table>
			</td>
		</tr>
	
		<tr style="border-bottom: 1px solid #ccc;">
			<th><h2><?php esc_html_e('Links', 'ticketmachine-event-manager'); ?></h2></th>
			<td>
				<table class="form-table">	
					<tr>
						<th><label><?php esc_html_e('Text color', 'ticketmachine-event-manager'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo esc_html($ticketmachine_design->link_text_color); ?>" data-default-color="#ffffff" name="link_text_color" /></td>
						<th><label><?php esc_html_e('On hover', 'ticketmachine-event-manager'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo esc_html($ticketmachine_design->link_text_color_hover); ?>" data-default-color="#ffffff" name="link_text_color_hover" /></td>
					</tr>	
				</table>
			</td>
		</tr>
	
		<tr>
			<th><h2><?php esc_html_e('Date', 'ticketmachine-event-manager'); ?></h2></th>
			<td>
				<table class="form-table">	
					<tr>
						<th><label><?php esc_html_e('Text color', 'ticketmachine-event-manager'); ?> </label></th>
						<td><input class="color-field" type="text" value="<?php echo esc_html($ticketmachine_design->date_text_color); ?>" data-default-color="#ffffff" name="date_text_color" /></td>
					</tr>	
				</table>
			</td>
		</tr>

	</tbody>
</table>