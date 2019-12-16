<?php

	function tm_display_event ( $atts, $globals, $api ) {
		global $event;

		$params = [ "id" => $_GET['id'] ];
		$event = tmapi_event($params);

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
		
		return $tm_output;
		
	}

?>