<?php

    function tm_widget_event_list ( $atts, $globals, $api ) {

        $events = tmapi_events($atts)->result;
        print_r($events);
        $tm_output .= "<div class='tm_widget_event_list'>";

        foreach($events as $event){
            $tm_output .= $event->id;
        }

        $tm_output .= "</div>";

        return $tm_output;
    }

?>