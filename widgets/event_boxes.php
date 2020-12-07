<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    function ticketmachine_widget_event_boxes ( $atts, $isWidget ) {
        global $tm_globals, $api;
        $ticketmachine_output = "";
        $params = $atts;
		$events = ticketmachine_tmapi_events($params)->result;

        include plugin_dir_path( __FILE__ ) . "../partials/_event_list_item.php";
        
        $prev = NULL;
        $i = 0;
        
        if(empty($events)) {	
            $ticketmachine_output .= "<div class='col-12 text-center mt-1'>";
                $ticketmachine_output .= ticketmachine_alert(esc_html__("No events could be found", "ticketmachine-event-manager"), "error");
            $ticketmachine_output .= "</div>";
            
        }else{
            $ticketmachine_output .= "<div class='col-12 mt-2'></div>";

            foreach($events as $event) {
                $event = (object)$event;
                
                $curr = $event->ev_date;
                if(isset($tm_globals->event_grouping) && $tm_globals->event_grouping != "None") {
                    if ($i == 0 || date( $tm_globals->group_by , strtotime( $curr ) ) != date( $tm_globals->group_by, strtotime( $prev ) ) ) {
                        $ticketmachine_output .= "<div class='col-12 my-2'>
                                            <div class='d-flex'>
                                                <hr class='my-auto flex-grow-1'>
                                                <h3 class='px-4'>" . ticketmachine_i18n_date($tm_globals->format_date, $event->ev_date) . "</h3>
                                                <hr class='my-auto flex-grow-1'>
                                            </div>
                                        </div>";
                        $prev = $curr;
                    }
                }
                
                $ticketmachine_output .= ticketmachine_event_list_item ( $event, $tm_globals );
                
                $i++;
            }
        }

        return $ticketmachine_output;
    }
?>