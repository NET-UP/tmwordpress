<table class="form-table">
	<tbody>
		<tr>
			<th><label><?php echo __('Social Media teilen aktiviert?', 'ticketmachine'); ?></label></th>
            <td><input name="show_social_media" type="checkbox" value=1 class="regular-text" <?php if($tm_config->show_social_media){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr>
            <th class="pl-4"><label><?php echo __('iCal', 'ticketmachine'); ?></label></th>
            <td><input name="show_social_media_ical" type="checkbox" value=1 class="regular-text" <?php if($tm_config->show_social_media_ical){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr>
            <th class="pl-4"><label><?php echo __('Google Kalender', 'ticketmachine'); ?></label></th>
            <td><input name="show_social_media_google_cal" type="checkbox" value=1 class="regular-text" <?php if($tm_config->show_social_media_google_cal){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr>
            <th class="pl-4"><label><?php echo __('Facebook', 'ticketmachine'); ?></label></th>
            <td><input name="show_social_media_facebook" type="checkbox" value=1 class="regular-text" <?php if($tm_config->show_social_media_facebook){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr>
            <th class="pl-4"><label><?php echo __('Twitter', 'ticketmachine'); ?></label></th>
            <td><input name="show_social_media_twitter" type="checkbox" value=1 class="regular-text" <?php if($tm_config->show_social_media_twitter){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr>
            <th class="pl-4"><label><?php echo __('E-Mail', 'ticketmachine'); ?></label></th>
            <td><input name="show_social_media_email" type="checkbox" value=1 class="regular-text" <?php if($tm_config->show_social_media_email){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr>
            <th class="pl-4"><label><?php echo __('Messenger', 'ticketmachine'); ?></label></th>
            <td><input name="show_social_media_messenger" type="checkbox" value=1 class="regular-text" <?php if($tm_config->show_social_media_messenger){ ?>checked <?php  } ?>/></td>
        </tr>
        <tr>
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