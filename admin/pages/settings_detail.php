<?php
	if (isset($_POST['submit'])) {

		$save_array = 
			array(
				"show_social_media" => $_POST['show_social_media'],
				"show_social_media_ical" => $_POST['show_social_media_ical'],
				"show_social_media_google_cal" => $_POST['show_social_media_google_cal'],
				"show_social_media_facebook" => $_POST['show_social_media_facebook'],
				"show_social_media_twitter" => $_POST['show_social_media_twitter'],
				"show_social_media_email" => $_POST['show_social_media_email'],
				"show_social_media_messenger" => $_POST['show_social_media_messenger'],
				"show_social_media_whatsapp" => $_POST['show_social_media_whatsapp'],
				"show_google_map" => $_POST['show_google_map']
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
			</div>
			<?php
		}else{
			?>
			<div class="notice notice-error is-dismissable">
				<p><?php echo __('Etwas ist schiefgelaufen.'); ?></p>
			</div>
			<?php
		}
		$tm_config = (object)$_POST;
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
			<th><label><?php echo __('Social Media teilen aktiviert?', 'ticketmachine'); ?></label></th>
            <td><input name="show_social_media" type="checkbox" value=1 class="regular-text" <?php if($tm_config->show_social_media){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr class="social_media hidden">
            <th class="pl-4"><label><?php echo __('iCal', 'ticketmachine'); ?></label></th>
            <td><input name="show_social_media_ical" type="checkbox" value=1 class="regular-text" <?php if($tm_config->show_social_media_ical){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr class="social_media hidden">
            <th class="pl-4"><label><?php echo __('Google Kalender', 'ticketmachine'); ?></label></th>
            <td><input name="show_social_media_google_cal" type="checkbox" value=1 class="regular-text" <?php if($tm_config->show_social_media_google_cal){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr class="social_media hidden">
            <th class="pl-4"><label><?php echo __('Facebook', 'ticketmachine'); ?></label></th>
            <td><input name="show_social_media_facebook" type="checkbox" value=1 class="regular-text" <?php if($tm_config->show_social_media_facebook){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr class="social_media hidden">
            <th class="pl-4"><label><?php echo __('Twitter', 'ticketmachine'); ?></label></th>
            <td><input name="show_social_media_twitter" type="checkbox" value=1 class="regular-text" <?php if($tm_config->show_social_media_twitter){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr class="social_media hidden">
            <th class="pl-4"><label><?php echo __('E-Mail', 'ticketmachine'); ?></label></th>
            <td><input name="show_social_media_email" type="checkbox" value=1 class="regular-text" <?php if($tm_config->show_social_media_email){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr class="social_media hidden">
            <th class="pl-4"><label><?php echo __('Messenger', 'ticketmachine'); ?></label></th>
            <td><input name="show_social_media_messenger" type="checkbox" value=1 class="regular-text" <?php if($tm_config->show_social_media_messenger){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr class="social_media hidden">
            <th class="pl-4"><label><?php echo __('WhatsApp', 'ticketmachine'); ?></label></th>
            <td><input name="show_social_media_whatsapp" type="checkbox" value=1 class="regular-text" <?php if($tm_config->show_social_media_whatsapp){ ?>checked <?php  } ?>/></td>
        </tr>
    </tbody>
</table>
<table class="form-table">
    <tbody>
        <tr>
            <th><label><?php echo __('Google Maps aktiviert?', 'ticketmachine'); ?></label></th>
            <td><input name="show_google_map" type="checkbox" value=1 class="regular-text" <?php if($tm_config->show_google_map){ ?>checked <?php  } ?>/></td>
        </tr>
    </tbody>
</table>