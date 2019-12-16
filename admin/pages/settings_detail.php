<table class="form-table">
	<tbody>
		<tr>
			<th><label><?php echo __('Social Media teilen aktiviert?', 'ticketmachine'); ?></label></th>
            <td><input name="show_media" type="checkbox" value=1 class="regular-text" <?php //if($tm_config->show_calendar){ ?>checked <?php // } ?>/></td>
        </tr>
        <tr>
            <th><label><?php echo __('iCal', 'ticketmachine'); ?></label></th>
            <td><input name="show_ical" type="checkbox" value=1 class="regular-text"></td>
        </tr>
        <tr>
            <th><label><?php echo __('Google Kalender', 'ticketmachine'); ?></label></th>
            <td><input name="show_google_calendar" type="checkbox" value=1 class="regular-text"></td>
        </tr>
        <tr>
            <th><label><?php echo __('Facebook', 'ticketmachine'); ?></label></th>
            <td><input name="show_facebook" type="checkbox" value=1 class="regular-text"></td>
        </tr>
        <tr>
            <th><label><?php echo __('Twitter', 'ticketmachine'); ?></label></th>
            <td><input name="show_twitter" type="checkbox" value=1 class="regular-text"></td>
        </tr>
        <tr>
            <th><label><?php echo __('E-Mail', 'ticketmachine'); ?></label></th>
            <td><input name="show_email" type="checkbox" value=1 class="regular-text"></td>
        </tr>
        <tr>
            <th><label><?php echo __('Messenger', 'ticketmachine'); ?></label></th>
            <td><input name="show_messenger" type="checkbox" value=1 class="regular-text"></td>
        </tr>
        <tr>
            <th><label><?php echo __('WhatsApp', 'ticketmachine'); ?></label></th>
            <td><input name="show_whatsapp" type="checkbox" value=1 class="regular-text"></td>
        </tr>
    </tbody>
    <tbody>
        <tr>
            <th><label><?php echo __('Google Maps aktiviert?', 'ticketmachine'); ?></label></th>
            <td><input name="show_google_map" type="checkbox" value=1 class="regular-text"></td>
        </tr>
    </tbody>
</table>