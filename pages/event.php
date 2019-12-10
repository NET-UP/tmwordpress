<?php

	function tm_display_event ( $atts, $globals, $api ) {

		$params = [ "id" => $_GET['id'] ];
		$event = tmapi_event($params)->result;

		$tm_output .= '
			<div class=	"col-12">
				<div class="row">
					<div class="col-12">
						<h5>
						<span>
							<h3 class="d-inline-block" role="fe_nu_ev_name">' . $event->ev_name . '</h3>
						</span>
						</h5>
					</div>
				</div>
				
				<div class="row">
					<div class="col-12 col-lg-5 col-xl-6">';
		$tm_output .= tm_event_page_information($event, $globals);
		$tm_output .= '
					</div>
					<div class="col-12 col-lg-7 col-xl-6">';
		$tm_output .= tm_event_page_tickets($event, $globals);
		$tm_output .= '
					</div>
				</div>
			</div>
		';
		
		return $tm_output;
		
	}

?>