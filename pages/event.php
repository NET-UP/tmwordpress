<?php

	function tm_display_event ( $atts, $globals, $api ) {

		$event = apiRequest($api->get_single_event);
		print_r($event);

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
						#include "event_page_information.php";
		$tm_output .= '
					</div>
					<div class="col-12 col-lg-7 col-xl-6">';
						#include "event_page_tickets.php";
		$tm_output .= '
					</div>
				</div>
			</div>
		';
		
		return $tm_output;
		
	}

?>