<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	if (isset($_POST['submit'])) {

		if ( ! isset( $_POST['ticketmachine_settings_page_form_nonce'] ) || ! wp_verify_nonce( $_POST['ticketmachine_settings_page_form_nonce'], 'ticketmachine_action_save_settings' ) ) {
			print 'Sorry, your nonce did not verify.';
			exit;
		} else {
            $ticketmachine_post = (object)$_POST;
			$errors = array();

			//validate
			if (!empty($ticketmachine_post->show_social_media)){
				$ticketmachine_post->show_social_media = true;
			}else{
				$ticketmachine_post->show_social_media = false;
            }
            
			if (!empty($ticketmachine_post->show_social_media_ical)){
				$ticketmachine_post->show_social_media_ical = true;
			}else{
				$ticketmachine_post->show_social_media_ical = false;
            }
            
			if (!empty($ticketmachine_post->show_social_media_google_cal)){
				$ticketmachine_post->show_social_media_google_cal = true;
			}else{
				$ticketmachine_post->show_social_media_google_cal = false;
            }
            
			if (!empty($ticketmachine_post->show_social_media_facebook)){
				$ticketmachine_post->show_social_media_facebook = true;
			}else{
				$ticketmachine_post->show_social_media_facebook = false;
            }
            
			if (!empty($ticketmachine_post->show_social_media_twitter)){
				$ticketmachine_post->show_social_media_twitter = true;
			}else{
				$ticketmachine_post->show_social_media_twitter = false;
            }
            
			if (!empty($ticketmachine_post->show_social_media_email)){
				$ticketmachine_post->show_social_media_email = true;
			}else{
				$ticketmachine_post->show_social_media_email = false;
            }
            
			if (!empty($ticketmachine_post->show_social_media_messenger)){
				$ticketmachine_post->show_social_media_messenger = true;
			}else{
				$ticketmachine_post->show_social_media_messenger = false;
            }
            
			if (!empty($ticketmachine_post->show_social_media_whatsapp)){
				$ticketmachine_post->show_social_media_whatsapp = true;
			}else{
				$ticketmachine_post->show_social_media_whatsapp = false;
            }
            
			if (!empty($ticketmachine_post->show_google_map)){
				$ticketmachine_post->show_google_map = true;
			}else{
				$ticketmachine_post->show_google_map = false;
            }
            
			if (!empty($ticketmachine_post->show_additional_info)){
				$ticketmachine_post->show_additional_info = true;
			}else{
				$ticketmachine_post->show_additional_info = false;
            }
            
			if (empty($ticketmachine_post->detail_page_layout)){
				$ticketmachine_post->detail_page_layout = 1;
			}

            $save_array = 
                array(
                    "show_social_media" => (bool)$ticketmachine_post->show_social_media,
                    "show_social_media_ical" => (bool)$ticketmachine_post->show_social_media_ical,
                    "show_social_media_google_cal" => (bool)$ticketmachine_post->show_social_media_google_cal,
                    "show_social_media_facebook" => (bool)$ticketmachine_post->show_social_media_facebook,
                    "show_social_media_twitter" => (bool)$ticketmachine_post->show_social_media_twitter,
                    "show_social_media_email" => (bool)$ticketmachine_post->show_social_media_email,
                    "show_social_media_messenger" => (bool)$ticketmachine_post->show_social_media_messenger,
                    "show_social_media_whatsapp" => (bool)$ticketmachine_post->show_social_media_whatsapp,
                    "show_google_map" => (bool)$ticketmachine_post->show_google_map,
                    "show_additional_info" => (bool)$ticketmachine_post->show_additional_info,
                    "detail_page_layout" => (int)$ticketmachine_post->detail_page_layout
                );
            if (!empty($ticketmachine_config) && empty($errors)) {
                $wpdb->update(
                    $wpdb->prefix . "ticketmachine_config",
                    $save_array,
                    array('id' => $ticketmachine_config->id)
                );
                ?>
                <div class="notice notice-success is-dismissable">
                    <p><?php esc_html_e('Saved', 'ticketmachine-event-manager'); ?>!</p>
                </div>
                <?php
                $ticketmachine_config = $ticketmachine_post;
            }else{
                ?>
                <div class="notice notice-error is-dismissable">
                    <p><?php esc_html_e('Something went wrong', 'ticketmachine-event-manager'); ?>!</p>
                </div>
                <?php
            }
        }
    }
