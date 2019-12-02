<?php
	require_once('../../../wp-load.php');

	include "globals.php";

	$tm_json = apiRequest($api->get_event_calendar);

	$calendar = array();
	$i = 0;

	foreach($tm_json['result'] as $tm_event_array) {
		$tm_event = (object) $tm_event_array;
		
		$tm_json_getEventStatus = apiRequest($api->get_event_status . $tm_event->id);
		$tm_json_contingent = (object) $tm_json_getEventStatus;
		
		if($tm_json_contingent->free > 0){
			$tm_event->status_color = "#d4edda";
			$tm_event->status_text_color = "#155724";
		}else{
			$tm_event->status_color = "#f8d7da";
			$tm_event->status_text_color = "#721c24";
		}
		
		$start = strtotime($tm_event->ev_date) * 1000;
		$end = strtotime($tm_event->endtime) * 1000;	

		if ($end < (strtotime("midnight", time())*1000)){
			$tm_event->status_color = "#eeeeee";
			$tm_event->status_text_color = "#999999";
		}elseif($i == 0) {
			$i = 1;
			$default_date = $start;
		}
		
		$calendar[] = array(
			'id' =>$tm_event->id,
			'title' => $tm_event->ev_name,
			'url' => "/event/?id=" . $tm_event->id,
			'start' => $start,
			'end' => $end,
			'class' => "event-success",
			'color' => $tm_event->status_color,
			'textColor' => $tm_event->status_text_color,
			'defaultDate' => $default_date
		);
	}

	$calendarData = $calendar;
		
	echo json_encode($calendarData);

	exit;
?>