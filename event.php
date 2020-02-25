<?php
	require_once('../../../wp-load.php');

	include "globals.php";

	$params = [ 
		"query" => sanitize_text_field($_GET['q']), 
		"sort" =>  sanitize_text_field($_GET['sort']), 
		"tag" =>  sanitize_text_field($_GET['tag']), 
		"approved" => 1 
	];
	$events = ticketmachine_tmapi_events($params);

	$calendar = array();
	$i = 0;
			
	if(empty($events->result)) {	
		header('HTTP/1.0 404 Not found');
	}else{

		foreach($events->result as $event) {
			$event = (object) $event;
			
			$params = [ "id" => $event->id ];
			
			if($event->state['sale_active'] > 0){
				$event->status_color = "#d4edda";
				$event->status_text_color = "#155724";
			}else{
				$event->status_color = "#f8d7da";
				$event->status_text_color = "#721c24";
			}

			//Override for free plugin
			$event->status_color = "#d4edda";
			$event->status_text_color = "#155724";
			
			$start = strtotime(get_date_from_gmt($event->ev_date)) * 1000;
			$end = strtotime(get_date_from_gmt($event->endtime)) * 1000;	

			if ($end < (strtotime("midnight", time())*1000)){
				$event->status_color = "#eeeeee";
				$event->status_text_color = "#999999";
			}elseif($i == 0) {
				$i = 1;
				$default_date = $start;
			}
			
			$calendar[] = array(
				'id' =>$event->id,
				'title' => $event->ev_name,
				'url' => "/" . $globals->event_slug . "/?id=" . $event->id,
				'start' => $start,
				'end' => $end,
				'class' => "event-success",
				'color' => $event->status_color,
				'textColor' => $event->status_text_color,
				'defaultDate' => $default_date
			);
		}
	}

	$calendarData = $calendar;
		
	echo json_encode($calendarData);

	exit;
?>