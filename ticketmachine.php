<?php

	/*
	Plugin Name:        TicketMachine Event Manager
    Plugin URI:         https://www.ticketmachine.de/event-manager/wordpress
	Description:        Easily create and manage cloud-based events for your wordpress site.
	Version:            1.0.0
    Requires at least:  4.0
    Author:             NET-UP AG
	Author URI:         https://www.net-up.de
	*/
    add_action( 'wp_enqueue_scripts', 'add_core_files' );
    
    add_action( 'init', 'wpdocs_load_textdomain' );
    function wpdocs_load_textdomain() {
        load_plugin_textdomain( 'ticketmachine', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
    }
	
	// load dynamic form for calculator from template
	function tm_initialize( $atts ) {
        global $globals, $api, $wpdb;

		include_once( plugin_dir_path( __FILE__ ) . 'globals.php');
        include_once( plugin_dir_path( __FILE__ ) . 'pages/error.php');
        include_once( plugin_dir_path( __FILE__ ) . 'partials/error.php');
		
		if( $atts ) {
			
			foreach($_GET as $key => $value) {
				$atts[$key] = $value;
			}
			
            $tm_output = "<div class='tm_page' data-locale=" . $globals->locale_short . ">";
            
            if(isset($atts['page'])){
                switch ($atts['page']) {
                    case 'event_list':
                        include "partials/_event_list_item.php";
                        include "partials/_search_header.php";
                        include "partials/_tag_header.php";
                        include "pages/events.php";
                        $tm_output .= tm_display_events( $atts );
                        break;
                    case 'event_boxes':
                        include "partials/_event_boxes_item.php";
                        include "partials/_search_header.php";
                        include "partials/_tag_header.php";
                        include "pages/events.php";
                        $tm_output .= tm_display_events( $atts );
                        break;
                    case 'event_details':
                        include "partials/_event_page_information.php";
                        include "partials/_event_page_tickets.php";
                        include "partials/_event_page_details.php";
                        include "partials/_event_page_google_map.php";
                        include "partials/_event_page_actions.php";
                        include "pages/event.php";
                        $tm_output .= tm_display_event( $atts );
                        break;
                }
            }elseif($atts['widget']){
                switch ($atts['widget']) {
                    case 'event_list':
                        include "widgets/event_list.php";
                        $tm_output .= tm_widget_event_list( $atts );
                        break;
                    case 'event_calendar':
                        include "widgets/event_calendar.php";
                        $tm_output .= tm_widget_event_calendar( $atts );
                        break;
                }
            }

			$tm_output .= "</div>";
			
			$tm_output = shortcode_unautop($tm_output);
			return $tm_output;
			
		}
	}

	add_shortcode( 'ticketmachine', 'tm_initialize' );
	
	function add_core_files () {
		//jQuery
		wp_enqueue_script( 'jquery-ui-datepicker', array("jquery") );
		wp_enqueue_style( 'jquery-ui_CSS', plugins_url('assets/css/ext/jquery_ui.css', __FILE__ ) );
		
		//Cookies
		wp_enqueue_script( 'cookies_JS', plugins_url( "assets/js/cookies.js", __FILE__ ) );

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
	
    if(is_admin()){
        include_once( plugin_dir_path( __FILE__ ) . 'admin/admin.php');
    }
	
    register_activation_hook(__FILE__, 'tm_activate');
    register_deactivation_hook(__FILE__, 'tm_deactivate');

    function tm_activate( ) {
        global $wpdb;
        global $jal_db_version;

        

        //create events overview page
        $new_page_title = 'Events';
        $new_page_slug = 'events';
        $new_page_content = '[ticketmachine page="event_list"]';
        $new_page_template = '';
    
        $page_check = get_page_by_path($new_page_slug);
        $new_page = array(
            'post_type' => 'page',
            'post_title' => $new_page_title,
            'post_name' => $new_page_slug,
            'post_content' => $new_page_content,
            'post_status' => 'publish',
            'post_author' => 1,
        );
        if(!isset($page_check->ID)){
            $new_page_id = wp_insert_post($new_page);
            if(!empty($new_page_template)){
                update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
            }
        }
        $events_slug = get_page_by_path($new_page_slug);

        //create event detail page
        $new_page_title = 'Event';
        $new_page_slug = 'event';
        $new_page_content = '[ticketmachine page="event_details"]';
        $new_page_template = '';
    
        $page_check = get_page_by_path($new_page_slug);
        $new_page = array(
            'post_type' => 'page',
            'post_title' => $new_page_title,
            'post_name' => $new_page_slug,
            'post_content' => $new_page_content,
            'post_status' => 'publish',
            'post_author' => 1,
        );
        if(!isset($page_check->ID)){
            $new_page_id = wp_insert_post($new_page);
            if(!empty($new_page_template)){
                update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
            }
        }
        $event_slug = get_page_by_path($new_page_slug);

        $table = $wpdb->prefix . 'ticketmachine_config';
        $charset = $wpdb->get_charset_collate();
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table (
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    organizer_id int(11) DEFAULT 0 NOT NULL,
                    organizer varchar(64) DEFAULT '' NOT NULL,
                    api_client_id varchar(64) DEFAULT 'c16727aa80540e51edcd276641c6f68974bb312ec5b17b75a3bc0ba254236a14' NOT NULL,
                    api_client_secret varchar(64) DEFAULT '1d3fb26a828f0e09700464997271c5236bb7d3194992299331eaa1c420a7f522' NOT NULL,
                    api_refresh_token varchar(64) DEFAULT '' NOT NULL,
                    api_access_token varchar(64) DEFAULT '' NOT NULL,
                    api_refresh_last int(11) DEFAULT " . time() . " NOT NULL,
                    api_refresh_interval int(11) DEFAULT 3600 NOT NULL,
                    api_environment varchar(64) DEFAULT 'staging' NOT NULL,
                    show_list bit(1) DEFAULT 1 NOT NULL,
                    show_boxes bit(1) DEFAULT 1 NOT NULL,
                    show_calendar bit(1) DEFAULT 1 NOT NULL,
                    show_social_media bit(1) DEFAULT 1 NOT NULL,
                    show_social_media_ical bit(1) DEFAULT 1 NOT NULL,
                    show_social_media_google_cal bit(1) DEFAULT 1 NOT NULL,
                    show_social_media_facebook bit(1) DEFAULT 1 NOT NULL,
                    show_social_media_twitter bit(1) DEFAULT 1 NOT NULL,
                    show_social_media_email bit(1) DEFAULT 1 NOT NULL,
                    show_social_media_messenger bit(1) DEFAULT 1 NOT NULL,
                    show_social_media_whatsapp bit(1) DEFAULT 1 NOT NULL,
                    show_google_map bit(1) DEFAULT 1 NOT NULL,
                    event_grouping varchar(64) DEFAULT 'Year' NOT NULL,
                    events_slug_id varchar(128) DEFAULT '" . $events_slug->ID . "' NOT NULL,
                    event_slug_id varchar(128) DEFAULT '" . $event_slug->ID . "' NOT NULL,
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
                    button_secondary_background_color varchar(64) DEFAULT '#f7f7f7' NOT NULL,
                    button_secondary_text_color varchar(64) DEFAULT '#666666' NOT NULL,
                    button_secondary_border_color varchar(64) DEFAULT '#dadada' NOT NULL,
                    button_secondary_background_color_hover varchar(64) DEFAULT '#f7f7f7' NOT NULL,
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
	

	function tm_event_metadata() {
        if(isset($_GET['id']) && $_GET['id'] > 0){
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            echo '<meta property="og:url" content="' . $actual_link . '" />';
            echo '<meta property="og:type" content="website" />';
        }
	}
	

	function tm_event_metadata_event() {
        if(isset($_GET['id']) && $_GET['id'] > 0){
            include_once( plugin_dir_path( __FILE__ ) . 'globals.php');
            $params = [ "id" => $_GET['id'] ];
            $event = tmapi_event($params);
            if(isset($event->id)){            
                echo '<meta property="og:title" content="' . $event->ev_name . '" />';
                echo '<meta property="og:image" content="' . $event->event_img_url . '" />';
                echo '<meta property="og:type" content="website" />';
                echo '<meta property="og:description" content="'. tm_i18n_date("d.m.Y", $event->ev_date) .' @ '. tm_i18n_date("H:i", $event->ev_date) .'" />';
            }
       }
    }
    
    add_filter( 'oembed_response_data', 'disable_embeds_filter_oembed_response_data_' );
    function disable_embeds_filter_oembed_response_data_( $data ) {
        unset($data['author_url']);
        unset($data['author_name']);
        return $data;
    }

	add_action('wp_head','tm_event_metadata');
    if(isset($_GET['id']) && $_GET['id'] > 0){
        add_action('wp_head','tm_event_metadata_event');
    }

?>
