<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    // Send GET or POST request to the TicketMachine API
    function ticketmachine_apiRequest($ticketmachine_url, $ticketmachine_post=FALSE, $method="GET", $headers=array()) {
        global $wpdb, $ticketmachine_globals, $ticketmachine_api, $ticketmachine_debug;

        if(!$headers) {
            $headers = array();
        }
        $headers = ticketmachine_array_push_assoc($headers, 'User-Agent', 'https://www.ticketmachine.de/');

        if(isset($ticketmachine_globals)) {
            if(isset($ticketmachine_globals->api_access_token))
                $headers = ticketmachine_array_push_assoc($headers, 'Authorization', 'Bearer ' . $ticketmachine_globals->api_access_token);

            if($method == "POST") {

                if($ticketmachine_post) {
                    $headers = ticketmachine_array_push_assoc($headers, 'Content-Type', 'application/json');
                    
                    $resource = wp_remote_post($ticketmachine_url, array(
                        'method'  => 'POST',
                        'sslverify' => FALSE,
                        'timeout' => 45,
                        'headers' => $headers,
                        'body' 	  => str_replace("\'", "'", str_replace("\r\n", "<br>", str_replace("&nbsp;", "", str_replace('\"', "'", json_encode($ticketmachine_post, JSON_UNESCAPED_SLASHES)))))
                    ));
                        
                    $log_resource = $resource;
                    if(!is_wp_error($resource)){
                        $log_resource['headers'] = (array)$log_resource["headers"];
                    }

                    $sent = array(
                        "headers" => (array)$headers,
                        "body" => (array)$ticketmachine_post
                    );

                    $log = array(
                        "url" => $ticketmachine_url,
                        "sent" => $sent,
                        "response" => $log_resource
                    );

                    $status_code = wp_remote_retrieve_response_code($resource);

                    if($status_code == 400 || $status_code == 500) {
                        ticketmachine_log(json_encode($log), "error");
                    }elseif($status_code == 200) {
                        ticketmachine_log(json_encode($log), "success");
                    }else{
                        ticketmachine_log(json_encode($log), "info");
                    }

                    if(strpos($ticketmachine_url, 'token') !== false) {
                        if($status_code == 400) {
                            // retry after 2 seconds if invalid token somehow
                            sleep(2);
                            ticketmachine_tmapi_refresh_token_check(true);
                        }
                    }

                }

            }else{

                if($ticketmachine_post) {
                    $headers = ticketmachine_array_push_assoc($headers, 'Accept', 'application/json');
                }

                $resource = wp_remote_get($ticketmachine_url, array(
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
    }

    /* API Requests */
    // Get event list
    function ticketmachine_tmapi_events($params=array(), $method="GET", $ticketmachine_post=FALSE,  $headers=array(), $ticketmachine_url_only=0){
        global $ticketmachine_api, $ticketmachine_globals;

        if(isset($ticketmachine_api)) {
            $params = (object)$params;
            if(empty($params->sort)){
                $params->sort = "ev_date";
            }
            
            if(!empty($params->q)) {
                $params->query = $params->q;
            }

            $ticketmachine_url = $ticketmachine_api->base_url . "ticketmachine.de/api/v2/events?";
            
            if($ticketmachine_globals->organizer && $ticketmachine_globals->organizer != "" ){
                $ticketmachine_url .= "organizer.og_abbreviation[eq]=" . $ticketmachine_globals->organizer;
            }elseif($params->organizer){
                $ticketmachine_url .= "organizer.og_abbreviation[eq]=" . $params->organizer;
            }
            
            if(empty($params->show_old)) {
                $ticketmachine_url .= "&endtime[gte]=" . $ticketmachine_globals->first_event_date;
            }
            $ticketmachine_url .= "&sort=". $params->sort;
            if(empty($params->per_page)) {
                $params->per_page = 30;
            }
            $ticketmachine_url .= "&per_page=" . (int)$params->per_page;
            if(!empty($params->pg)) {
                $ticketmachine_url .= "&offset=" . (int)$params->per_page*($params->pg-1);
            }
            
            if(!empty($params->query)) {
                $ticketmachine_url .= "&ev_name[contains]=" . htmlspecialchars(urlencode($params->query));
            }
            
            if(!empty($params->tag)) {
                $ticketmachine_url .= "&tags[eq]=" . htmlspecialchars(urlencode($params->tag));
            }
            
            if(isset($params->approved)) {
                $ticketmachine_url .= "&approved[eq]=" . (int)$params->approved;
            }

            ticketmachine_debug($ticketmachine_url);

            if(isset($ticketmachine_url_only) && $ticketmachine_url_only == 1) {
                return $ticketmachine_url;
            }else{
                $events = (object)ticketmachine_apiRequest($ticketmachine_url, $ticketmachine_post, $method, $headers);
                ticketmachine_debug($events->meta);
                return $events;
            }
        }
    }

    // Get event
    function ticketmachine_tmapi_event($params=array(), $method="GET", $ticketmachine_post=FALSE, $headers=array()){
        global $ticketmachine_api, $ticketmachine_globals;
        if(isset($ticketmachine_api)) {
                
            if($method == "POST"){
                $ticketmachine_post = $params;
            }
            $params = (object)$params;

            $ticketmachine_url = $ticketmachine_api->base_url. "ticketmachine.de/api/v2/events/";
            if(!empty($params && isset($params->id))){ 
                $ticketmachine_url .= (int)$params->id;
            }

            if(!empty($params->categories)) {
                $ticketmachine_url .= "?categories=true";
            }

            $event = (object)ticketmachine_apiRequest($ticketmachine_url, $ticketmachine_post, $method, $headers);

            return $event;
        }
    }

    // Copy event
    function ticketmachine_tmapi_event_copy($params){
        global $ticketmachine_api, $ticketmachine_globals;

        if(isset($ticketmachine_api)) {
            $ticketmachine_url = $ticketmachine_api->base_url . "ticketmachine.de/api/v2/events/" . absint($_GET['id']) . "/copy";

            $event = ticketmachine_apiRequest($ticketmachine_url, $params, "POST");
            return (object)$event;
        }
    }

    // Delete event
    function ticketmachine_tmapi_event_delete($params){
        global $ticketmachine_api, $ticketmachine_globals;

        $ticketmachine_url = $ticketmachine_api->base_url . "ticketmachine.de/api/v2/events/" . absint($_GET['id']) . "/delete";

        $event = ticketmachine_apiRequest($ticketmachine_url, $params, "POST");
        return (object)$event;
    }

    // Get connected organizer
    function ticketmachine_tmapi_organizers($params=array(), $method="GET", $ticketmachine_post=FALSE, $headers=array()){
        global $ticketmachine_api, $ticketmachine_globals;

        $params = (object)$params;

        $ticketmachine_url = $ticketmachine_api->base_url . "ticketmachine.de/api/v2/organizers/me";

        $organizer = ticketmachine_apiRequest($ticketmachine_url, $ticketmachine_post, $method, $headers);

        return $organizer;
    }

    //Check if access token expired
    function ticketmachine_tmapi_refresh_token_check($retry = false) {
        global $ticketmachine_globals, $ticketmachine_api, $wpdb, $wp_version, $ticketmachine_db_version;

        if(isset($ticketmachine_globals) && isset($ticketmachine_globals->activated) && $ticketmachine_globals->activated > 0) {
            if(time() > ($ticketmachine_globals->api_refresh_last + $ticketmachine_globals->api_refresh_interval)){
                $actual_config = (object)$wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_config LIMIT 0,1")[0];

                if(!empty($actual_config->api_refresh_token) && $actual_config->api_refresh_token == $ticketmachine_globals->api_refresh_token) {
                    sleep(1);

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
                            array('id' => $ticketmachine_globals->id)
                        );
                        $ticketmachine_globals->api_access_token = $token['access_token'];
                        $ticketmachine_globals->api_refresh_token = $token['access_token'];
                        $ticketmachine_globals->activated = 1;
                    }else{
                        // COULD NOT GET AN ACCESS TOKEN
                        $save_array = array(
                            "api_token_failed" => true,
                        );
            
                        $wpdb->update(
                            $wpdb->prefix . "ticketmachine_config",
                            $save_array,
                            array('id' => $ticketmachine_globals->id)
                        );

                        if($retry == true && $actual_config->api_token_failed) {
                             // INVALID REFRESH TOKEN
                             $save_array = array(
                                "api_access_token" => "",
                                "api_refresh_token" => "",
                                "api_refresh_last" => time()-1000,
                                "api_token_failed" => false,
                            );
    
                            $wpdb->update(
                                $wpdb->prefix . "ticketmachine_config",
                                $save_array,
                                array('id' => $ticketmachine_globals->id)
                            );

                            $ticketmachine_globals->activated = 0;
                            
                            sleep(1);
                            
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
                            //wp_mail( $multiple_recipients, $subj, $body, $headers );
                        }
                    }
                }else{
                    $ticketmachine_globals->api_access_token = $actual_config->api_access_token;
                    $ticketmachine_globals->api_refresh_token = $actual_config->api_refresh_token;
                }
            }
        }
    }

    // Get new access token
    function ticketmachine_tmapi_get_access_token($refresh_token, $status="update") {
        global $ticketmachine_api, $ticketmachine_globals, $wpdb;

        if(isset($ticketmachine_api)) {
            $actual_config = (object)$wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_config LIMIT 0,1")[0];

            if($status == "new"){
                $ticketmachine_api->auth->code = array(
                    'grant_type' => 'authorization_code',
                    'client_id' => $ticketmachine_api->client_id,
                    'client_secret' => $ticketmachine_api->client_secret,
                    'code' => $refresh_token,
                    'redirect_uri' => $ticketmachine_api->auth->proxy,
                    'scope' => "public organizer organizer/event"
                );

                $headers = array(
                    'Idempotency-Key' => hash("sha256", $refresh_token),
                );
            }
            if($status == "update"){
                $ticketmachine_api->auth->code = array(
                    'grant_type' => 'refresh_token',
                    'client_id' => $actual_config->api_client_id,
                    'client_secret' => $actual_config->api_client_secret,
                    'refresh_token' => $actual_config->api_refresh_token,
                    'redirect_uri' => $ticketmachine_api->auth->proxy,
                    'scope' => "public organizer organizer/event"
                );

                $headers = array(
                    'Idempotency-Key' => hash("sha256", $actual_config->api_refresh_token),
                );
            }

            $token = ticketmachine_apiRequest($ticketmachine_api->token, $ticketmachine_api->auth->code, "POST", $headers);

            if(isset($token)) {
                $ticketmachine_globals->api_access_token = $token['access_token'];
            }
            
            return $token;
        }
    }

    // Get all categories
    function ticketmachine_tmapi_categories($params=array(), $method="GET", $headers=array()){
        global $ticketmachine_api, $ticketmachine_globals;

        if(isset($ticketmachine_api)) {
            $params = (object)$params;
            if(!$params->sort){
                $params->sort = "name";
            }

            $ticketmachine_url = $ticketmachine_api->base_url . "ticketmachine.de/api/v2/events/tags";

            $categories = ticketmachine_apiRequest($ticketmachine_url, $params, $method);
            return (object)$categories;
        }
    }

    // Add to category
    function ticketmachine_tmapi_category_add($params=array(), $method="POST", $headers=array()){
        global $ticketmachine_api, $ticketmachine_globals;

        if(isset($ticketmachine_api)) {
            $params = (object)$params;
            $ticketmachine_url = $ticketmachine_api->scheme . "://cloud." . $ticketmachine_api->environment . "ticketmachine.de/api/v2/event/tags/types/category/add";

            $category = ticketmachine_apiRequest($ticketmachine_url, $params, $method);
            return (object)$category;
        }
    }

    // Remove from category
    function ticketmachine_tmapi_category_remove($params=array(), $method="POST", $headers=array()){
        global $ticketmachine_api, $ticketmachine_globals;

        if(isset($ticketmachine_api)) {
            $params = (object)$params;
            $ticketmachine_url = $ticketmachine_api->scheme . "://cloud." . $ticketmachine_api->environment . "ticketmachine.de/api/v2/event/tags/types/category/remove";
            $category = ticketmachine_apiRequest($ticketmachine_url, $params, $method);
            return (object)$category;
        }
    }
?>