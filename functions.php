<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	function ticketmachine_array_push_assoc($array, $key, $value){
		$array[$key] = $value;
		return $array;
	}

	function ticketmachine_apiRequest($url, $post=FALSE, $method="GET", $headers=array()) {

	  $headers = array();
	  $headers = ticketmachine_array_push_assoc($headers, 'User-Agent', 'https://www.ticketmachine.de/');

	  if(isset($_SESSION['access_token']))
		  $headers = ticketmachine_array_push_assoc($headers, 'Authorization', 'Bearer ' . $_SESSION['access_token']);

	  if($method == "POST") {

		if($post) {
			$headers = ticketmachine_array_push_assoc($headers, 'Content-Type', 'application/json');

			$resource = wp_remote_post($url, array(
				'method'  => 'POST',
				'timeout' => 45,
				'headers' => $headers,
				'body' => $post
			));
		}

	  }else{

		if($post) {
			$headers = ticketmachine_array_push_assoc($headers, 'Accept', 'application/json');
		}

		$resource = wp_remote_get($url, array(
			'method'  => 'GET',
			'timeout' => 45,
			'headers' => $headers
		));

	  }
	  $response = $resource['body'];
	  echo "<pre>"; 
	  print_r($url);
	  print_r($headers);
	  print_r($post);
	  print_r($response);
	  echo "</pre>";
	  
	  return json_decode($response, true);
	  
	}

	/* API Requests */
	/* Get event list */
	function ticketmachine_ticketmachine_tmapi_events($params=array(), $method="GET", $post=FALSE,  $headers=array()){
		global $api, $globals;

		$params = (object)$params;
		if(!isset($params->sort)){
			$params->sort = "ev_date";
		}

		$url = $api->scheme . "://cloud." . $api->environment . "ticketmachine.de/api/v2/events?";
		
		if($globals->organizer && $globals->organizer != "" ){
			$url .= "organizer.og_abbreviation[eq]=" . $globals->organizer;
		}elseif($params->organizer){
			$url .= "organizer.og_abbreviation[eq]=" . $params->organizer;
		}
		
		if(empty($params->show_old)) {
			$url .= "&endtime[gte]=" . $globals->first_event_date;
		}
		$url .= "&sort=". $params->sort;
		if(!empty($params->per_page)) {
			$url .= "&per_page=" . (int)$params->per_page;
		}
		
		if(isset($params->query)) {
			$url .= "&ev_name[contains]=" . htmlspecialchars(urlencode($params->query));
		}
		
		if(isset($params->tag)) {
			$url .= "&tags[eq]=" . htmlspecialchars(urlencode($params->tag));
		}
		
		if(isset($params->approved)) {
			$url .= "&approved[eq]=" . (int)$params->approved;
		}

		$events = (object)ticketmachine_apiRequest($url, $post, $method, $headers);

		return $events;
	}

	/* Get event */
	function ticketmachine_tmapi_event($params=array(), $method="GET", $post=FALSE, $headers=array()){
		global $api, $globals;
		if($method == "POST"){
			$post = $params;
		}else{
			$params = (object)$params;
		}

		$url = $api->scheme . "://cloud." . $api->environment . "ticketmachine.de/api/v2/events/" . $params->id;
		if(!empty($params->categories)) {
			$url .= "?categories=true";
		}

		$event = (object)ticketmachine_apiRequest($url, $post, $method, $headers);

		return $event;
	}

	/* Copy event */
	function ticketmachine_tmapi_event_copy($params){
		global $api, $globals;

		$url = $api->scheme . "://cloud." . $api->environment . "ticketmachine.de/api/v2/events/" . absint($_GET['id']) . "/copy";

		$event = ticketmachine_apiRequest($url, $params, "POST");
		return (object)$event;
	}

	/* Get connected organizer */
	function ticketmachine_tmapi_organizers($params=array(), $method="GET", $post=FALSE, $headers=array()){
		global $api, $globals;

		$params = (object)$params;

		$url = $api->scheme . "://cloud." . $api->environment . "ticketmachine.de/api/v2/organizers/me";

		$organizer = ticketmachine_apiRequest($url, $post, $method, $headers);

		return $organizer;
	}

	//Check if access token expired
	function ticketmachine_tmapi_refresh_token_check() {
		global $globals, $api, $wpdb;

		if(time() > $globals->api_refresh_last + $globals->api_refresh_interval){
			$token = ticketmachine_tmapi_get_access_token($globals->api_refresh_token, "update");
		
			$save_array = array(
				"api_access_token" => $token['access_token'],
				"api_refresh_token" => $token['refresh_token'],
				"api_refresh_last" => time(),
				"api_refresh_interval" => $token['expires_in']/2
			);

			$wpdb->update(
				$wpdb->prefix . "ticketmachine_config",
				$save_array,
				array('id' => $globals->id)
			);
			$_SESSION['access_token'] = $token['access_token'];
		}
	}

	// Get new access token
	function ticketmachine_tmapi_get_access_token($refresh_token, $status="update") {
		global $api, $globals;

		if($status == "new"){
			$api->auth->code = array(
				'grant_type' => 'authorization_code',
				'client_id' => $api->client_id,
				'client_secret' => $api->client_secret,
				'code' => $refresh_token,
				'redirect_uri' => $api->auth->proxy,
				'scope' => "public organizer organizer/event"
			);
		}
		if($status == "update"){
			$api->auth->code = array(
				'grant_type' => 'refresh_token',
				'client_id' => $api->client_id,
				'client_secret' => $api->client_secret,
				'refresh_token' => $refresh_token,
				'redirect_uri' => $api->auth->proxy,
				'scope' => "public organizer organizer/event"
			);
		}

		$token = ticketmachine_apiRequest($api->token, $api->auth->code, "GET");
        $_SESSION['access_token'] = $token['access_token'];

		return $token;
	}

	/* Get all categories */
	function ticketmachine_tmapi_categories($params=array(), $method="GET", $headers=array()){
		global $api, $globals;

		$params = (object)$params;
		if(!$params->sort){
			$params->sort = "name";
		}

		$url = $api->scheme . "://cloud." . $api->environment . "ticketmachine.de/api/v2/events/tags";

		$categories = ticketmachine_apiRequest($url, $params, $method);
		return (object)$categories;
	}

	/* Add to category */
	function ticketmachine_tmapi_category_add($params=array(), $method="POST", $headers=array()){
		global $api, $globals;

		$params = (object)$params;
		$url = $api->scheme . "://cloud." . $api->environment . "ticketmachine.de/api/v2/event/tags/types/category/add";

		$category = ticketmachine_apiRequest($url, $params, $method);
		return (object)$category;
	}

	/* Remove from category */
	function ticketmachine_tmapi_category_remove($params=array(), $method="POST", $headers=array()){
		global $api, $globals;

		$params = (object)$params;
		$url = $api->scheme . "://cloud." . $api->environment . "ticketmachine.de/api/v2/event/tags/types/category/remove";
		$category = ticketmachine_apiRequest($url, $params, $method);
		return (object)$category;
	}

	function ticketmachine_i18n_date($format, $datetime){
		$formatted_date = date_i18n($format, strtotime(get_date_from_gmt($datetime)) );
		return $formatted_date;
	}

	function ticketmachine_i18n_reverse_date($datetime){
		$formatted_date = gmdate(DATE_ISO8601, strtotime(date_i18n(DATE_ISO8601, strtotime($datetime))));
		return $formatted_date;
	}


	switch ($globals->lang) {
		case 'en':
			setlocale(LC_TIME, 'en_US.UTF-8');
			break;
		case 'de':
			setlocale(LC_TIME, 'de_DE.UTF-8');
			break;
	}

	if(isset($globals->activated) && $globals->activated > 0){
		ticketmachine_tmapi_refresh_token_check();
	}
      
?>