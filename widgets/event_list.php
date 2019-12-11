<?php

    function tm_widget_event_list ( $atts, $globals, $api ) {

        $params = $atts;
        $events = tmapi_events($params)->result;
        $tm_output .= '<div class="tm_widget_event_list">';
            $tm_output .= '<ul class="list-unstyled">';

            foreach($events as $event){
                $event = (object)$event;

                $tm_output .= '<li class="media">
                                        <img class="mr-3" style="background-image:url('. $event->event_img_url .')"/>
                                        <div class="media-body">
                                            <h5 class="mt-0 mb-1">' . $event->ev_name . '</h5>
                                        </div>
                                </li>';
            }

            $tm_output .= '</ul>';
        $tm_output .= '</div>';

        return $tm_output;
    }

?>