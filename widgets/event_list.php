<?php

    function tm_widget_event_list ( $atts ) {
		global $globals, $api;

        $params = $atts;
        if(empty($params['approved'])) {
            $params = array_push_assoc($params, "approved", 1);
        }
        $events = tmapi_events($params)->result;
        $tm_output = "";
        
			
        if(empty($events)) {	
            $tm_output .= "<div class='col-12 text-center mt-1'>";
                $tm_output .= tm_alert(__("No events could be found", "ticketmachine"), "error");
            $tm_output .= "</div>";
            
        }else{

            $tm_output .= '<div class="tm_widget_event_list">';
                $tm_output .= '<ul class="list-unstyled">';

                foreach($events as $event){
                    $event = (object)$event;

                    $tm_output .= '<li class="media">';

                    if(isset($atts['show_image']) && $atts['show_image'] > 0){
                        $tm_output .= '<a class="mr-3 media-img" href="/' . $globals->event_slug . '?id=' . $event->id . '" style="background-image:url('. $event->event_img_url .')"></a>';
                    }
                                        
                        $tm_output .= '<div class="media-body">';
                        $tm_output .= '<h5 class="mt-0 mb-1"><a href="/' . $globals->event_slug . '?id=' . $event->id . '">' . $event->ev_name . '</a></h5>';
                        
                        if(isset($atts['show_date']) && $atts['show_date'] > 0){
                            $tm_output .= '
                            <div class="card-meta-tag"><i class="far fa-calendar-alt tm-icon" aria-hidden="true"></i> &nbsp;'. date_i18n( "d.m.Y", strtotime(get_date_from_gmt($event->ev_date)) ) .'</div> 
                            <div class="card-meta-tag"><i class="far fa-clock tm-icon" aria-hidden="true"></i> &nbsp;'. date_i18n( "H:i", strtotime(get_date_from_gmt($event->ev_date)) ) .'</div>';
                        }

                        if(isset($atts['show_description']) && $atts['show_description'] > 0){
                            if(!$atts['description_length']){
                                $atts['description_length'] = 15;
                            }
                            $tm_output .= '<div>' . wp_trim_words(wp_strip_all_tags($event->ev_description), $atts['description_length'], "...") . '</div>';
                        }

                    $tm_output .= '</div>
                                </li>';
                }

                if(isset($atts['show_more']) && $atts['show_more'] > 0){
                    $tm_output .= '<li class="media"><a href="/' . $globals->events_slug . '">' . __("Show all events", "ticketmachine") . '</a></li>';
                }

                $tm_output .= '</ul>';

            $tm_output .= '</div>';

        }

        return $tm_output;
    }

?>