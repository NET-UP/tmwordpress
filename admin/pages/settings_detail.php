<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	if (isset($_POST['submit'])) {

		if ( ! isset( $_POST['ticketmachine_settings_page_form_nonce'] ) || ! wp_verify_nonce( $_POST['ticketmachine_settings_page_form_nonce'], 'ticketmachine_action_save_settings' ) ) {
			print 'Sorry, your nonce did not verify.';
			exit;
		} else {
            $post = (object)$_POST;
			$errors = array();

			//validate
			if (!empty($post->show_social_media)){
				$post->show_social_media = true;
			}else{
				$post->show_social_media = false;
            }
            
			if (!empty($post->show_social_media_ical)){
				$post->show_social_media_ical = true;
			}else{
				$post->show_social_media_ical = false;
            }
            
			if (!empty($post->show_social_media_google_cal)){
				$post->show_social_media_google_cal = true;
			}else{
				$post->show_social_media_google_cal = false;
            }
            
			if (!empty($post->show_social_media_facebook)){
				$post->show_social_media_facebook = true;
			}else{
				$post->show_social_media_facebook = false;
            }
            
			if (!empty($post->show_social_media_twitter)){
				$post->show_social_media_twitter = true;
			}else{
				$post->show_social_media_twitter = false;
            }
            
			if (!empty($post->show_social_media_email)){
				$post->show_social_media_email = true;
			}else{
				$post->show_social_media_email = false;
            }
            
			if (!empty($post->show_social_media_messenger)){
				$post->show_social_media_messenger = true;
			}else{
				$post->show_social_media_messenger = false;
            }
            
			if (!empty($post->show_social_media_whatsapp)){
				$post->show_social_media_whatsapp = true;
			}else{
				$post->show_social_media_whatsapp = false;
            }
            
			if (!empty($post->show_google_map)){
				$post->show_google_map = true;
			}else{
				$post->show_google_map = false;
            }

            $save_array = 
                array(
                    "show_social_media" => (bool)$post->show_social_media,
                    "show_social_media_ical" => (bool)$post->show_social_media_ical,
                    "show_social_media_google_cal" => (bool)$post->show_social_media_google_cal,
                    "show_social_media_facebook" => (bool)$post->show_social_media_facebook,
                    "show_social_media_twitter" => (bool)$post->show_social_media_twitter,
                    "show_social_media_email" => (bool)$post->show_social_media_email,
                    "show_social_media_messenger" => (bool)$post->show_social_media_messenger,
                    "show_social_media_whatsapp" => (bool)$post->show_social_media_whatsapp,
                    "show_google_map" => (bool)$post->show_google_map
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
                $ticketmachine_config = $post;
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
			<th><label><?php esc_html_e('Activate Social Media sharing?', 'ticketmachine-event-manager'); ?></label></th>
            <td><input name="show_social_media" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_social_media){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr class="social_media hidden">
            <th class="pl-4"><label><?php esc_html_e('iCal', 'ticketmachine-event-manager'); ?></label></th>
            <td><input name="show_social_media_ical" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_social_media_ical){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr class="social_media hidden">
            <th class="pl-4"><label><?php esc_html_e('Google Calendar', 'ticketmachine-event-manager'); ?></label></th>
            <td><input name="show_social_media_google_cal" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_social_media_google_cal){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr class="social_media hidden">
            <th class="pl-4"><label><?php esc_html_e('Facebook', 'ticketmachine-event-manager'); ?></label></th>
            <td><input name="show_social_media_facebook" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_social_media_facebook){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr class="social_media hidden">
            <th class="pl-4"><label><?php esc_html_e('Twitter', 'ticketmachine-event-manager'); ?></label></th>
            <td><input name="show_social_media_twitter" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_social_media_twitter){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr class="social_media hidden">
            <th class="pl-4"><label><?php esc_html_e('Email', 'ticketmachine-event-manager'); ?></label></th>
            <td><input name="show_social_media_email" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_social_media_email){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr class="social_media hidden">
            <th class="pl-4"><label><?php esc_html_e('Messenger', 'ticketmachine-event-manager'); ?></label></th>
            <td><input name="show_social_media_messenger" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_social_media_messenger){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr class="social_media hidden">
            <th class="pl-4"><label><?php esc_html_e('WhatsApp', 'ticketmachine-event-manager'); ?></label></th>
            <td><input name="show_social_media_whatsapp" type="checkbox" value=1 class="regular-text" <?php if($ticketmachine_config->show_social_media_whatsapp){ ?>checked <?php  } ?>/></td>
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