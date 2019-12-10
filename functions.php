<?php

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
	function tmapi_events($params=array(), $post=FALSE, $method="GET", $headers=array()){

		global $api, $globals;

		$params = (object)$params;
		if(!$params->sort){
			$params->sort = "ev_date";
		}

		$get_event_list = "http://apiv2." . $api->environment . "ticketmachine.de/api/v2/events?";
		
		if($globals->organizer && $globals->organizer != "" ){
			$get_event_list .= "organizer.og_abbreviation[eq]=" . $globals->organizer;
		}elseif($params->organizer){
			$get_event_list .= "organizer.og_abbreviation[eq]=" . $params->organizer;
		}
		
		$get_event_list .= "&endtime[gte]=" . $globals->first_event_date;
		$get_event_list .= "&sort=". $params->sort;
		
		if($params->query) {
			$get_event_list .= "&ev_name[contains]=" . $params->query;
		}

		$events = apiRequest($get_event_list, $post, $method, $headers);
		return (object)$events['result'];
	}
	
	switch ($globals->lang) {
		case 'en':
			setlocale(LC_TIME, 'en_US.UTF-8');
			break;
		case 'de':
			setlocale(LC_TIME, 'de_DE.UTF-8');
			break;
	}
	 
	// Start a session so we have a place to
	// store things between redirects
	session_start();
	
	  // Exchange the auth code for an access token
	  $token = apiRequest($api->token, array(
		'grant_type' => 'client_credentials',
		'client_id' => $api->client_id,
		'client_secret' => $api->client_secret,
		'scope' => "system"
	  ));
      $_SESSION['access_token'] = $token['access_token'];
      
?>