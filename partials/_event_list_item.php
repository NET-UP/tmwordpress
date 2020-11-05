<?php 
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	function ticketmachine_event_list_item ( $event, $tm_globals ) {
		
		$event->event_location = (object) $event->event_location;
		
		$ticketmachine_output = '<div class="col-12 col-md-6 col-xl-4 card-group">';
			$ticketmachine_output .= '<card class="card mb-4">';
				$ticketmachine_output .= '<a aria-label="' . esc_attr($event->ev_name) . ' am ' . ticketmachine_i18n_date("d. F Y", $event->ev_date) . '" href="/' . esc_html($tm_globals->event_slug) .'?id=' . esc_html($event->id) . '" class="card-img-top" style="background-image:url( ' . esc_url($event->event_img_url) . ' )" title="' . esc_attr($event->ev_name) . '">';
					$ticketmachine_output .= '<div class="badge badge-danger float-right mt-1 mr-2">'. esc_html($event->rules["badge"]) .'</div>';
				$ticketmachine_output .= '</a>';
				$ticketmachine_output .= '<div class="card-body position-relative">';

				  $ticketmachine_output .= '<div class="card-date" title="' . ticketmachine_i18n_date("d. F Y", $event->ev_date) . '">';
					$ticketmachine_output .= '<div class="card-day">' . ticketmachine_i18n_date("d", $event->ev_date) . '</div>';
					$ticketmachine_output .= '<div class="card-month">' . ticketmachine_i18n_date("M", $event->ev_date) . '</div>';
				  $ticketmachine_output .= '</div>';
				  $ticketmachine_output .= '<h5 class="card-title" title="' . esc_attr($event->ev_name) . '">' . esc_html($event->ev_name) . '</h5>';
				  $ticketmachine_output .= '<div class="card-price"></div>';

				  $ticketmachine_output .= '<div class="row pt-2">';
					$ticketmachine_output .= '<div class="col-sm-8 col-md-7">';
					if(!empty($event->ev_location_name)){
						$ticketmachine_output .= '<p class="card-text mt-0 px-2 pt-sm-1 pb-3 pb-sm-2 ellipsis">';
							$ticketmachine_output .= '<i class="fas fa-map-marker-alt tm-icon"></i> &nbsp;';
							$ticketmachine_output .= '<a aria-label="' . esc_attr__("Event Location", 'ticketmachine-event-manager') . ': ' . esc_html($event->ev_location_name) . '" href="' . esc_url($tm_globals->map_query_url . urlencode($event->ev_location_name . " " . $event->event_location->street . " " . $event->event_location->house_number . " " . $event->event_location->zip . " " . $event->event_location->city . " " . $event->event_location->country )) . '" target="_blank" title="' . esc_html__("Event Location", 'ticketmachine-event-manager') . ': ' . $event->ev_location_name . '">' . $event->ev_location_name . '</a>';
						$ticketmachine_output .= '</p>';
					}
					$ticketmachine_output .= '</div>';
					$ticketmachine_output .= '<div class="col-sm-4 col-md-5">';
					  $ticketmachine_output .= '<a aria-label="' . esc_attr__("To ticket selection for", 'ticketmachine-event-manager') . ' ' . esc_html($event->ev_name)  . '"';
					  
					  if(empty($event->state['sale_active'])){
						$ticketmachine_output .= ' href="/' . esc_html($tm_globals->event_slug) .'/?id=' . esc_html($event->id) . '"';
					  }
					  
					  $ticketmachine_output .=' class="btn btn-primary btn-sm px-3 float-sm-right d-block" title="' . esc_html__("To ticket selection", 'ticketmachine-event-manager') . '">';
					
					  if(empty($event->state['sale_active'])){
						  $ticketmachine_output .= esc_html__("More", 'ticketmachine-event-manager') . ' &nbsp;<i class="fas fa-angle-right"></i>';
					  }else{
						$ticketmachine_output .= esc_html__("Tickets", 'ticketmachine-event-manager') . ' &nbsp;<i class="fas fa-ticket-alt"></i>';
					  }
					  $ticketmachine_output .= '</a>';
					$ticketmachine_output .= '</div>';
				  $ticketmachine_output .= '</div>';
				$ticketmachine_output .= '</div>';
			$ticketmachine_output .= '</card>';
		$ticketmachine_output .= '</div>';
		
		return $ticketmachine_output;

	}
	
?>
	



