<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	
    global $tm_globals, $tm_api, $wpdb;
    $ticketmachine_config = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_config LIMIT 0,1");
    $ticketmachine_config = $ticketmachine_config[0];

    if(!empty($_GET['code']) && empty($tm_globals->activated)) {
        //Exchange the auth code for an access token
        $token = ticketmachine_tmapi_get_access_token(sanitize_text_field($_GET['code']), "new");

        $current_organizer = (object)ticketmachine_tmapi_organizers()[0];

        $save_array = array(
            "api_access_token" => $token['access_token'],
            "api_refresh_token" => $token['refresh_token'],
            "api_refresh_last" => time(),
            "api_refresh_interval" => $token['expires_in']/2,
            "organizer_id" => $current_organizer->id,
            "organizer" => $current_organizer->og_abbreviation
        );

        if(!empty($token['access_token']) && !empty($token['refresh_token'])){
            $wpdb->update(
                $wpdb->prefix . "ticketmachine_config",
                $save_array,
                array('id' => $ticketmachine_config->id)
            );
            $tm_globals->activated = 1;
        }
    }
    
    if(!empty($tm_globals->activated)) {
?>

<div class="wrap tm-admin-page">
    <h1 class="dont-display"></h1>

    <?php if(!empty($_GET['code'])) { ?>

        <div class="box mb-3">
            <a href="#" class="close"><i class="fas fa-times"></i></a>
            <div class="box-body">
                <h1><?php esc_html_e('Welcome to', 'ticketmachine-event-manager'); ?> <span>TicketMachine</span> <span>Event Manager</span> <span class="text-primary"></span></h1>
                <div><?php esc_html_e("You're ready to go!", 'ticketmachine-event-manager'); ?></div>
            
                <br>
                <p class="">
                    <a href="?page=ticketmachine_settings&tab=design"><?php esc_html_e("Customize your theme", "ticketmachine-event-manager"); ?></a>
                    <a class="ms-3" href="?page=ticketmachine_events&action=edit"><?php esc_html_e("Create an event", "ticketmachine-event-manager"); ?></a>
                    <a class="ms-3 d-none" href="?page=ticketmachine_events&action=edit"><?php esc_html_e("What's new?", "ticketmachine-event-manager"); ?></a>
                </p>
            </div>
        
        </div>

    <?php } ?>

    <div class="row">
        <div class="col-xl-9">

            <h1 class="wp-heading-inline me-3 mb-3">
                TicketMachine <i class="fas fa-angle-right mx-1"></i> <?php esc_html_e('Overview', 'ticketmachine-event-manager') ?>
                <a target="_blank" href="/<?php echo esc_html($tm_globals->events_slug); ?>" class="button button-secondary ms-2"><?php esc_html_e("Go to live webpage", "ticketmachine-event-manager"); ?></a>
            </h1>

            <div class="row">

                <div class="col-md-6 col-lg-4">
                    <div class="box mb-3">
                        <div class="box-title"><?php esc_html_e("Create an event", "ticketmachine-event-manager"); ?></div>
                        <div class="box-body">
                            <p><?php esc_html_e("Just add a title, description, image, location and dates.", "ticketmachine-event-manager"); ?></p>
                            <a href="?page=ticketmachine_events&action=edit" class="button button-secondary mb-1"><?php esc_html_e("New event", "ticketmachine-event-manager"); ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="box mb-3">
                        <div class="box-title"><?php esc_html_e("Edit events", "ticketmachine-event-manager"); ?></div>
                        <div class="box-body">
                            <p><?php esc_html_e("Edit, publish and view your existing events.", "ticketmachine-event-manager"); ?></p>
                            <a href="?page=ticketmachine_events" class="button button-secondary mb-1"><?php esc_html_e("View all events", "ticketmachine-event-manager"); ?></a>
                            <a href="?page=ticketmachine_events&status=drafts" class="button button-secondary mb-1"><?php esc_html_e("Drafts", "ticketmachine-event-manager"); ?></a>
                            <a href="?page=ticketmachine_events&status=upcoming" class="button button-secondary mb-1"><?php esc_html_e("Upcoming events", "ticketmachine-event-manager"); ?></a>
                        </div>
                    </div>
                </div>

                <?php if(current_user_can('manage_options')) { ?>
                
                    <div class="col-md-6 col-lg-4">
                        <div class="box mb-3">
                            <div class="box-title"><?php esc_html_e("Change settings", "ticketmachine-event-manager"); ?></div>
                            <div class="box-body">
                                <p><?php esc_html_e("Easily adjust the design, page layouts, URLs and general settings.", "ticketmachine-event-manager"); ?></p>
                                <a href="?page=ticketmachine_settings&tab=design" class="button button-secondary mb-1"><?php esc_html_e("Edit design", "ticketmachine-event-manager"); ?></a>
                                <a href="?page=ticketmachine_settings&tab=general" class="button button-secondary mb-1"><?php esc_html_e("General settings", "ticketmachine-event-manager"); ?></a>
                                <a href="?page=ticketmachine_settings&tab=detail" class="button button-secondary mb-1"><?php esc_html_e("Event settings", "ticketmachine-event-manager"); ?></a>
                            </div>
                        </div>
                    </div>

                <?php } ?>

            </div>
        </div>

        <?php 
            include( str_replace("/admin/pages", "", plugin_dir_path(__FILE__)) . 'admin/partials/sidebar.php');
        ?>

    </div>
</div>

<?php } ?>