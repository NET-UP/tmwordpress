<?php
	global $wpdb, $globals, $api;
	$tm_config = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_config LIMIT 0,1");
	$tm_config = $tm_config[0];

	$globals = new stdClass();
	$api = new stdClass();
	$api->auth = new stdClass();
	
	$globals->map_query_url = "https://www.google.de/maps?q=";
	$globals->lang = "de";
	$globals->current_url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
	
	/* Backend Settings */
	$api->client_id = $tm_config->api_client_id;
	$api->client_secret = $tm_config->api_client_secret;

	$globals->show_list = $tm_config->show_list;
	$globals->show_calendar = $tm_config->show_calendar;
	$globals->environment = $tm_config->api_environment;
	$globals->organizer_id = $tm_config->organizer_id;
	$globals->search_query = htmlentities($_GET['q']);
	$globals->organizer = $tm_config->organizer;
	$globals->group_by = "Y";
	$globals->format_date = "Y";
	$globals->first_event_date = date('Y-m-d');
	$globals->first_event_date_calendar = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) );

	$globals->webshop_url = "https://" . $globals->environment . ".ticketmachine.de/" . $globals->lang . "/customer/" . $globals->organizer;
	
	if($globals->environment == "staging"){
		$api->environment = $globals->environment . ".";
	}else{
		$api->environment = "";
	}
	
	/* Get event list backend */
	$api->get_event_list_backend = "http://apiv2." . $api->environment . "ticketmachine.de/api/v2/events?";
	
	if($globals->organizer && $globals->organizer != "" ){
		$api->get_event_list_backend .= "organizer.og_abbreviation[eq]=" . $globals->organizer;
	}elseif($_GET['organizer']){
		$api->get_event_list_backend .= "organizer.og_abbreviation[eq]=" . $_GET['organizer'];
	}
	
	$api->get_event_list_backend .= "&sort=ev_date&per_page=100";

	/* Copy single event */
	$api->copy_single_event = "http://apiv2." . $api->environment . "ticketmachine.de/api/v2/events/" . $_GET['id'] . "/copy";
	
	/* Delete single event */
	$api->delete_single_event = "http://apiv2." . $api->environment . "ticketmachine.de/api/v2/events/" . $_GET['id'] . "/delete";
	
	#TODO: Refactor api request
	$api->token = "http://apiv2." . $api->environment . "ticketmachine.de/oauth/token";
	$api->auth->url = "http://apiv2." . $api->environment . "ticketmachine.de/oauth/token";
	$api->auth->key = $api->client_id.":".$api->client_secret;
	$api->auth->encoded_key = base64_encode($api->auth->key);
	$api->auth->headers = array();
	$api->auth->headers = [
		'Authorization: Basic' . $api->auth->encoded_key,
		'Content-Type: application/x-www-form-urlencoded'
	];
	$api->auth->data = array(
		'response_type' => 'code',
		'client_id' => $api->client_id,
		'redirect_uri' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
		'state' => $_SESSION['state'],
		'scope' => 'system',
	);
	
	include('functions.php');
?>