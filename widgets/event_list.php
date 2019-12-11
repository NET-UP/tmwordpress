<?php

    function tm_widget_event_list ( $atts, $globals, $api ) {

        $params = $atts;
        $events = tmapi_events($params)->result;
        $tm_output .= '<div class="tm_widget_event_list">';

        foreach($events as $event){
            $event = (object)$event;

            $tm_output .= '<ul class="list-unstyled">
                                <li class="media">
                                    <a href="#">
                                        <div class="mr-3 media-img"></div>
                                        <div class="media-body">
                                            <h5 class="mt-0 mb-1">' . $event->ev_name . '</h5>
                                        </div>
                                    </a>
                                </li>
                            </ul>';
        }

        $tm_output .= '</div>';

        return $tm_output;
    }

?>