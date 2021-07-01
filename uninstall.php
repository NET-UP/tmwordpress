<?php
    // Exit if accessed directly
    if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
        exit;
    }
    global $wpdb;

    // Remove TicketMachine pages
    //$wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_config LIMIT 0,1");
    //$ticketmachine_config = $ticketmachine_config[0];
    //$ticketmachine_config = (object)$ticketmachine_config;
    
    //wp_trash_post($ticketmachine_config->events_slug_id);
    //wp_trash_post($ticketmachine_config->event_slug_id);

    // Remove TicketMachine database tables
    $table_name = $wpdb->prefix . 'ticketmachine_config';
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
    $table_name = $wpdb->prefix . 'ticketmachine_design';
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
    $table_name = $wpdb->prefix . 'ticketmachine_organizers';
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
    $table_name = $wpdb->prefix . 'ticketmachine_organizers_events_match';
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
    $table_name = $wpdb->prefix . 'ticketmachine_log';
    $wpdb->query("DROP TABLE IF EXISTS $table_name");

?>