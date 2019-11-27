<?php

	/*
	Plugin Name: TicketMachine
	Description: Easily sell tickets for your events
	Version:     0.8.7
	Author:      NET-UP
	Author URI:  https://www.net-up.de
	*/

	add_action( 'wp_enqueue_scripts', 'add_core_files' );
	
	// load dynamic form for calculator from template
	function tm_initialize( $atts ) {

		include_once( plugin_dir_path( __FILE__ ) . 'globals.php');
		
		if( $atts ) {
			
			foreach($_GET as $key => $value) {
				$atts[$key] = $value;
			}
			
			$tm_output = "<div class='tm_page'>";

			switch ($atts['page']) {
				case 'events_list':
					include "partials/_event_list_item.php";
					include "pages/events.php";
					$tm_output .= tm_display_events( $atts, $globals, $api );
					break;
				case 'event_details':
					include "pages/event.php";
					$tm_output .= tm_display_event( $atts, $globals );
					break;
			}

			$tm_output .= "</div>";
			
			$tm_output = shortcode_unautop($tm_output);
			return $tm_output;
			
		}
	}

	add_shortcode( 'ticketmachine', 'tm_initialize' );
	
	function add_core_files () {
		//jQuery
		wp_enqueue_script( 'jquery-ui_JS', plugins_url( "assets/js/ext/jquery_ui.js", __FILE__ ), array("jquery") );
		wp_enqueue_style( 'jquery-ui_CSS', plugins_url('assets/css/ext/jquery_ui.css', __FILE__ ) );
		
		//Popper
		wp_enqueue_script( 'popper_JS', plugins_url('assets/js/ext/popper.js', __FILE__ ) );
		
		//Bootstrap
		wp_enqueue_script( 'bootstrap-4_JS', plugins_url( "assets/js/ext/bootstrap.min.js", __FILE__ ), array("jquery") );
		wp_enqueue_style( 'boostrap-4_CSS', plugins_url('assets/css/ext/bootstrap.min.css', __FILE__ ) );
		
		//Icons
		wp_enqueue_style( 'fontawesome-5_CSS', plugins_url('assets/css/ext/fontawesome.min.css', __FILE__ ) );
		
		//Core
		wp_enqueue_style( 'core_CSS', plugins_url('assets/css/ticketmachine.css', __FILE__ ) );
		wp_enqueue_script( 'core_JS', plugins_url('assets/js/ticketmachine.js', __FILE__ ) );

		//Custom Styles
		wp_enqueue_style( 'custom_CSS', plugins_url('assets/css/custom.php', __FILE__ ) );
	}
	
	include( plugin_dir_path( __FILE__ ) . 'admin/admin.php');
	
    register_activation_hook(__FILE__, 'tm_activate');
    register_deactivation_hook(__FILE__, 'tm_deactivate');

    function tm_activate( ) {
        global $wpdb;
        global $jal_db_version;

        $table = $wpdb->prefix . 'ticketmachine_config';
        $charset = $wpdb->get_charset_collate();
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table (
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    organizer varchar(64) DEFAULT '' NOT NULL,
                    api_client_id varchar(64) DEFAULT '' NOT NULL,
                    api_client_secret varchar(64) DEFAULT '' NOT NULL,
                    api_refresh_token varchar(64) DEFAULT '' NOT NULL,
                    api_access_token varchar(64) DEFAULT '' NOT NULL,
                    api_refresh_interval int(11) DEFAULT 7200 NOT NULL,
                    api_last_refresh datetime(6) DEFAULT NULL,
                    api_environment varchar(64) DEFAULT 'shop' NOT NULL,
                    show_list bit(1) DEFAULT 1 NOT NULL,
                    show_calendar bit(1) DEFAULT 1 NOT NULL,
                PRIMARY KEY  (id)
                ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        add_option('jal_db_version', $jal_db_version);
        
        $wpdb->query("INSERT INTO $table (id) VALUES (NULL)");
        
        $table = $wpdb->prefix . 'ticketmachine_design';
        $charset = $wpdb->get_charset_collate();
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table (
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    link_text_color varchar(64) DEFAULT '#0fb1e4' NOT NULL,
                    link_text_color_hover varchar(64) DEFAULT '#0056b3' NOT NULL,
                    container_background_color varchar(64) DEFAULT '#ffffff' NOT NULL,
                    date_text_color varchar(64) DEFAULT '#ee7d26' NOT NULL,
                    price_text_color varchar(64) DEFAULT '#ee7d26' NOT NULL,
                    button_primary_background_color varchar(64) DEFAULT '#ee7d26' NOT NULL,
                    button_primary_text_color varchar(64) DEFAULT '#ffffff' NOT NULL,
                    button_primary_border_color varchar(64) DEFAULT '#f58d3e' NOT NULL,
                    button_primary_background_color_hover varchar(64) DEFAULT '#f58d3e' NOT NULL,
                    button_primary_text_color_hover varchar(64) DEFAULT '#ffffff' NOT NULL,
                    button_primary_border_color_hover varchar(64) DEFAULT '#f58d3e' NOT NULL,
                    button_secondary_background_color varchar(64) DEFAULT '#f9f9f9' NOT NULL,
                    button_secondary_text_color varchar(64) DEFAULT '#666666' NOT NULL,
                    button_secondary_border_color varchar(64) DEFAULT '#dadada' NOT NULL,
                    button_secondary_background_color_hover varchar(64) DEFAULT '#f9f9f9' NOT NULL,
                    button_secondary_text_color_hover varchar(64) DEFAULT '#666666' NOT NULL,
                    button_secondary_border_color_hover varchar(64) DEFAULT '#dadada' NOT NULL,
                PRIMARY KEY  (id)
                ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        add_option('jal_db_version', $jal_db_version);
        
        $wpdb->query("INSERT INTO $table (id) VALUES (NULL)");
    }

    function tm_deactivate( ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ticketmachine_config';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
        $table_name = $wpdb->prefix . 'ticketmachine_design';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
    }

?>