<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    function ticketmachine_widget_event_list ( $atts, $isWidget ) {
		global $tm_globals, $api;

        $params = $atts;
        if(empty($params['approved'])) {
            $params = ticketmachine_array_push_assoc($params, "approved", 1);
        }
        $events = ticketmachine_tmapi_events($params)->result;
        $ticketmachine_output = "";
        
			
        if(empty($events)) {	
            $ticketmachine_output .= "<div class='col-12 text-center mt-1'>";
                $ticketmachine_output .= ticketmachine_alert(esc_html__("No events could be found", "ticketmachine-event-manager"), "error");
            $ticketmachine_output .= "</div>";
            
        }else{
				
            $ticketmachine_output .= '<div class="col-12">';
                $ticketmachine_output .= '<ul class="list-unstyled mx-0">';

                foreach($events as $event){
                    $event = (object)$event;
    
                    if(empty($event->state['sale_active'])){
                        $event->link = '/' . esc_html($tm_globals->event_slug) .'/?id=' . esc_html($event->id);
                    }else{
                        $event->link = esc_html($tm_globals->webshop_url) .'/events/unseated/select_unseated?event_id=' . esc_html($event->id);
                    }

                    $ticketmachine_output .= '<li class="media mx-0 mt-2 p-3">';

                    if(isset($atts['show_image']) && $atts['show_image'] > 0){
                        $ticketmachine_output .= '<a class="mr-3 media-img" href="' . $event->link . '" style="background-image:url('. $event->event_img_url .')"></a>';
                    }
                                        
                        $ticketmachine_output .= '<div class="media-body">';

                        if(isset($atts['show_date']) && $atts['show_date'] > 0){
                            $ticketmachine_output .= '
                            <div class="card-meta-tag"><i class="far fa-calendar-alt tm-icon" aria-hidden="true"></i> &nbsp;'. ticketmachine_i18n_date("d.m.Y", $event->ev_date) .'</div> 
                            <div class="card-meta-tag"><i class="far fa-clock tm-icon" aria-hidden="true"></i> &nbsp;'. ticketmachine_i18n_date("H:i", $event->ev_date) .'</div>';
                        }

                        $ticketmachine_output .= '<h5 class="mt-0 mb-1"><a class="tm-list-title" href="' . $event->link . '">' . $event->ev_name . '</a></h5>';
                        
                        if(isset($atts['show_description']) && $atts['show_description'] > 0){
                            if(empty($atts['description_length'])){
                                $atts['description_length'] = 15;
                            }
                            if(isset($atts['description_length'])){
                                $ticketmachine_output .= '<div>' . esc_html(wp_trim_words(wp_strip_all_tags($event->ev_description), $atts['description_length'], "...")) . '</div>';
                            }
                        }

                    $ticketmachine_output .= '</div>
                                </li>';
                }

                if(isset($atts['show_more']) && $atts['show_more'] > 0){
                    $ticketmachine_output .= '<li class="media"><a href="/' . esc_html($tm_globals->events_slug) . '">' . esc_html__("Show all events", "ticketmachine-event-manager") . '</a></li>';
                }

                $ticketmachine_output .= '</ul>';

            $ticketmachine_output .= '</div>';

        }

        return $ticketmachine_output;
    }

?>