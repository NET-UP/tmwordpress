<?php

    function tm_widget_event_list ( $atts, $globals, $api ) {
		$events = tmapi_all_events();

        foreach($events as $event){
            $tm_output = $event->id;
        }

        return $tm_output;
    }

?>