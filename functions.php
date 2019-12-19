<?php

	function array_push_assoc($array, $key, $value){
		$array[$key] = $value;
		return $array;
	}

	function apiRequest($url, $post=FALSE, $method="GET", $headers=array()) {
	  $ch = curl_init($url);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	  $headers = [];

	  if($method == "POST") {
		if($post) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_POST, 1);
			$headers[] = 'Content-Type: application/json';
		}
	  }else{
		if($post) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
			$headers[] = 'Accept: application/json';
		}
	  }

	  $headers[] = 'User-Agent: https://www.ticketmachine.de/';
	 
	  if(isset($_SESSION['access_token']))
		$headers[] = 'Authorization: Bearer '.$_SESSION['access_token'];
	 
	  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	 
	  $response = curl_exec($ch);
	  return json_decode($response, true);
	  
	}

	/* API Requests */
	/* Get event list */
	function tmapi_events($params=array(), $method="GET", $post=FALSE,  $headers=array()){
		global $api, $globals;

		$params = (object)$params;
		if(!isset($params->sort)){
			$params->sort = "ev_date";
		}

		$url = "http://apiv2." . $api->environment . "ticketmachine.de/api/v2/events?";
		
		if($globals->organizer && $globals->organizer != "" ){
			$url .= "organizer.og_abbreviation[eq]=" . $globals->organizer;
		}elseif($params->organizer){
			$url .= "organizer.og_abbreviation[eq]=" . $params->organizer;
		}
		
		if($params->show_old != 1) {
			$url .= "&endtime[gte]=" . $globals->first_event_date;
		}
		$url .= "&sort=". $params->sort;
		if($params->per_page > 0) {
			$url .= "&per_page=" . (int)$params->per_page;
		}
		
		if($params->query) {
			$url .= "&ev_name[contains]=" . htmlspecialchars(urlencode($params->query));
		}
		
		if($params->tag) {
			$url .= "&tags[eq]=" . htmlspecialchars(urlencode($params->tag));
		}
		
		if($params->shown) {
			$url .= "&shown[eq]=" . htmlspecialchars(urlencode($params->tag));
		}

		$events = apiRequest($url, $post, $method, $headers);
		return (object)$events;
	}

	/* Get event */
	function tmapi_event($params=array(), $method="GET", $post=FALSE, $headers=array()){
		global $api, $globals;
		if($method == "POST"){
			$post = $params;
		}else{
			$params = (object)$params;
		}

		$url = "http://apiv2." . $api->environment . "ticketmachine.de/api/v2/events/" . $params->id;
		if($params->categories > 0) {
			$url .= "?categories=true";
		}

		$event = apiRequest($url, $post, $method, $headers);
		return (object)$event;
	}

	/* Copy event */
	function tmapi_event_copy($params){
		global $api, $globals;

		$url = "http://apiv2." . $api->environment . "ticketmachine.de/api/v2/events/" . $_GET['id'] . "/copy";

		$event = apiRequest($url, $params, "POST");
		return (object)$event;
	}

	/* Get all categories */
	function tmapi_categories($params=array(), $method="GET", $headers=array()){
		global $api, $globals;

		$params = (object)$params;
		if(!$params->sort){
			$params->sort = "name";
		}

		$url = "http://apiv2." . $api->environment . "ticketmachine.de/api/v2/events/tags";

		$categories = apiRequest($url, $params, $method);
		return (object)$categories;
	}

	/* Add to category */
	function tmapi_category_add($params=array(), $method="POST", $headers=array()){
		global $api, $globals;

		$params = (object)$params;
		$url = "http://apiv2." . $api->environment . "ticketmachine.de/api/v2/event/tags/types/category/add";

		$category = apiRequest($url, $params, $method);
		return (object)$category;
	}

	/* Remove from category */
	function tmapi_category_remove($params=array(), $method="POST", $headers=array()){
		global $api, $globals;

		$params = (object)$params;
		$url = "http://apiv2." . $api->environment . "ticketmachine.de/api/v2/event/tags/types/category/remove";
		$category = apiRequest($url, $params, $method);
		return (object)$category;
	}

	switch ($globals->lang) {
		case 'en':
			setlocale(LC_TIME, 'en_US.UTF-8');
			break;
		case 'de':
			setlocale(LC_TIME, 'de_DE.UTF-8');
			break;
	}
	
	// Exchange the auth code for an access token
	$token = apiRequest($api->token, array(
	'grant_type' => 'client_credentials',
	'client_id' => $api->client_id,
	'client_secret' => $api->client_secret,
	'scope' => "system"
	));
	$_SESSION['access_token'] = $token['access_token'];
      
?>