<?php
	defined("ABSPATH") or die("Permission denied");
	include( str_replace("/admin/pages", "", plugin_dir_path(__FILE__)) . 'admin/includes/scriptstyles.php');
	
    global $globals, $api, $wpdb;
    $tm_config = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_config LIMIT 0,1");
    $tm_config = $tm_config[0];

    if($_GET['code'] && empty($globals->api_refresh_token)) {
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
    }
?>

<div class="wrap tm-admin-page">

    <?php if($_GET['code']) { ?>

        <div class="box mb-3">
            <a href="#" class="close"><i class="fas fa-times"></i></a>
            <div class="box-body">
                <h1><?php echo __('Welcome to', 'ticketmachine'); ?> <span>TicketMachine</span> <span>Event Manager</span> <span class="text-primary">1.0!</span></h1>
                <div><?php echo __("You're ready to go!", 'ticketmachine'); ?></div>
            
                <br>
                <p class="">
                    <a href="?page=tm_settings&tab=design"><?php echo __("Customize your theme", "ticketmachine"); ?></a>
                    <a class="ml-3" href="?page=tm_events&action=edit"><?php echo __("Create an event", "ticketmachine"); ?></a>
                    <a class="ml-3 d-none" href="?page=tm_events&action=edit"><?php echo __("What's new?", "ticketmachine"); ?></a>
                </p>
            </div>
        
        </div>

    <?php } ?>

    <div class="row">
        <div class="col-xl-9">

            <h1 class="wp-heading-inline mr-3 mb-3">
                TicketMachine <i class="fas fa-angle-right mx-1"></i> <?php echo __('Overview', 'ticketmachine') ?>
                <a target="_blank" href="/<?php echo $globals->events_slug; ?>" class="button button-secondary ml-2"><?php echo __("Go to live webpage", "ticketmachine"); ?></a>
            </h1>

            <div class="row">

                <div class="col-md-6 col-lg-4">
                    <div class="box mb-3">
                        <div class="box-title"><?php echo __("Create an event", "ticketmachine"); ?></div>
                        <div class="box-body">
                            <p><?php echo __("Just add a title, description, image, location and dates.", "ticketmachine"); ?></p>
                            <a href="?page=tm_events&action=edit" class="button button-secondary mb-1"><?php echo __("New event", "ticketmachine"); ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="box mb-3">
                        <div class="box-title"><?php echo __("Edit events", "ticketmachine"); ?></div>
                        <div class="box-body">
                            <p><?php echo __("Edit, publish and view your existing events.", "ticketmachine"); ?></p>
                            <a href="?page=tm_events" class="button button-secondary mb-1"><?php echo __("View all events", "ticketmachine"); ?></a>
                            <a href="?page=tm_events&status=drafts" class="button button-secondary mb-1"><?php echo __("Drafts", "ticketmachine"); ?></a>
                            <a href="?page=tm_events&status=upcoming" class="button button-secondary mb-1"><?php echo __("Upcoming events", "ticketmachine"); ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="box mb-3">
                        <div class="box-title"><?php echo __("Change settings", "ticketmachine"); ?></div>
                        <div class="box-body">
                            <p><?php echo __("Easily adjust the design, page layouts, URLs and general settings.", "ticketmachine"); ?></p>
                            <a href="?page=tm_settings&tab=design" class="button button-secondary mb-1"><?php echo __("Edit design", "ticketmachine"); ?></a>
                            <a href="?page=tm_settings&tab=general" class="button button-secondary mb-1"><?php echo __("General settings", "ticketmachine"); ?></a>
                            <a href="?page=tm_settings&tab=detail" class="button button-secondary mb-1"><?php echo __("Event settings", "ticketmachine"); ?></a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-xl-3">
            <h1 class="wp-heading-inline mr-3 mb-3">
                <?php echo __('', 'ticketmachine') ?>&nbsp;
            </h1>
            <div class="box mb-3">
                <div class="box-title"><?php echo __("TicketMachine Pro", "ticketmachine"); ?></div>
                <div class="box-body"></div>
            </div>
        </div>

    </div>
</div>