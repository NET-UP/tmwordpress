<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    if(!function_exists("ticketmachine_widget_event_boxes")) {
        function ticketmachine_widget_event_boxes ( $atts, $isWidget ) {
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

            include_once plugin_dir_path( __FILE__ ) . "../partials/_event_list_item.php";
            
            $prev = NULL;
            $i = 0;
            
            if($isWidget == 1){
                $ticketmachine_output .= "<div class='row'>";
            }
            
            if(empty($events)) {	
                $ticketmachine_output .= "<div class='col-12 text-center mt-1'>";
                    $ticketmachine_output .= ticketmachine_alert(esc_html__("No events could be found", "ticketmachine-event-manager"), "error");
                $ticketmachine_output .= "</div>";
                
            }else{
                $ticketmachine_output .= "<div class='col-12 mt-2'></div>";

                foreach($events as $event) {
                    $event = (object)$event;
                    
                    $curr = $event->ev_date;
                    if(isset($ticketmachine_globals->event_grouping) && $ticketmachine_globals->event_grouping != "None") {
                        if ($i == 0 || date( $ticketmachine_globals->group_by , strtotime( $curr ) ) != date( $ticketmachine_globals->group_by, strtotime( $prev ) ) ) {
                            $ticketmachine_output .= "<div class='col-12 my-2'>
                                                <div class='d-flex'>
                                                    <hr class='my-auto flex-grow-1'>
                                                    <h3 class='px-4 mt-0'>" . ticketmachine_i18n_date($ticketmachine_globals->format_date, $event->ev_date) . "</h3>
                                                    <hr class='my-auto flex-grow-1'>
                                                </div>
                                            </div>";
                            $prev = $curr;
                        }
                    }

                    $event->has_location = 0;
                    $event->has_location_link = 0;
                    if(!empty($event->ev_location_name) || !empty($event->event_location['city']) || !empty($event->event_location['street']) || !empty($event->event_location['zip']) || !empty($event->event_location['house_number'])){
                        $event->has_location = 1;
                        $event->has_location_link = 1;
                    }

                    if(empty($event->event_location['city']) || empty($event->event_location['street']) || empty($event->event_location['house_number'])){
                        $event->has_location_link = 0;
                    }
                    
                    $ticketmachine_output .= ticketmachine_event_list_item ( $event, $ticketmachine_globals, $atts );
                    
                    $i++;
                }
            }
            
                    
            $ticketmachine_output .= '<div class="col-12">';

            $ticketmachine_output .= ticketmachine_pagination($meta, $params);
            
            $ticketmachine_output .= '</div>';

            if($isWidget == 1){
                $ticketmachine_output .= "</div>";
            }

            return $ticketmachine_output;
        }
    }
?>