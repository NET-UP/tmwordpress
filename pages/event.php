<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	function ticketmachine_display_event ( $atts ) {
		global $event, $tm_globals, $api, $wpdb;

		$params = array();
		$ticketmachine_output = "";

		if(!empty($_GET['id'])){
			$params = ticketmachine_array_push_assoc($params, "id", absint($_GET['id']));
			$event = ticketmachine_tmapi_event($params);

            $table = $wpdb->prefix . "ticketmachine_organizers_events_match";
            $event_organizer_match = $wpdb->get_row( "SELECT * FROM $table WHERE `api_event_id` = " . $event->id );
            if(!empty($event_organizer_match)){
                $table = $wpdb->prefix . "ticketmachine_organizers";
				$organizer = $wpdb->get_row( "SELECT * FROM $table WHERE `id` = " . $event_organizer_match->organizer_id );
            }
		}
		if(isset($event)){

			$event->has_location = 0;
			if(!empty($event->ev_location_name) || !empty($event->event_location['city']) || !empty($event->event_location['street']) || !empty($event->event_location['zip']) || !empty($event->event_location['house_number'])){
				$event->has_location = 1;
			}

			if($tm_globals->detail_page_layout == 1){
				$tm1_lg_width = 12;
				$tm1_xl_width = 12;
				$tm2_lg_width = 12;
				$tm2_xl_width = 12;
			}else{
				$tm1_lg_width = 5;
				$tm1_xl_width = 6;
				$tm2_lg_width = 7;
				$tm2_xl_width = 6;
			}
	
			if(isset($event->id)){
				$ticketmachine_output .= '
						<div class="row">
							<div class="col-12 col-lg-' . $tm1_lg_width . ' col-xl-' . $tm1_xl_width . ' ticketmachine_left">';
				$ticketmachine_output .= ticketmachine_event_page_information($event, $tm_globals);
				$ticketmachine_output .= '
							</div>
							<div class="col-12 col-lg-' . $tm2_lg_width . ' col-xl-' . $tm2_xl_width . ' ticketmachine_right">';
				$ticketmachine_output .= ticketmachine_event_page_actions($event, $tm_globals);
				$ticketmachine_output .= ticketmachine_event_page_tickets($event, $tm_globals);
				if (isset($tm_globals->show_additional_info) && $tm_globals->show_additional_info) {
					$ticketmachine_output .= ticketmachine_event_page_details($event, $tm_globals);
				}
				if (isset($tm_globals->show_google_map) && $tm_globals->show_google_map) {
					$ticketmachine_output .= ticketmachine_event_page_google_map($event, $tm_globals);
				}
				if (!empty($organizer)) {
				}
				$ticketmachine_output .= '
							</div>
						</div>';
			}else{
				$error = esc_html__("No events could be found", "ticketmachine-event-manager");
				$ticketmachine_output .= ticketmachine_error_page($error, array(
															esc_html__("Back to events", "ticketmachine-event-manager") => "/" . esc_html($tm_globals->events_slug)
														), esc_html__("Oops!", "ticketmachine-event-manager"));
			}
			
			return $ticketmachine_output;
		}
		
	}

?>