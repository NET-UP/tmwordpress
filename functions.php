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

		$url = $api->scheme . "://apiv2." . $api->environment . "ticketmachine.de/api/v2/events?";
		
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

		$events = (object)apiRequest($url, $post, $method, $headers);
		return $events;
	}

	/* Get event */
	function tmapi_event($params=array(), $method="GET", $post=FALSE, $headers=array()){
		global $api, $globals;
		if($method == "POST"){
			$post = $params;
		}else{
			$params = (object)$params;
		}

		$url = $api->scheme . "://apiv2." . $api->environment . "ticketmachine.de/api/v2/events/" . $params->id;
		if(!empty($params->categories)) {
			$url .= "?categories=true";
		}

		$event = (object)apiRequest($url, $post, $method, $headers);

		return $event;
	}

	/* Copy event */
	function tmapi_event_copy($params){
		global $api, $globals;

		$url = $api->scheme . "://apiv2." . $api->environment . "ticketmachine.de/api/v2/events/" . $_GET['id'] . "/copy";

		$event = apiRequest($url, $params, "POST");
		return (object)$event;
	}

	/* Get connected organizer */
	function tmapi_organizers($params=array(), $method="GET", $post=FALSE, $headers=array()){
		global $api, $globals;

		$params = (object)$params;

		$url = $api->scheme . "://apiv2." . $api->environment . "ticketmachine.de/api/v2/organizers/me";

		$organizer = apiRequest($url, $post, $method, $headers);

		return $organizer;
	}

	/* Get all categories */
	function tmapi_categories($params=array(), $method="GET", $headers=array()){
		global $api, $globals;

		$params = (object)$params;
		if(!$params->sort){
			$params->sort = "name";
		}

		$url = $api->scheme . "://apiv2." . $api->environment . "ticketmachine.de/api/v2/events/tags";

		$categories = apiRequest($url, $params, $method);
		return (object)$categories;
	}

	/* Add to category */
	function tmapi_category_add($params=array(), $method="POST", $headers=array()){
		global $api, $globals;

		$params = (object)$params;
		$url = $api->scheme . "://apiv2." . $api->environment . "ticketmachine.de/api/v2/event/tags/types/category/add";

		$category = apiRequest($url, $params, $method);
		return (object)$category;
	}

	/* Remove from category */
	function tmapi_category_remove($params=array(), $method="POST", $headers=array()){
		global $api, $globals;

		$params = (object)$params;
		$url = $api->scheme . "://apiv2." . $api->environment . "ticketmachine.de/api/v2/event/tags/types/category/remove";
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
      
?>