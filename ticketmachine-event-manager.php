<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	/*
	Plugin Name:        TicketMachine Event Manager & Calendar
    Plugin URI:         https://www.ticketmachine.de/
	Description:        Easily create and manage cloud-based events for your wordpress site.
	Version:            1.7.0
    Requires at least:  4.5
    Author:             NET-UP AG
	Author URI:         https://www.net-up.de
	Text Domain: 		ticketmachine-event-manager
	Domain Path: 		/languages
	*/

	require_once(plugin_dir_path( __FILE__ ) . "/utils.php");

    add_action( 'wp_enqueue_scripts', 'ticketmachine_register_core_files' );
    add_action( 'wp_enqueue_scripts', 'ticketmachine_register_calendar_files' );

	add_action( 'init', 'ticketmachine_wpdocs_load_textdomain' );
	global $ticketmachine_db_version;
	$ticketmachine_db_version = "1.6.5";
	
	// Load translations if they don't already exist
    function ticketmachine_wpdocs_load_textdomain() {
        load_plugin_textdomain( 'ticketmachine-event-manager', false, plugin_dir_path( __FILE__ ) . '/languages' ); 
	}

	add_action( 'init', 'ticketmachine_check_some_other_plugin' );

	// Check if plugin is already installed
	function ticketmachine_check_some_other_plugin() {
		if(!headers_sent() && !session_id()){session_start();}
		if (!function_exists('is_plugin_active')) {
			include_once(ABSPATH . 'wp-admin/includes/plugin.php');
		}
		if (is_plugin_active('ticketmachine-event-manager/ticketmachine-event-manager.php')){
			global $wpdb, $tm_globals, $tm_api;
		
			$ticketmachine_config = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_config LIMIT 0,1");
			$ticketmachine_config = $ticketmachine_config[0];
		
			$tm_globals = (object)$ticketmachine_config;
			$tm_globals->timeout = 0;
			if(!empty($tm_globals->api_refresh_token) && !empty($tm_globals->api_access_token)) {
				$tm_globals->activated = 1;
			}
			
			if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
				$tm_globals->locale = ICL_LANGUAGE_CODE;
			}else{
				$tm_globals->locale = get_locale();
			}
			$tm_globals->locale_short = strtok($tm_globals->locale, '_');
			
			$tm_api = new stdClass();
			$tm_api->auth = new stdClass();
		
			//get page slugs
			if(!empty($tm_globals->events_slug_id) && $tm_globals->events_slug_id > 0){
				$tm_post = (object)get_post($tm_globals->events_slug_id); 
				$tm_globals->events_slug = $tm_post->post_name;
			}
			if(!empty($tm_globals->event_slug_id) && $tm_globals->event_slug_id > 0){
				$tm_post = (object)get_post($tm_globals->event_slug_id); 
				$tm_globals->event_slug = $tm_post->post_name;
			}
			if(!empty($tm_globals->privacy_slug_id) && $tm_globals->privacy_slug_id > 0){
				$tm_post = (object)get_post($tm_globals->privacy_slug_id); 
				$tm_globals->privacy_slug = $tm_post->post_name;
			}
		
			switch ($tm_globals->event_grouping) {
				case 'Month':
					$tm_globals->group_by = "m Y";
					$tm_globals->format_date = "F Y";
					break;
				case 'Year':
					$tm_globals->group_by = "Y";
					$tm_globals->format_date = "Y";
					break;
				case 'None':
					$tm_globals->group_by = "";
					$tm_globals->format_date = "";
					break;
				default:
					$tm_globals->group_by = "Y";
					$tm_globals->format_date = "Y";
					break;
			}
			
			$tm_globals->map_query_url = "https://www.google.de/maps?q=";
			$tm_globals->lang = "de";
			$tm_globals->current_url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
			
			/* Backend Settings */
			$tm_api->client_id = $ticketmachine_config->api_client_id;
			$tm_api->client_secret = $ticketmachine_config->api_client_secret;
			$tm_globals->environment = $ticketmachine_config->api_environment;
			$tm_globals->timezone = "";
			$tm_globals->inverted_timezone = "";
			
			$tm_globals->search_query = "";
			if(isset($_GET['q'])){
				$tm_globals->search_query = htmlentities(sanitize_text_field($_GET['q']));
			}
			$tm_globals->tag = "";
			if(isset($_GET['tag'])){
				$tm_globals->tag = htmlentities(sanitize_text_field($_GET['tag']));
			}
			$tm_globals->organizer = $ticketmachine_config->organizer;
			$tm_globals->organizer_id = (int)$ticketmachine_config->organizer_id;
		
			$tm_globals->first_event_date = date('Y-m-d');
			$tm_globals->first_event_date_calendar = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) );
		
			if($tm_globals->environment == "staging"){
				$tm_api->environment = $tm_globals->environment . ".";
			}else{
				$tm_api->environment = "";
			}
		
			$tm_api->scheme = "https";
			
			# Build API base url
			if(!isset($tm_globals->api_state)){
				$tm_globals->api_state = "";
			}
			$tm_api->base_url = $tm_api->token = $tm_api->scheme . "://cloud." . $tm_api->environment;
			
			if(!empty($ticketmachine_config->webshop_url)){
				$tm_globals->webshop_url = $tm_api->scheme . "://" . $ticketmachine_config->webshop_url . ".ticketmachine.de/" . $tm_globals->lang;;
			}else{
				$tm_globals->webshop_url = $tm_api->scheme . "://" . $tm_globals->organizer . ".ticketmachine.de/" . $tm_globals->lang;
			}
			
		
			$tm_api->token = $tm_api->base_url . "ticketmachine.de/oauth/token";
			$tm_api->auth->url = $tm_api->base_url . "ticketmachine.de/oauth/token";
		
			$tm_api->auth->key = $tm_api->client_id.":".$tm_api->client_secret;
			$tm_api->auth->encoded_key = base64_encode($tm_api->auth->key);
			$tm_api->auth->headers = array();
			$tm_api->auth->headers = [
				'Authorization: Basic' . $tm_api->auth->encoded_key,
				'Content-Type: application/x-www-form-urlencoded'
			];
		
			$tm_api->auth->start_uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$tm_api->auth->proxy = $tm_api->scheme . "://www.ticketmachine.de/oauth/proxy.php";
			
			$tm_api->auth->data = array(
				'response_type' => 'code',
				'client_id' => $tm_api->client_id,
				'redirect_uri' => $tm_api->auth->proxy,
				'state' => $tm_globals->api_state,
				'environment' => $tm_api->environment,
				'start_uri' => $tm_api->auth->start_uri,
				'scope' => 'public organizer organizer/event'
			);
		
			$tm_api->auth->access = array(
				'grant_type' => 'client_credentials',
				'client_id' => $tm_api->client_id,
				'client_secret' => $tm_api->client_secret,
				'scope' => "public organizer organizer/event"
			);
		
			switch ($tm_globals->lang) {
				case 'en':
					setlocale(LC_TIME, 'en_US.UTF-8');
					break;
				case 'de':
					setlocale(LC_TIME, 'de_DE.UTF-8');
					break;
			}

			if(isset($tm_globals->activated) && $tm_globals->activated > 0){
				ticketmachine_tmapi_refresh_token_check();
			}
		}
	}

    register_activation_hook(__FILE__, 'ticketmachine_activate');
    register_deactivation_hook(__FILE__, 'ticketmachine_deactivate');

	// Run when plugin is activated
    function ticketmachine_activate( ) {
        global $wpdb;
		global $ticketmachine_db_version;

        //create events overview page
        $new_page_title = 'Events';
        $new_page_slug = 'events';
        $new_page_content = '[ticketmachine page="event_list"]';
        $new_page_template = '';
		$create_new_page = false;
    
        $page_check = get_page_by_path($new_page_slug);

        if(!isset($page_check->ID)){
			$create_new_page = true;
        }else{
			if(!has_shortcode( $page_check->post_content, 'ticketmachine')){
				$new_page_slug = 'tm-events';
				$page_check = get_page_by_path($new_page_slug);
				if(!isset($page_check->ID)){
					$create_new_page = true;
				}
			}
		}

        $new_page = array(
            'post_type' => 'page',
            'post_title' => $new_page_title,
            'post_name' => $new_page_slug,
            'post_content' => $new_page_content,
            'post_status' => 'publish',
            'post_author' => 1,
        );

		if($create_new_page === true){
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
		$create_new_page = false;
    
        $page_check = get_page_by_path($new_page_slug);

        if(!isset($page_check->ID)){
			$create_new_page = true;
        }else{
			if(!has_shortcode( $page_check->post_content, 'ticketmachine')){
				$new_page_slug = 'tm-event';
				$page_check = get_page_by_path($new_page_slug);
				if(!isset($page_check->ID)){
					$create_new_page = true;
				}
			}
		}

        $new_page = array(
            'post_type' => 'page',
            'post_title' => $new_page_title,
            'post_name' => $new_page_slug,
            'post_content' => $new_page_content,
            'post_status' => 'publish',
            'post_author' => 1,
        );

		if($create_new_page === true){
			$new_page_id = wp_insert_post($new_page);
			if(!empty($new_page_template)){
				update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
			}
		}

		$event_slug = get_page_by_path($new_page_slug);
		
        $charset = $wpdb->get_charset_collate();
        $charset_collate = $wpdb->get_charset_collate();

        $table = $wpdb->prefix . 'ticketmachine_config';
        $sql = "CREATE TABLE $table (
                    id mediumint(9) DEFAULT 1 NOT NULL,
                    organizer_id int(11) DEFAULT 0 NOT NULL,
                    organizer varchar(64) DEFAULT '' NOT NULL,
                    webshop_url varchar(64) DEFAULT '' NOT NULL,
                    api_client_id varchar(64) DEFAULT 'c16727aa80540e51edcd276641c6f68974bb312ec5b17b75a3bc0ba254236a14' NOT NULL,
                    api_client_secret varchar(64) DEFAULT '1d3fb26a828f0e09700464997271c5236bb7d3194992299331eaa1c420a7f522' NOT NULL,
                    api_refresh_token varchar(64) DEFAULT '' NOT NULL,
                    api_access_token varchar(64) DEFAULT '' NOT NULL,
                    api_refresh_last int(11) DEFAULT " . time() . " NOT NULL,
                    api_refresh_interval int(11) DEFAULT 3600 NOT NULL,
                    api_environment varchar(64) DEFAULT 'shop' NOT NULL,
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
                    show_additional_info bit(1) DEFAULT 1 NOT NULL,
                    detail_page_layout int(3) DEFAULT 2 NOT NULL,
                    event_grouping varchar(64) DEFAULT 'Year' NOT NULL,
                    events_slug_id int(11) DEFAULT " . $events_slug->ID . " NOT NULL,
                    event_slug_id int(11) DEFAULT " . $event_slug->ID . " NOT NULL,
                    privacy_slug_id varchar(11) DEFAULT 0 NOT NULL,
                PRIMARY KEY  (id)
                ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        
        $wpdb->query("INSERT IGNORE INTO $table (id) VALUES (1)");
        
        $table = $wpdb->prefix . 'ticketmachine_design';
        $sql = "CREATE TABLE $table (
                    id mediumint(9) DEFAULT 1 NOT NULL,
                    link_text_color varchar(64) DEFAULT '#0fb1e4' NOT NULL,
                    link_text_color_hover varchar(64) DEFAULT '#0056b3' NOT NULL,
                    container_background_color varchar(64) DEFAULT '#ffffff' NOT NULL,
                    box_text_color varchar(64) DEFAULT '#222222' NOT NULL,
                    box_meta_color varchar(64) DEFAULT '#222222' NOT NULL,
                    box_header_color varchar(64) DEFAULT '#222222' NOT NULL,
                    box_border_color varchar(64) DEFAULT '#DDDDDD' NOT NULL,
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
        dbDelta( $sql );
        
        $wpdb->query("INSERT IGNORE INTO $table (id) VALUES (1)");
        
        $table = $wpdb->prefix . 'ticketmachine_organizers';
        $sql = "CREATE TABLE IF NOT EXISTS $table (
					id int(11) NOT NULL AUTO_INCREMENT,
                    approved tinyint(1) DEFAULT 0 NOT NULL,
                    og_name varchar(128) DEFAULT '' NOT NULL,
                    og_street varchar(128) DEFAULT '' NOT NULL,
                    og_house_number varchar(128) DEFAULT '' NOT NULL,
                    og_zip varchar(128) DEFAULT '' NOT NULL,
                    og_city varchar(128) DEFAULT '' NOT NULL,
                    og_country varchar(128) DEFAULT '' NOT NULL,
                    og_email varchar(128) DEFAULT '' NOT NULL,
                    og_phone varchar(128) DEFAULT '' NOT NULL,
                	PRIMARY KEY  (id)
                ) $charset_collate;";
		dbDelta( $sql );
        
        $table = $wpdb->prefix . 'ticketmachine_organizers_events_match';
        $sql = "CREATE TABLE $table (
					id int(11) NOT NULL AUTO_INCREMENT,
                    organizer_id int(11) DEFAULT 0 NOT NULL,
                    api_event_id int(11) DEFAULT 0 NOT NULL,
                    local_event_id int(11) DEFAULT 0 NOT NULL,
                	PRIMARY KEY  (id)
                ) $charset_collate;";
        dbDelta( $sql );
        
        $table = $wpdb->prefix . 'ticketmachine_log';
        $sql = "CREATE TABLE $table (
					id int(11) NOT NULL AUTO_INCREMENT,
                    log_message text DEFAULT '' NOT NULL,
                    log_type varchar(64) DEFAULT 'info' NOT NULL,
                    log_time int(11) DEFAULT 0 NOT NULL,
                	PRIMARY KEY  (id)
                ) $charset_collate;";
        dbDelta( $sql );
        update_option('ticketmachine_db_version', $ticketmachine_db_version);
	}
	
	function ticketmachine_update() {
		global $ticketmachine_db_version;
		if ( get_site_option( 'ticketmachine_db_version' ) != $ticketmachine_db_version) {
			ticketmachine_activate();
		}
	}
	add_action('plugins_loaded', 'ticketmachine_update');
	
	// Run when plugin is deactivated
    function ticketmachine_deactivate( ) {
	}

	function ticketmachine_enqueue_assets() {
		include_once( plugin_dir_path( __FILE__ ) . 'assets/css/custom.php');
		
		//Underscore
		wp_enqueue_script( 'underscore' );

		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_style( 'jquery-ui_CSS' );
		//Cookies
		wp_enqueue_script( 'cookies_JS' );
		//Popper
		wp_enqueue_script( 'popper_JS' );
		//Bootstrap
		wp_enqueue_script( 'bootstrap-4_JS' );
		wp_enqueue_style( 'boostrap-4_CSS' );

		//Icons
		wp_enqueue_style( 'fontawesome-5_CSS' );
		//Core
		wp_enqueue_style( 'core_CSS' );
		wp_enqueue_script( 'core_JS' );
		//iCal
		wp_enqueue_script( 'iCal_JS' );
		//FileSaver
		wp_enqueue_script( 'fileSaver_JS' );
	}
	
	// Run only if TicketMachine shortcode is found on current page
	function ticketmachine_initialize( $atts ) {
			
        global $tm_globals, $tm_api, $wpdb;

		ticketmachine_enqueue_assets();

		include_once( plugin_dir_path( __FILE__ ) . 'pages/error.php');
		include_once( plugin_dir_path( __FILE__ ) . 'partials/error.php');
		
		if( $atts ) {
			
			foreach($_GET as $key => $value) {
				$atts[$key] = $value;
			}
			
            $ticketmachine_output = "<div class='ticketmachine_page' data-locale=" . esc_html($tm_globals->locale_short) . ">";
            
            if(isset($atts['page'])){
				include_once "partials/_pagination.php";
                switch ($atts['page']) {
                    case 'event_list':
                        include_once "partials/_search_header.php";
                        include_once "partials/_tag_header.php";
                        include_once "pages/events.php";
                        $ticketmachine_output .= ticketmachine_display_events( $atts );
                        break;
                    case 'event_boxes':
                        include_once "partials/_search_header.php";
                        include_once "partials/_tag_header.php";
                        include_once "pages/events.php";
                        $ticketmachine_output .= ticketmachine_display_events( $atts );
                        break;
                    case 'event_details':
                        include_once "partials/_event_page_information.php";
                        include_once "partials/_event_page_tickets.php";
                        include_once "partials/_event_page_details.php";
                        include_once "partials/_event_page_google_map.php";
                        include_once "partials/_event_organizer_details.php";
                        include_once "partials/_event_page_actions.php";
                        include_once "pages/event.php";
                        $ticketmachine_output .= ticketmachine_display_event( $atts );
                        break;
                }
            }elseif($atts['widget']){
				include_once "partials/_pagination.php";
                switch ($atts['widget']) {
                    case 'event_boxes':
                        include_once "widgets/event_boxes.php";
                        $ticketmachine_output .= ticketmachine_widget_event_boxes( $atts, 1 );
                        break;
                    case 'event_list':
                        include_once "widgets/event_list.php";
                        $ticketmachine_output .= ticketmachine_widget_event_list( $atts, 1 );
                        break;
                    case 'event_calendar':
                        include_once "widgets/event_calendar.php";
                        $ticketmachine_output .= ticketmachine_widget_event_calendar( $atts, 1 );
                        break;
                }
            }

			$ticketmachine_output .= "</div>";
			
			$ticketmachine_output = shortcode_unautop($ticketmachine_output);
			return $ticketmachine_output;
			
		}
	}

	add_action( 'wp_ajax_ticketmachine_calendar', 'ticketmachine_calendar_callback' );
	add_action( 'wp_ajax_nopriv_ticketmachine_calendar', 'ticketmachine_calendar_callback' );

	add_shortcode( 'ticketmachine', 'ticketmachine_initialize' );
	
	// Precompile styles and javascript
	function ticketmachine_register_core_files () {
		
		//jQuery
		wp_register_script( 'jquery-ui-datepicker', array("jquery") );
		wp_register_style( 'jquery-ui_CSS', plugins_url('assets/css/ext/jquery_ui.css', __FILE__ ) );
		//Cookies
		wp_register_script( 'cookies_JS', plugins_url( "assets/js/cookies.js", __FILE__ ) );
		//Popper
		wp_register_script( 'popper_JS', plugins_url('assets/js/ext/popper.js', __FILE__ ) );
		//Bootstrap
		wp_register_script( 'bootstrap-4_JS', plugins_url( "assets/js/ext/bootstrap.min.js", __FILE__ ), array("jquery") );
		wp_register_style( 'boostrap-4_CSS', plugins_url('assets/css/ext/bootstrap.min.css', __FILE__ ) );
		//Icons
		wp_register_style( 'fontawesome-5_CSS', plugins_url('assets/css/ext/fontawesome.min.css', __FILE__ ) );
		//Core
		wp_register_style( 'core_CSS', plugins_url('assets/css/ticketmachine.css', __FILE__ ) );
		wp_register_script( 'core_JS', plugins_url('assets/js/ticketmachine.js', __FILE__ ), array("jquery") );
        //iCal
        wp_register_script( 'iCal_JS', plugins_url('assets/js/ext/ics.js', __FILE__ ) );
        //FileSaver
		wp_register_script( 'fileSaver_JS', plugins_url('assets/js/ext/filesaver.js', __FILE__ ) );
		if(is_admin()){ 
			ticketmachine_enqueue_assets();
		}
    }
	
	// Precompile calendar dependencies
    function ticketmachine_register_calendar_files() {
        //Calendar Styles
        wp_register_style( 'calendar_CSS_1', plugins_url('assets/packages/core/main.css', __FILE__ ) );
        wp_register_style( 'calendar_CSS_2', plugins_url('assets/packages/daygrid/main.css', __FILE__ ) );
        wp_register_style( 'calendar_CSS_3', plugins_url('assets/packages/timegrid/main.css', __FILE__ ) );
        wp_register_style( 'calendar_CSS_4', plugins_url('assets/packages/list/main.css', __FILE__ ) );
        wp_register_style( 'calendar_CSS_5', plugins_url('assets/packages/bootstrap/main.css', __FILE__ ) );
        //Calendar Scripts
        wp_register_script( 'calendar_JS_1', plugins_url('assets/packages/core/main.js', __FILE__ ) );
        wp_register_script( 'calendar_JS_2', plugins_url('assets/packages/interaction/main.js', __FILE__ ) );
        wp_register_script( 'calendar_JS_3', plugins_url('assets/packages/daygrid/main.js', __FILE__ ) );
        wp_register_script( 'calendar_JS_4', plugins_url('assets/packages/timegrid/main.js', __FILE__ ) );
        wp_register_script( 'calendar_JS_5', plugins_url('assets/packages/list/main.js', __FILE__ ) );
        wp_register_script( 'calendar_JS_6', plugins_url('assets/packages/bootstrap/main.js', __FILE__ ) );
		//Calendar Ajax
        wp_register_script( 'ticketmachine-calendar-script', plugins_url('assets/js/calendar.js', __FILE__ ), array("core_JS") );
    }
	
	// Run only if inside of admin backend
    if(is_admin()){
        include_once( plugin_dir_path( __FILE__ ) . 'admin/admin.php');
    }

	// Add general metadata for TicketMachine pages
	function ticketmachine_event_metadata() {
        if(isset($_GET['id']) && $_GET['id'] > 0){
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            echo '<meta property="og:url" content="' . esc_url($actual_link) . '" />';
			echo '<meta property="og:type" content="website" />';
		}
	}

	// Add metadata only for Event pages
	function ticketmachine_event_metadata_event() {
        if(isset($_GET['id']) && $_GET['id'] > 0){
            $params = [ "id" => absint($_GET['id']) ];
            $event = ticketmachine_tmapi_event($params);
            if(isset($event->id)){            
                echo '<title>' . esc_html($event->ev_name) . '</title>';
                echo '<meta property="og:title" content="' . esc_html($event->ev_name) . '" />';
                echo '<meta property="og:image" content="' . esc_url($event->event_img_url) . '" />';
                echo '<meta property="og:type" content="website" />';
                echo '<meta property="og:description" content="'. ticketmachine_i18n_date("d.m.Y", $event->ev_date) .' @ '. ticketmachine_i18n_date("H:i", $event->ev_date) .'" />';
            }
       }
	}

	// Send GET or POST request to the TicketMachine API
	function ticketmachine_apiRequest($tm_url, $tm_post=FALSE, $method="GET", $headers=array()) {
	  global $tm_globals, $tm_api, $tm_debug;

	  $headers = array();
	  $headers = ticketmachine_array_push_assoc($headers, 'User-Agent', 'https://www.ticketmachine.de/');

	  if(isset($tm_globals->api_access_token))
		  $headers = ticketmachine_array_push_assoc($headers, 'Authorization', 'Bearer ' . $tm_globals->api_access_token);

	  if($method == "POST") {

		if($tm_post) {
			$headers = ticketmachine_array_push_assoc($headers, 'Content-Type', 'application/json');
			
			$resource = wp_remote_post($tm_url, array(
				'method'  => 'POST',
				'sslverify' => FALSE,
				'timeout' => 45,
				'headers' => $headers,
				'body' 	  => str_replace("\'", "'", str_replace("\r\n", "<br>", str_replace("&nbsp;", "", str_replace('\"', "'", json_encode($tm_post, JSON_UNESCAPED_SLASHES)))))
			));
				
				$log_resource = $resource;
				if(!is_wp_error($resource)){
					$log_resource['headers'] = (array)$log_resource["headers"];
				}
				$log = array(
					"url" => (array)$tm_url,
					"sent" => (array)$tm_post,
					"response" => $log_resource
				);
			
				ticketmachine_log(json_encode($log), "info");

		}

	  }else{

		if($tm_post) {
			$headers = ticketmachine_array_push_assoc($headers, 'Accept', 'application/json');
		}

		$resource = wp_remote_get($tm_url, array(
			'method'  => 'GET',
			'sslverify' => FALSE,
			'timeout' => 45,
			'headers' => $headers
		));

	  }

	  if(!is_wp_error($resource)){
		if(isset($resource['body'])){
			$response = $resource['body'];
			return json_decode($response, true);
		}
	  }
	  
	}

	/* API Requests */
	// Get event list
	function ticketmachine_tmapi_events($params=array(), $method="GET", $tm_post=FALSE,  $headers=array(), $tm_url_only=0){
		global $tm_api, $tm_globals;

		$params = (object)$params;
		if(empty($params->sort)){
			$params->sort = "ev_date";
		}
		
		if(!empty($params->q)) {
			$params->query = $params->q;
		}

		$tm_url = $tm_api->base_url . "ticketmachine.de/api/v2/events?";
		
		if($tm_globals->organizer && $tm_globals->organizer != "" ){
			$tm_url .= "organizer.og_abbreviation[eq]=" . $tm_globals->organizer;
		}elseif($params->organizer){
			$tm_url .= "organizer.og_abbreviation[eq]=" . $params->organizer;
		}
		
		if(empty($params->show_old)) {
			$tm_url .= "&endtime[gte]=" . $tm_globals->first_event_date;
		}
		$tm_url .= "&sort=". $params->sort;
		if(empty($params->per_page)) {
			$params->per_page = 30;
		}
		$tm_url .= "&per_page=" . (int)$params->per_page;
		if(!empty($params->pg)) {
			$tm_url .= "&offset=" . (int)$params->per_page*($params->pg-1);
		}
		
		if(!empty($params->query)) {
			$tm_url .= "&ev_name[contains]=" . htmlspecialchars(urlencode($params->query));
		}
		
		if(!empty($params->tag)) {
			$tm_url .= "&tags[eq]=" . htmlspecialchars(urlencode($params->tag));
		}
		
		if(isset($params->approved)) {
			$tm_url .= "&approved[eq]=" . (int)$params->approved;
		}

        ticketmachine_debug($tm_url);

		if(isset($tm_url_only) && $tm_url_only == 1) {
			return $tm_url;
		}else{
			$events = (object)ticketmachine_apiRequest($tm_url, $tm_post, $method, $headers);
			ticketmachine_debug($events->meta);
			return $events;
		}
	}

	// Get event
	function ticketmachine_tmapi_event($params=array(), $method="GET", $tm_post=FALSE, $headers=array()){
		global $tm_api, $tm_globals;
		if($method == "POST"){
			$tm_post = $params;
		}
		$params = (object)$params;

		$tm_url = $tm_api->base_url. "ticketmachine.de/api/v2/events/";
		if(!empty($params && isset($params->id))){ 
			$tm_url .= (int)$params->id;
		}

		if(!empty($params->categories)) {
			$tm_url .= "?categories=true";
		}

		$event = (object)ticketmachine_apiRequest($tm_url, $tm_post, $method, $headers);

		return $event;
	}

	// Copy event
	function ticketmachine_tmapi_event_copy($params){
		global $tm_api, $tm_globals;

		$tm_url = $tm_api->base_url . "ticketmachine.de/api/v2/events/" . absint($_GET['id']) . "/copy";

		$event = ticketmachine_apiRequest($tm_url, $params, "POST");
		return (object)$event;
	}

	// Delete event
	function ticketmachine_tmapi_event_delete($params){
		global $tm_api, $tm_globals;

		$tm_url = $tm_api->base_url . "ticketmachine.de/api/v2/events/" . absint($_GET['id']) . "/delete";

		$event = ticketmachine_apiRequest($tm_url, $params, "POST");
		return (object)$event;
	}

	// Get connected organizer
	function ticketmachine_tmapi_organizers($params=array(), $method="GET", $tm_post=FALSE, $headers=array()){
		global $tm_api, $tm_globals;

		$params = (object)$params;

		$tm_url = $tm_api->base_url . "ticketmachine.de/api/v2/organizers/me";

		$organizer = ticketmachine_apiRequest($tm_url, $tm_post, $method, $headers);

		return $organizer;
	}

	//Check if access token expired
	function ticketmachine_tmapi_refresh_token_check() {
		global $tm_globals, $tm_api, $wpdb;

		if(time() > ($tm_globals->api_refresh_last + $tm_globals->api_refresh_interval) && isset($tm_globals->activated) && $tm_globals->activated > 0){
			
			$actual_config = (object)$wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_config LIMIT 0,1")[0];

			if(!empty($actual_config->api_refresh_token) && $actual_config->api_refresh_token == $tm_globals->api_refresh_token) {
				$token = ticketmachine_tmapi_get_access_token($actual_config->api_refresh_token, "update");

				if(isset($token['access_token'])){
					$save_array = array(
						"api_access_token" => $token['access_token'],
						"api_refresh_token" => $token['refresh_token'],
						"api_refresh_last" => time(),
						"api_refresh_interval" => $token['expires_in']/2
					);
		
					$wpdb->update(
						$wpdb->prefix . "ticketmachine_config",
						$save_array,
						array('id' => $tm_globals->id)
					);
					$tm_globals->api_access_token = $token['access_token'];
					$tm_globals->api_refresh_token = $token['refresh_token'];
					$tm_globals->activated == 1;
				}else{
					$tm_globals->timeout++;
					if($tm_globals->timeout < 3){
						sleep(1);
						ticketmachine_tmapi_refresh_token_check();
					}else{
						$tm_globals->activated == 0;
						$save_array = array(
							"api_access_token" => "",
							"api_refresh_token" => "",
							"api_refresh_last" => time()-1000,
							"api_refresh_interval" => $token['expires_in']/2
						);
		
						$wpdb->update(
							$wpdb->prefix . "ticketmachine_config",
							$save_array,
							array('id' => $tm_globals->id)
						);
					}
				}
			}else{
				$tm_globals->api_access_token = $actual_config->api_access_token;
				$tm_globals->api_refresh_token = $actual_config->api_refresh_token;
			}
		}
	}

	// Get new access token
	function ticketmachine_tmapi_get_access_token($refresh_token, $status="update") {
		global $tm_api, $tm_globals, $wpdb;

		$actual_config = (object)$wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_config LIMIT 0,1")[0];

		if($status == "new"){
			$tm_api->auth->code = array(
				'grant_type' => 'authorization_code',
				'client_id' => $tm_api->client_id,
				'client_secret' => $tm_api->client_secret,
				'code' => $refresh_token,
				'redirect_uri' => $tm_api->auth->proxy,
				'scope' => "public organizer organizer/event"
			);
		}
		if($status == "update"){
			$tm_api->auth->code = array(
				'grant_type' => 'refresh_token',
				'client_id' => $actual_config->api_client_id,
				'client_secret' => $actual_config->api_client_secret,
				'refresh_token' => $actual_config->api_refresh_token,
				'redirect_uri' => $tm_api->auth->proxy,
				'scope' => "public organizer organizer/event"
			);
		}

		$token = ticketmachine_apiRequest($tm_api->token, $tm_api->auth->code, "POST");

		$tm_globals->api_access_token = $token['access_token'];

		
		return $token;
	}

	// Get all categories
	function ticketmachine_tmapi_categories($params=array(), $method="GET", $headers=array()){
		global $tm_api, $tm_globals;

		$params = (object)$params;
		if(!$params->sort){
			$params->sort = "name";
		}

		$tm_url = $tm_api->base_url . "ticketmachine.de/api/v2/events/tags";

		$categories = ticketmachine_apiRequest($tm_url, $params, $method);
		return (object)$categories;
	}

	// Add to category
	function ticketmachine_tmapi_category_add($params=array(), $method="POST", $headers=array()){
		global $tm_api, $tm_globals;

		$params = (object)$params;
		$tm_url = $tm_api->scheme . "://cloud." . $tm_api->environment . "ticketmachine.de/api/v2/event/tags/types/category/add";

		$category = ticketmachine_apiRequest($tm_url, $params, $method);
		return (object)$category;
	}

	// Remove from category
	function ticketmachine_tmapi_category_remove($params=array(), $method="POST", $headers=array()){
		global $tm_api, $tm_globals;

		$params = (object)$params;
		$tm_url = $tm_api->scheme . "://cloud." . $tm_api->environment . "ticketmachine.de/api/v2/event/tags/types/category/remove";
		$category = ticketmachine_apiRequest($tm_url, $params, $method);
		return (object)$category;
	}

	
	// Remove author metadata on ticketmachine pages
    add_filter( 'oembed_response_data', 'ticketmachine_disable_embeds_filter_oembed_response_data_' );
    function ticketmachine_disable_embeds_filter_oembed_response_data_( $data ) {
        unset($data['author_url']);
        unset($data['author_name']);
        return $data;
	}
	
	add_action( 'template_redirect', 'ticketmachine_remove_wpseo' );
	// Remove YoastSEO metadata
	function ticketmachine_remove_wpseo() {
		if(isset($_GET['id']) && $_GET['id'] > 0){
			if (function_exists('YoastSEO')) {
				$front_end = YoastSEO()->classes->get( Yoast\WP\SEO\Integrations\Front_End_Integration::class );
				remove_action( 'wpseo_head', [ $front_end, 'present_head' ], -9999 );
			}
		}
	}

	add_action('wp_head','ticketmachine_event_metadata');
    if(isset($_GET['id']) && $_GET['id'] > 0){
		add_action('wp_head','ticketmachine_event_metadata_event');
    }

	// Load events into calendar
	function ticketmachine_calendar_callback() {
		global $tm_api, $tm_globals, $wpdb;

        $events = ticketmachine_tmapi_events($_REQUEST);
            
        $calendar = array();
        $i = 0;
                
        if(empty($events->result)) {	
        }else{

            foreach($events->result as $event) {
				$event = (object) $event;

				if(empty($event->state['sale_active'])){
					$event->link = '/' . esc_html($tm_globals->event_slug) .'/?id=' . esc_html($event->id);
				}else{
					$event->link = esc_html($tm_globals->webshop_url) .'/events/unseated/select_unseated?event_id=' . esc_html($event->id);
				}
                
                $params = [ "id" => $event->id ];
                
                if($event->state['sale_active'] > 0){
                    $event->status_color = "#d4edda";
                    $event->status_text_color = "#155724";
                }else{
                    $event->status_color = "#f8d7da";
                    $event->status_text_color = "#721c24";
                }

                //Override for free plugin
                $event->status_color = "#d4edda";
                $event->status_text_color = "#155724";
                
                $start = strtotime($event->ev_date) * 1000;
                $end = strtotime($event->endtime) * 1000;	

                if ($end < (strtotime("midnight", time())*1000)){
                    $event->status_color = "#eeeeee";
                    $event->status_text_color = "#999999";
                }elseif($i == 0) {
                    $i = 1;
                    $default_date = $start;
                }
                
                $calendar[] = array(
                    'id' => esc_html($event->id),
                    'title' => esc_html($event->ev_name),
                    'url' => "" . $event->link,
                    'start' => $start,
                    'end' => $end,
                    'class' => "event-success",
                    'color' => $event->status_color,
                    'textColor' => $event->status_text_color,
                    'defaultDate' => $default_date
                );
            }
        }

        $calendarData = $calendar;
        wp_send_json_success($calendarData);
        die();
    }
