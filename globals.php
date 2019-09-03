<?php
	global $wpdb;
	$tm_config = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_config LIMIT 0,1");
	$tm_config = $tm_config[0];

	$globals = new stdClass();
	$api = new stdClass();
	
	$globals->map_query_url = "https://www.google.de/maps?q=";
	$globals->lang = "de";
	$globals->search_query = htmlspecialchars($_GET['q']);
	$globals->current_url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
	
	/* Backend Settings */
	$api->client_id = $tm_config->api_client_id;
	$api->client_secret = $tm_config->api_client_secret;

	$globals->show_list = $tm_config->show_list;
	$globals->show_calendar = $tm_config->show_calendar;
	$globals->environment = $tm_config->api_environment;
	$globals->organizer = $tm_config->organizer;
	$globals->group_by = "Y";
	$globals->format_date = "Y";
	$globals->first_event_date = date('Y-m-d');

	$globals->webshop_url = "https://" . $globals->environment . ".ticketmachine.de/" . $globals->lang . "/customer/" . $globals->organizer;
	
	if($globals->environment == "staging"){
		$api->environment = $globals->environment . ".";
	}else{
		$api->environment = "";
	}
	
	$api->get_event_list = "http://apiv2." . $api->environment . "ticketmachine.de/api/v2/events?";
	
	if($globals->organizer && $globals->organizer != "" ){
		$api->get_event_list .= "organizer.og_abbreviation[eq]=" . $globals->organizer;
	}elseif($_GET['organizer']){
		$api->get_event_list .= "organizer.og_abbreviation[eq]=" . $_GET['organizer'];
	}
	
	$api->get_event_list .= "&endtime[gte]=" . $globals->first_event_date . "&sort=ev_date";
	
	if($_GET['q']) {
		$api->get_event_list .= "&ev_name[contains]=" . $globals->search_query;
	}
	
	
	$api->get_event_status = "http://apiv2." . $api->environment . "ticketmachine.de/api/v2/event_infos/event_contingent_data?event_id=";
	$api->token = "http://apiv2." . $api->environment . "ticketmachine.de/oauth/token";
	
	include('functions.php');
?>