?>

<script type='text/javascript'>
    jQuery( document ).ready( function( $ ) {
        if (document.querySelector('input[name="show_social_media"]').checked) {
            jQuery('.social_media').removeClass('hidden');
        }

        jQuery('input[name="show_social_media"]').on('click', function( event ){
            if (document.querySelector('input[name="show_social_media"]').checked) {
                jQuery('.social_media').removeClass('hidden');
            } else {
                jQuery('.social_media').addClass('hidden');
            }
        });
    });
</script>

<table class="form-table">
    <tbody>
        <tr>
            <th><label><?php esc_html_e('Page layout', 'ticketmachine-event-manager'); ?></label></th>
            <td>
                <label>
                    <input name="detail_page_layout" type="radio" value=1 class="regular-text" <?php if($ticketmachine_config->detail_page_layout == 1){ ?>checked <?php  } ?>/> <?php esc_html_e('1 column', 'ticketmachine-event-manager'); ?> &nbsp;
                </label>
                <label>
                    <input name="detail_page_layout" type="radio" value=2 class="regular-text" <?php if($ticketmachine_config->detail_page_layout == 2){ ?>checked <?php  } ?>/> <?php esc_html_e('2 columns', 'ticketmachine-event-manager'); ?>
                </label>
            </td>
        </tr>
    </tbody>
</table>

<table class="form-table">
	<tbody>
		<tr>
			<th><label><?php esc_html_e('Activate Social Media sharing?', 'ticketmachine-event-manager'); ?></label></th>
            <td><input name="show_social_media" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_social_media){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr class="social_media hidden">
            <th class="ps-4 py-2"><label><?php esc_html_e('iCal', 'ticketmachine-event-manager'); ?></label></th>
            <td class="py-2"><input name="show_social_media_ical" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_social_media_ical){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr class="social_media hidden">
            <th class="ps-4 py-2"><label><?php esc_html_e('Google Calendar', 'ticketmachine-event-manager'); ?></label></th>
            <td class="py-2"><input name="show_social_media_google_cal" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_social_media_google_cal){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr class="social_media hidden">
            <th class="ps-4 py-2"><label><?php esc_html_e('Facebook', 'ticketmachine-event-manager'); ?></label></th>
            <td class="py-2"><input name="show_social_media_facebook" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_social_media_facebook){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr class="social_media hidden">
            <th class="ps-4 py-2"><label><?php esc_html_e('Twitter', 'ticketmachine-event-manager'); ?></label></th>
            <td class="py-2"><input name="show_social_media_twitter" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_social_media_twitter){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr class="social_media hidden">
            <th class="ps-4 py-2"><label><?php esc_html_e('Email', 'ticketmachine-event-manager'); ?></label></th>
            <td class="py-2"><input name="show_social_media_email" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_social_media_email){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr class="social_media hidden">
            <th class="ps-4 py-2"><label><?php esc_html_e('Messenger', 'ticketmachine-event-manager'); ?></label></th>
            <td class="py-2"><input name="show_social_media_messenger" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_social_media_messenger){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr class="social_media hidden">
            <th class="ps-4 py-2"><label><?php esc_html_e('WhatsApp', 'ticketmachine-event-manager'); ?></label></th>
            <td class="py-2"><input name="show_social_media_whatsapp" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_social_media_whatsapp){ ?>checked <?php  } ?>/></td>
        </tr>
    </tbody>
</table>

<table class="form-table">
    <tbody>
        <tr>
            <th><label><?php esc_html_e('Show additional information?', 'ticketmachine-event-manager'); ?></label></th>
            <td><input name="show_additional_info" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_additional_info){ ?>checked <?php  } ?>/></td>
        </tr>
    </tbody>
</table>

<table class="form-table">
    <tbody>
        <tr>
            <th><label><?php esc_html_e('Activate Google Maps?', 'ticketmachine-event-manager'); ?></label></th>
            <td><input name="show_google_map" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_google_map){ ?>checked <?php  } ?>/></td>
        </tr>
    </tbody>
</table>