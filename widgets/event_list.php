<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    if(!function_exists("ticketmachine_widget_event_list")) {
        function ticketmachine_widget_event_list ( $atts, $isWidget ) {
            global $ticketmachine_globals, $ticketmachine_api;
            $ticketmachine_output = "";
            unset($atts['page']);
            unset($atts['widget']);

            $params = $atts;

            if(empty($params['per_page'])) {
                $params["per_page"] = 30;
            }
            
            if(empty($params["pg"])) {
                $params["pg"] = 1;
            }
            
            if(empty($params['approved'])) {
                $params = ticketmachine_array_push_assoc($params, "approved", 1);
            }
            $response = ticketmachine_tmapi_events($params);
            $events = $response->result;
            $meta = $response->meta;

            if(!isset($atts['show_image'])){
                $atts['show_image'] = 1;
            }
            if(!isset($atts['show_description'])){
                $atts['show_description'] = 1;
            }
            if(!isset($atts['show_date'])){
                $atts['show_date'] = 1;
            }

            if($isWidget == 1){
                $ticketmachine_output .= "<div class='row'><div class='row ticketmachine_widget_event_list'>";
            }

            if(empty($events)) {	
                $ticketmachine_output .= "<div class='col-12 text-center mt-1'>";
                    $ticketmachine_output .= ticketmachine_alert(esc_html__("No events could be found", "ticketmachine-event-manager"), "error");
                $ticketmachine_output .= "</div>";
                
            }else{
                    
                $ticketmachine_output .= '<div class="col-12">';
                    $ticketmachine_output .= '<ul class="list-unstyled mx-0">';

                    foreach($events as $event){
                        $event = (object)$event;

                        $event->has_location = 0;
                        $event->has_location_link = 0;
                        if(!empty($event->ev_location_name) || !empty($event->event_location['city']) || !empty($event->event_location['street']) || !empty($event->event_location['zip']) || !empty($event->event_location['house_number'])){
                            $event->has_location = 1;
                            $event->has_location_link = 1;
                        }

                        if(empty($event->event_location['city']) || empty($event->event_location['street']) || empty($event->event_location['house_number'])){
                            $event->has_location_link = 0;
                        }
        
                        if(empty($event->state['sale_active'])){
                            $event->link = '/' . esc_html($ticketmachine_globals->event_slug) .'/?id=' . esc_html($event->id);
                        }else{
                            $event->link = esc_html($ticketmachine_globals->webshop_url) .'/events/unseated/select_unseated?event_id=' . esc_html($event->id);
                        }

                        $ticketmachine_output .= '<li class="media mx-0 mt-2 p-3">';

                        if(isset($atts['show_image']) && $atts['show_image'] > 0){
                            $ticketmachine_output .= '<a class="me-3 media-img" href="' . $event->link . '" style="background-image:url('. $event->event_img_url .')"></a>';
                        }
                                            
                            $ticketmachine_output .= '<div class="media-body">';

                            if(!empty($event->rules["badge"])) {
                                $ticketmachine_output .= '<div class="badge bg-danger m-0">'. esc_html($event->rules["badge"]) .'</div><br/>';
                            }
                            if(isset($atts['show_date']) && $atts['show_date'] > 0){
                                if(ticketmachine_i18n_date("H:i", $event->ev_date) == "00:00" && ticketmachine_i18n_date("H:i", $event->endtime) == "23:59") {
                                    $dateoutput = __("Entire Day", "ticketmachine-event-manager");
                                }else{
                                    $dateoutput = ticketmachine_i18n_date("H:i", $event->ev_date);
                                }

                                if(isset($event->endtime) && ticketmachine_i18n_date("d.m.Y", $event->ev_date) != ticketmachine_i18n_date("d.m.Y", $event->endtime) ) {
                                    $ticketmachine_output .= '
                                    <div class="card-meta-tag"><i class="far fa-calendar-alt tm-icon" aria-hidden="true"></i> &nbsp;'. ticketmachine_i18n_date("d.m.Y", $event->ev_date) .' - '. ticketmachine_i18n_date("d.m.Y", $event->endtime) .'</div> 
                                    <div class="card-meta-tag"><i class="far fa-clock tm-icon" aria-hidden="true"></i> &nbsp;'. $dateoutput .'</div>';
                                }else{
                                    $ticketmachine_output .= '
                                    <div class="card-meta-tag"><i class="far fa-calendar-alt tm-icon" aria-hidden="true"></i> &nbsp;'. ticketmachine_i18n_date("d.m.Y", $event->ev_date) .'</div> 
                                    <div class="card-meta-tag"><i class="far fa-clock tm-icon" aria-hidden="true"></i> &nbsp;'. $dateoutput .'</div>';
                                }

                            }

                            if(isset($event->has_location) && $event->has_location == 1){   
                                if(empty($event->ev_location_name)) {
                                    $event_location = $event->event_location->street . " " . $event->event_location->house_number;
                                }else{
                                    $event_location = $event->ev_location_name;
                                }
                                $ticketmachine_output .= '<div class="card-meta-tag"><i class="fas fa-map-marker-alt tm-icon"></i> &nbsp; ';
                                    if(isset($event->has_location_link) && $event->has_location_link == 1){        
                                        $ticketmachine_output .= '<a aria-label="' . esc_attr__("Event Location", 'ticketmachine-event-manager') . ': ' . esc_html($event->ev_location_name) . '" href="' . esc_url($ticketmachine_globals->map_query_url . urlencode($event->ev_location_name . " " .$event->event_location->street . " " . $event->event_location->house_number . " " . $event->event_location->zip . " " . $event->event_location->city . " " . $event->event_location->country )) . '" target="_blank" title="' . esc_attr__("Event Location", 'ticketmachine-event-manager') . ': ' . esc_html($event_location) . '">' . esc_html($event_location) . '</a>';
                                    }else{
                                        $ticketmachine_output .= $event_location;
                                    }
                                $ticketmachine_output .= '</div>';
                            }

                            $ticketmachine_output .= '<h5 class="mt-1 mb-1"><a class="tm-list-title" href="' . $event->link . '">' . $event->ev_name . '</a></h5>';
                            
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
                        $ticketmachine_output .= '<li class="media"><a href="/' . esc_html($ticketmachine_globals->events_slug) . '">' . esc_html__("Show all events", "ticketmachine-event-manager") . '</a></li>';
                    }

                    $ticketmachine_output .= '</ul>';
                    
                    $ticketmachine_output .= ticketmachine_pagination($meta, $params);

                $ticketmachine_output .= '</div>';

            }

            if($isWidget == 1){
                $ticketmachine_output .= "</div></div>";
            }

            return $ticketmachine_output;
        }
    }

?>