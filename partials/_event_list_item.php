<?php 

	function tm_event_list_item ( $event, $globals ) {
		$event->event_location = (object) $event->event_location;
		
		$tm_output = '<div class="col-12 col-md-6 col-xl-4 card-group">';
			$tm_output .= '<card class="card mb-4">';
				$tm_output .= '<a aria-label="' . $event->ev_name . ' am ' . date( "d", strtotime($event->ev_date) ) . '. ' . strftime( "%B", strtotime($event->ev_date) ) . ' ' . date( "Y", strtotime($event->ev_date) ) . '" href="/event/?id=' . $event->id . '" class="card-img-top" style="background-image:url( ' . $event->event_img_url . ' )" title="' . $event->ev_name . '">';
					$tm_output .= '<div class="badge badge-danger float-right mt-1 mr-2">'. $event->rules["badge"] .'</div>';
				$tm_output .= '</a>';
				$tm_output .= '<div class="card-body position-relative">';

				  $tm_output .= '<div class="card-date">';
					$tm_output .= '<div class="card-day">' . date( "d", strtotime($event->ev_date) ) . '</div>';
					$tm_output .= '<div class="card-month">' . strftime( "%b", strtotime($event->ev_date) ) . '</div>';
				  $tm_output .= '</div>';
				  $tm_output .= '<h5 class="card-title" title="' . $event->ev_name . '">' . $event->ev_name . '</h5>';
				  $tm_output .= '<div class="card-price"></div>';

				  $tm_output .= '<div class="row pt-2">';
					$tm_output .= '<div class="col-sm-8 col-md-7">';
					  $tm_output .= '<p class="card-text mt-0 px-2 pt-sm-1 pb-3 pb-sm-2 ellipsis">';
						$tm_output .= '<i class="fas fa-map-marker-alt tm-icon"></i> &nbsp;';
						$tm_output .= '<a aria-label="' . __("Event Location", 'ticketmachine') . ': ' . $event->ev_location_name . '" href="' . $globals->map_query_url . urlencode($event->event_location->street . " " . $event->event_location->house_number . " " . $event->event_location->zip . " " . $event->event_location->city . " " . $event->event_location->country ) . '" target="_blank" title="' . __("Event Location", 'ticketmachine') . ': ' . $event->ev_location_name . '">' . $event->ev_location_name . '</a>';
					  $tm_output .= '</p>';
					$tm_output .= '</div>';
					$tm_output .= '<div class="col-sm-4 col-md-5">';
					  $tm_output .= '<a aria-label="' . __("To ticket selection for", 'ticketmachine') . ' ' . $event->ev_name  . '" href="/event/?id=' . $event->id . '" class="btn btn-primary btn-sm px-3 float-sm-right d-block" title="' . __("To ticket selection", 'ticketmachine') . '">';
						$tm_output .= __("More", 'ticketmachine') . ' &nbsp;<i class="fas fa-angle-right"></i>';
					  $tm_output .= '</a>';
					$tm_output .= '</div>';
				  $tm_output .= '</div>';
				$tm_output .= '</div>';
			$tm_output .= '</card>';
		$tm_output .= '</div>';
		
		return $tm_output;

	}
	
?>
	



