<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    function ticketmachine_widget_event_list ( $atts ) {
		global $globals, $api;

        $params = $atts;
        if(empty($params['approved'])) {
            $params = ticketmachine_array_push_assoc($params, "approved", 1);
        }
        $events = ticketmachine_ticketmachine_tmapi_events($params)->result;
        $ticketmachine_output = "";
        
			
        if(empty($events)) {	
            $ticketmachine_output .= "<div class='col-12 text-center mt-1'>";
                $ticketmachine_output .= ticketmachine_alert(__("No events could be found", "ticketmachine"), "error");
            $ticketmachine_output .= "</div>";
            
        }else{

            $ticketmachine_output .= '<div class="ticketmachine_widget_event_list">';
                $ticketmachine_output .= '<ul class="list-unstyled">';

                foreach($events as $event){
                    $event = (object)$event;

                    $ticketmachine_output .= '<li class="media">';

                    if(isset($atts['show_image']) && $atts['show_image'] > 0){
                        $ticketmachine_output .= '<a class="mr-3 media-img" href="/' . $globals->event_slug . '?id=' . $event->id . '" style="background-image:url('. $event->event_img_url .')"></a>';
                    }
                                        
                        $ticketmachine_output .= '<div class="media-body">';
                        $ticketmachine_output .= '<h5 class="mt-0 mb-1"><a href="/' . $globals->event_slug . '?id=' . $event->id . '">' . $event->ev_name . '</a></h5>';
                        
                        if(isset($atts['show_date']) && $atts['show_date'] > 0){
                            $ticketmachine_output .= '
                            <div class="card-meta-tag"><i class="far fa-calendar-alt tm-icon" aria-hidden="true"></i> &nbsp;'. ticketmachine_i18n_date("d.m.Y", $event->ev_date) .'</div> 
                            <div class="card-meta-tag"><i class="far fa-clock tm-icon" aria-hidden="true"></i> &nbsp;'. ticketmachine_i18n_date("H:i", $event->ev_date) .'</div>';
                        }

                        if(isset($atts['show_description']) && $atts['show_description'] > 0){
                            if(!$atts['description_length']){
                                $atts['description_length'] = 15;
                            }
                            $ticketmachine_output .= '<div>' . wp_trim_words(wp_strip_all_tags($event->ev_description), $atts['description_length'], "...") . '</div>';
                        }

                    $ticketmachine_output .= '</div>
                                </li>';
                }

                if(isset($atts['show_more']) && $atts['show_more'] > 0){
                    $ticketmachine_output .= '<li class="media"><a href="/' . $globals->events_slug . '">' . __("Show all events", "ticketmachine") . '</a></li>';
                }

                $ticketmachine_output .= '</ul>';

            $ticketmachine_output .= '</div>';

        }

        return $ticketmachine_output;
    }

?>