<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	function ticketmachine_display_event ( $atts ) {
		global $event, $globals, $api;

		$params = array();
		$ticketmachine_output = "";

		if(!empty($_GET['id'])){
			$params = ticketmachine_array_push_assoc($params, "id", absint($_GET['id']));
			$event = ticketmachine_tmapi_event($params);
		}

		$event->has_location = 0;
		if(!empty($event->ev_location_name) || !empty($event->event_location['city']) || !empty($event->event_location['street']) || !empty($event->event_location['zip']) || !empty($event->event_location['house_number'])){
			$event->has_location = 1;
		}

		if(isset($event->id)){
			$ticketmachine_output .= '
					<div class="row">
						<div class="col-12 col-lg-5 col-xl-6 ticketmachine_left">';
			$ticketmachine_output .= ticketmachine_event_page_information($event, $globals);
			$ticketmachine_output .= '
						</div>
						<div class="col-12 col-lg-7 col-xl-6 ticketmachine_right">';
			$ticketmachine_output .= ticketmachine_event_page_actions($event, $globals);
			$ticketmachine_output .= ticketmachine_event_page_tickets($event, $globals);
			$ticketmachine_output .= ticketmachine_event_page_details($event, $globals);
			if ($globals->show_google_map) {
				$ticketmachine_output .= ticketmachine_event_page_google_map($event, $globals);
			}
			$ticketmachine_output .= '
						</div>
					</div>';
		}else{
			$error = esc_html__("No events could be found", "ticketmachine");
			$ticketmachine_output .= ticketmachine_error_page($error, array(
														esc_html__("Back to events", "ticketmachine") => "/" . esc_html($globals->events_slug)
													), esc_html__("Oops!", "ticketmachine"));
		}
		
		return $ticketmachine_output;
		
	}

?>