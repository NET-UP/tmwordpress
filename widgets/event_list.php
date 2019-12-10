<?php

    function tm_widget_event_list ( $atts, $globals, $api ) {
		$tm_json = apiRequest($api->get_event_list);
		$events = $tm_json['result'];

        $tm_output = json_decode($events);

        return $tm_output;
    }

?>