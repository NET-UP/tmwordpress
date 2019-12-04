<?php
	#function apiRequest($url, $post=FALSE, $headers=array()) {
	#  $ch = curl_init($url);
	#  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	#  $headers = [];
	# 
	#  if($post)
	#	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
#
	#  if(strlen($post) > 0 || is_object($post)) {
	#	curl_setopt($ch, CURLOPT_POST, 1);
	#	$headers[] = 'Content-Type: application/json';
	#  }else{
	#	$headers[] = 'Accept: application/json';
	#  }
#
	#  $headers[] = 'User-Agent: https://www.ticketmachine.de/';
	# 
	#  if(isset($_SESSION['access_token']))
	#	$headers[] = 'Authorization: Bearer '.$_SESSION['access_token'];
	# 
	#  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	# 
	#  $response = curl_exec($ch);
	#  return json_decode($response, true);
	#  
	#}

	function apiRequest($url, $data=FALSE, $method){
		$curl = curl_init();
		$headers = [];
	
		switch ($method){
		case "POST":
			curl_setopt($curl, CURLOPT_POST, 1);
			if ($data)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			break;
		case "PUT":
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
			if ($data)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
			break;
		default:
			if ($data)
				$url = sprintf("%s?%s", $url, http_build_query($data));
		}

		if(isset($_SESSION['access_token']))
			$headers[] = 'Authorization: Bearer '.$_SESSION['access_token'];

		$headers[] = 'Content-Type: application/json';
		$headers[] = 'User-Agent: https://www.ticketmachine.de/';
	
		// OPTIONS:
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	
		// EXECUTE:
		$result = curl_exec($curl);
		if(!$result){die("Connection Failure");}
		return json_decode($result);
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