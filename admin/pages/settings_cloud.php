<?php
    global $globals, $api, $wpdb;

    $tm_config = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_config LIMIT 0,1");
    $tm_config = $tm_config[0];

    if(!empty($_GET['code'])) {
        //Exchange the auth code for an access token
        $token = tmapi_get_access_token($_GET['code'], "new");

        $current_organizer = (object)tmapi_organizers()[0];

        $save_array = array(
            "activated" => 1,
            "api_access_token" => $token['access_token'],
            "api_refresh_token" => $token['refresh_token'],
            "api_refresh_last" => time(),
            "api_refresh_interval" => $token['expires_in']/2,
            "organizer_id" => $current_organizer->id,
            "organizer" => $current_organizer->og_abbreviation
        );


        $wpdb->update(
            $wpdb->prefix . "ticketmachine_config",
            $save_array,
            array('id' => $tm_config->id)
        );
        $globals->activated = 1;
    ?>

    <div class="notice notice-success is-dismissable">
        <p><?php echo __('Saved', 'ticketmachine'); ?>!</p>
    </div>

    <?php
    }

    $current_locale = get_locale();
    $parsed_locale = substr($current_locale, 0, strpos($current_locale, '_'));

    $authorize_url = $api->auth->proxy;
    $authorize_url .= "?";
    $authorize_url .= http_build_query($api->auth->data);
?>

<p>
    <?php echo __("If your events are not showing - or you would like to change to a different account, you can synchronize your events here.", "ticketmachine"); ?>
</p>

<a class="button button-primary mt-4" style="font-size:14px" href="<?php echo $authorize_url; ?>">
    <i class="fas fa-sync-alt"></i> &nbsp;<?php echo __("Sync events", "ticketmachine"); ?>
</a>