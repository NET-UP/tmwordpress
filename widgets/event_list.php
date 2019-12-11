<?php

    function tm_widget_event_list ( $atts, $globals, $api ) {

        $events = tmapi_events($atts)->result;
        $tm_output .= '<div class="tm_widget_event_list">';

        foreach($events as $event){
            $event = (object)$event;

            $tm_output .= '<ul class="list-group list-group-flush">';
                $tm_output .= '<li class="list-group-item"><a href="#">' . $event->ev_name . '</a></li>';
            $tm_output .= '</ul>';
        }

        $tm_output .= '</div>';

        return $tm_output;
    }

?>