<?php
	if(!session_id())
		session_start(); 
		
	global $wpdb, $globals, $api;
	$tm_config = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_config LIMIT 0,1");
	$tm_config = $tm_config[0];

	$globals = (object)$tm_config;
	$api = new stdClass();
	$api->auth = new stdClass();

	//get page slugs
	$globals->events_slug = get_page_uri($globals->events_slug_id); 
	$globals->event_slug = get_page_uri($globals->event_slug_id); 

	switch ($globals->event_grouping) {
		case 'Month':
			$globals->group_by = "m Y";
			$globals->format_date = "%b %Y";
			break;
		case 'Year':
			$globals->group_by = "Y";
			$globals->format_date = "%Y";
			break;
		default:
			$globals->group_by = "Y";
			$globals->format_date = "%Y";
			break;
	}
	
	$globals->map_query_url = "https://www.google.de/maps?q=";
	$globals->lang = "de";
	$globals->current_url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
	
	/* Backend Settings */
	$api->client_id = $tm_config->api_client_id;
	$api->client_secret = $tm_config->api_client_secret;
	$globals->environment = $tm_config->api_environment;
	
	$globals->search_query = "";
	if(isset($_GET['q'])){
		$globals->search_query = htmlentities($_GET['q']);
	}
	$globals->tag = "";
	if(isset($_GET['tag'])){
		$globals->tag = htmlentities($_GET['tag']);
	}
	$globals->organizer = $tm_config->organizer;

	

	$globals->first_event_date = date('Y-m-d');
	$globals->first_event_date_calendar = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) );

	$globals->webshop_url = "https://" . $globals->environment . ".ticketmachine.de/" . $globals->lang . "/customer/" . $globals->organizer;
	
	if($globals->environment == "staging"){
		$api->environment = $globals->environment . ".";
	}else{
		$api->environment = "";
	}
	
	#TODO: Refactor api request
	if(!isset($_SESSION['state'])){
		$_SESSION['state'] = "";
	}
	$api->token = "http://apiv2." . $api->environment . "ticketmachine.de/oauth/token";
	$api->auth->url = "http://apiv2." . $api->environment . "ticketmachine.de/oauth/token";

	$api->auth->key = $api->client_id.":".$api->client_secret;
	$api->auth->encoded_key = base64_encode($api->auth->key);
	$api->auth->headers = array();
	$api->auth->headers = [
		'Authorization: Basic' . $api->auth->encoded_key,
		'Content-Type: application/x-www-form-urlencoded'
	];

	$api->auth->start_uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$api->auth->redirect_uri = "https://www.ticketmachine.de/oauth/start.php";
	
	$api->auth->data = array(
		'response_type' => 'code',
		'client_id' => $api->client_id,
		'redirect_uri' => $api->auth->redirect_uri . "?start_uri=" . $api->auth->start_uri,
		'state' => $_SESSION['state'],
		'scope' => 'public organizer organizer/event'
	);
	
	include('functions.php');
?>