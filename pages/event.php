<?php

	function tm_display_event ( $atts ) {
		global $event, $globals, $api;

		$params = array();
		$tm_output = "";

		if(!empty($_GET['id'])){
			$params = array_push_assoc($params, "id", $_GET['id']);
			$event = tmapi_event($params);
		}

		if(isset($event->id)){
			$tm_output .= '
				<div class=	"col-12">
					<div class="row">
						<div class="col-12 col-lg-5 col-xl-6 tm_left">';
			$tm_output .= tm_event_page_information($event, $globals);
			$tm_output .= '
						</div>
						<div class="col-12 col-lg-7 col-xl-6 tm_right">';
			$tm_output .= tm_event_page_actions($event, $globals);
			$tm_output .= tm_event_page_tickets($event, $globals);
			$tm_output .= tm_event_page_details($event, $globals);
			if ($globals->show_google_map) {
				$tm_output .= tm_event_page_google_map($event, $globals);
			}
			$tm_output .= '
						</div>
					</div>
				</div>
			';
		}else{
			$error = __("No events could be found", "ticketmachine");
			$tm_output .= tm_error_page($error);
		}
		
		return $tm_output;
		
	}

?>