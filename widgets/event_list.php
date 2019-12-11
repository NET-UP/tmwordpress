<?php

    function tm_widget_event_list ( $atts, $globals, $api ) {

        $params = array();

        if($atts['per_page']) {
            $params = array_push_assoc($params, 'per_page', (int)$atts['per_page']);
        }

        switch ($atts['per_page']) {
            case 'event_list':
                include "widgets/event_list.php";
                $tm_output .= tm_widget_event_list( $atts, $globals, $api );
                break;
        }

        $events = tmapi_events($params)->result;
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