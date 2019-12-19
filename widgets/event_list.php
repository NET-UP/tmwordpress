<?php

    function tm_widget_event_list ( $atts ) {
		global $globals, $api;

        $params = $atts;
        $events = tmapi_events($params)->result;
        $tm_output .= '<div class="tm_widget_event_list">';
            $tm_output .= '<ul class="list-unstyled">';

            foreach($events as $event){
                $event = (object)$event;

                $tm_output .= '<li class="media">';

                if($atts['show_image'] > 0){
                    $tm_output .= '<div class="mr-3 media-img" style="background-image:url('. $event->event_img_url .')"></div>';
                }
                                    
                    $tm_output .= '<div class="media-body">';
                    $tm_output .= '<h5 class="mt-0 mb-1"><a href="/event?id=' . $event->id . '">' . $event->ev_name . '</a></h5>';

                    if($atts['show_description'] > 0){
                        if(!$atts['description_length']){
                            $atts['description_length'] = 15;
                        }
                        $tm_output .= '<div>' . wp_trim_words(wp_strip_all_tags($event->ev_description), $atts['description_length'], "...") . '</div>';
                    }

                $tm_output .= '</div>
                            </li>';
            }

            if($atts['show_more'] > 0){
                $tm_output .= '<li class="media"><a href="/events">' . __("Alle Events anzeigen", "ticketmachine") . '</a></li>';
            }

            $tm_output .= '</ul>';

        $tm_output .= '</div>';

        return $tm_output;
    }

?>