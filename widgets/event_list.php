<?php

    function tm_widget_event_list ( $atts, $globals, $api ) {

		$events = tmapi_events($atts)->results;

        foreach($events as $event){
            $tm_output .= $event->id;
        }

        return $tm_output;
    }

?>