<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
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
        global $tm_globals, $tm_api, $wpdb, $ticketmachine_db_version, $wp_version;
        $token_check_failed = 0;

        if(isset($tm_globals->activated) && $tm_globals->activated > 0) {
            if(time() > ($tm_globals->api_refresh_last + $tm_globals->api_refresh_interval)){
                
                $actual_config = (object)$wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_config LIMIT 0,1")[0];

                if(!empty($actual_config->api_refresh_token) && $actual_config->api_refresh_token == $tm_globals->api_refresh_token) {
                    $token = ticketmachine_tmapi_get_access_token($actual_config->api_refresh_token, "update");

                    if(isset($token['access_token'])){
                        $save_array = array(
                            "api_access_token" => $token['access_token'],
                            "api_refresh_token" => $token['refresh_token'],
                            "api_refresh_last" => time(),
                            "api_refresh_interval" => $token['expires_in']/2,
                            "api_token_failed" => false,
                        );
            
                        $wpdb->update(
                            $wpdb->prefix . "ticketmachine_config",
                            $save_array,
                            array('id' => $tm_globals->id)
                        );
                        $tm_globals->api_access_token = $token['access_token'];
                        $tm_globals->api_refresh_token = $token['refresh_token'];
                        $tm_globals->activated = 1;
                    }else{
                        $save_array = array(
                            "api_token_failed" => 1,
                        );
            
                        $wpdb->update(
                            $wpdb->prefix . "ticketmachine_config",
                            $save_array,
                            array('id' => $tm_globals->id)
                        );
                        $token_check_failed = 1;
                    }
                }else{
                    $tm_globals->api_access_token = $actual_config->api_access_token;
                    $tm_globals->api_refresh_token = $actual_config->api_refresh_token;
                }
            }
            
            if(time() > ($tm_globals->api_refresh_last + $tm_globals->api_refresh_interval + 60000)){
                if($actual_config->api_token_failed == 1 && $token_check_failed == 1) {
                    sleep(4000);
                    $token = ticketmachine_tmapi_get_access_token($actual_config->api_refresh_token, "update");

                    if(!isset($token['access_token'])){
                        $save_array = array(
                            "api_access_token" => "",
                            "api_refresh_token" => "",
                            "api_refresh_last" => time()-1000,
                            "api_token_failed" => false,
                        );

                        $wpdb->update(
                            $wpdb->prefix . "ticketmachine_config",
                            $save_array,
                            array('id' => $tm_globals->id)
                        );
                        
                        $tm_globals->activated = 0;
                        
                        // COULD NOT GET AN ACCESS TOKEN!
                        $php_version = PHP_VERSION ?? $PHP_VERSION;
                        $headers = array('Content-Type: text/html; charset=UTF-8');
                        $multiple_recipients = array(
                            'support@net-up.de',
                            get_option('admin_email')
                        );

                        $rows = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}ticketmachine_log ORDER BY id DESC LIMIT 0,20");
                    
                        $sendTMLog = "";

                        foreach ($rows as $row) {
                            $sendTMLog .= "[" . date("c", $row->log_time) . "] - " . $row->log_type . "<br/>";
                            $sendTMLog .= $row->log_message . "]<br/><br/>";
                        }

                        $subj = 'ERROR: Wordpress Plugin - TicketMachine Event Manager & Calendar';
                        $body = 'TicketMachine could not get a new access token!<br/><br/>Website: ' . get_site_url() .'<br/>Wordpress Version: ' . $wp_version . '<br/>Plugin Version: ' . $ticketmachine_db_version . '<br/>PHP Version: ' . $php_version . '<br/>Admin Email: ' . get_option('admin_email') . '<br/><br/>Log:<br/>' . $sendTMLog;
                        wp_mail( $multiple_recipients, $subj, $body, $headers );
                    }
                }
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
?>