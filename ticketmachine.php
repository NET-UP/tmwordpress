<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	/*
	Plugin Name:        TicketMachine Event Manager
    Plugin URI:         https://www.ticketmachine.de/event-manager/wordpress
	Description:        Easily create and manage cloud-based events for your wordpress site.
	Version:            1.0.0
    Requires at least:  4.0
    Author:             NET-UP AG
	Author URI:         https://www.net-up.de
	*/
    add_action( 'wp_enqueue_scripts', 'ticketmachine_add_core_files' );
    
    add_action( 'init', 'ticketmachine_wpdocs_load_textdomain' );
    function ticketmachine_wpdocs_load_textdomain() {
        load_plugin_textdomain( 'ticketmachine', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
    }
	
	// load dynamic form for calculator from template
	function ticketmachine_initialize( $atts ) {
        global $globals, $api, $wpdb, $ticketmachine_globals;

		require_once( plugin_dir_path( __FILE__ ) . 'globals.php');
        include_once( plugin_dir_path( __FILE__ ) . 'pages/error.php');
        include_once( plugin_dir_path( __FILE__ ) . 'partials/error.php');
		
		if( $atts ) {
			
			foreach($_GET as $key => $value) {
				$atts[$key] = $value;
			}
			
            $ticketmachine_output = "<div class='ticketmachine_page' data-locale=" . $globals->locale_short . ">";
            
            if(isset($atts['page'])){
                switch ($atts['page']) {
                    case 'event_list':
                        include "partials/_event_list_item.php";
                        include "partials/_search_header.php";
                        include "partials/_tag_header.php";
                        include "pages/events.php";
                        $ticketmachine_output .= ticketmachine_display_events( $atts );
                        break;
                    case 'event_boxes':
                        include "partials/_event_boxes_item.php";
                        include "partials/_search_header.php";
                        include "partials/_tag_header.php";
                        include "pages/events.php";
                        $ticketmachine_output .= ticketmachine_display_events( $atts );
                        break;
                    case 'event_details':
                        include "partials/_event_page_information.php";
                        include "partials/_event_page_tickets.php";
                        include "partials/_event_page_details.php";
                        include "partials/_event_page_google_map.php";
                        include "partials/_event_page_actions.php";
                        include "pages/event.php";
                        $ticketmachine_output .= ticketmachine_display_event( $atts );
                        break;
                }
            }elseif($atts['widget']){
                switch ($atts['widget']) {
                    case 'event_list':
                        include "widgets/event_list.php";
                        $ticketmachine_output .= ticketmachine_widget_event_list( $atts );
                        break;
                    case 'event_calendar':
                        include "widgets/event_calendar.php";
                        $ticketmachine_output .= ticketmachine_widget_event_calendar( $atts );
                        break;
                }
            }

			$ticketmachine_output .= "</div>";
			
			$ticketmachine_output = shortcode_unautop($ticketmachine_output);
			return $ticketmachine_output;
			
		}
	}

	add_shortcode( 'ticketmachine', 'ticketmachine_initialize' );
	
	function ticketmachine_add_core_files () {
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
	
    register_activation_hook(__FILE__, 'ticketmachine_activate');
    register_deactivation_hook(__FILE__, 'ticketmachine_deactivate');

    function ticketmachine_activate( ) {
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

    function ticketmachine_deactivate( ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ticketmachine_config';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
        $table_name = $wpdb->prefix . 'ticketmachine_design';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
    }
	

	function ticketmachine_event_metadata() {
        if(isset($_GET['id']) && $_GET['id'] > 0){
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            echo '<meta property="og:url" content="' . $actual_link . '" />';
            echo '<meta property="og:type" content="website" />';
        }
	}
	

	function ticketmachine_event_metadata_event() {
        if(isset($_GET['id']) && $_GET['id'] > 0){
            include_once( plugin_dir_path( __FILE__ ) . 'globals.php');
            $params = [ "id" => absint($_GET['id']) ];
            $event = ticketmachine_tmapi_event($params);
            if(isset($event->id)){            
                echo '<meta property="og:title" content="' . $event->ev_name . '" />';
                echo '<meta property="og:image" content="' . $event->event_img_url . '" />';
                echo '<meta property="og:type" content="website" />';
                echo '<meta property="og:description" content="'. ticketmachine_i18n_date("d.m.Y", $event->ev_date) .' @ '. ticketmachine_i18n_date("H:i", $event->ev_date) .'" />';
            }
       }
    }

    //Underscore
    wp_enqueue_script( 'underscore_JS', plugins_url('assets/js/ext/underscore.js', __FILE__ ) );

    //Calendar Packages
    wp_enqueue_style( 'calendar_CSS_1', plugins_url('assets/packages/core/main.css', __FILE__ ) );
    wp_enqueue_style( 'calendar_CSS_2', plugins_url('assets/packages/daygrid/main.css', __FILE__ ) );
    wp_enqueue_style( 'calendar_CSS_3', plugins_url('assets/packages/timegrid/main.css', __FILE__ ) );
    wp_enqueue_style( 'calendar_CSS_4', plugins_url('assets/packages/list/main.css', __FILE__ ) );
    wp_enqueue_style( 'calendar_CSS_5', plugins_url('assets/packages/bootstrap/main.css', __FILE__ ) );
    
    wp_enqueue_script( 'calendar_JS_1', plugins_url('assets/packages/core/main.js', __FILE__ ) );
    wp_enqueue_script( 'calendar_JS_2', plugins_url('assets/packages/interaction/main.js', __FILE__ ) );
    wp_enqueue_script( 'calendar_JS_3', plugins_url('assets/packages/daygrid/main.js', __FILE__ ) );
    wp_enqueue_script( 'calendar_JS_4', plugins_url('assets/packages/timegrid/main.js', __FILE__ ) );
    wp_enqueue_script( 'calendar_JS_5', plugins_url('assets/packages/list/main.js', __FILE__ ) );
    wp_enqueue_script( 'calendar_JS_6', plugins_url('assets/packages/bootstrap/main.js', __FILE__ ) );

    add_action( 'wp_ajax_my_action', function() use ($api, $ticketmachine_globals) {

        $params = [ 
            "query" => sanitize_text_field($_REQUEST['q']), 
            "sort" =>  sanitize_text_field($_REQUEST['sort']), 
            "tag" =>  sanitize_text_field($_REQUEST['tag']), 
            "approved" => 1
        ];

		$params = (object)$params;
		if(empty($params->sort)){
			$params->sort = "ev_date";
		}

		$url = "https://cloud." . $api->environment . "ticketmachine.de/api/v2/events?";
		
		if($ticketmachine_globals->organizer && $ticketmachine_globals->organizer != "" ){
			$url .= "organizer.og_abbreviation[eq]=" . $ticketmachine_globals->organizer;
		}elseif($params->organizer){
			$url .= "organizer.og_abbreviation[eq]=" . $params->organizer;
		}
		
		if(empty($params->show_old)) {
			$url .= "&endtime[gte]=" . $ticketmachine_globals->first_event_date;
		}
		$url .= "&sort=". $params->sort;
		if(!empty($params->per_page)) {
			$url .= "&per_page=" . (int)$params->per_page;
		}
		
		if(!empty($params->query)) {
			$url .= "&ev_name[contains]=" . htmlspecialchars(urlencode($params->query));
		}
		
		if(!empty($params->tag)) {
			$url .= "&tags[eq]=" . htmlspecialchars(urlencode($params->tag));
		}
		
		if(isset($params->approved)) {
			$url .= "&approved[eq]=" . (int)$params->approved;
		}

        $headers = array(
            'Authorization' => 'Bearer ' . $ticketmachine_globals->api_access_token,
            'Accept' => 'application/json'
        );

        $resource = wp_remote_get($url, array(
			'method'  => 'GET',
			'timeout' => 45,
			'headers' => $headers
        ));
        echo "<pre>"; 
        print_r($headers);
        print_r($url);
        echo "</pre>";
            
        wp_send_json_success($resource);

    });
    add_action( 'wp_enqueue_scripts', 'enqueue_my_action_script' );

    function enqueue_my_action_script() {
        wp_enqueue_script( 'my-action-script', plugins_url('assets/js/calendar.js', __FILE__ ) );
        wp_localize_script( 'my-action-script', 'my_action_data', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
        ) );
    }
    
    add_filter( 'oembed_response_data', 'ticketmachine_disable_embeds_filter_oembed_response_data_' );
    function ticketmachine_disable_embeds_filter_oembed_response_data_( $data ) {
        unset($data['author_url']);
        unset($data['author_name']);
        return $data;
    }

	add_action('wp_head','ticketmachine_event_metadata');
    if(isset($_GET['id']) && $_GET['id'] > 0){
        add_action('wp_head','ticketmachine_event_metadata_event');
    }

?>
