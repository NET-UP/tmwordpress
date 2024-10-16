<?php 
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	function ticketmachine_event_list_item ( $event, $ticketmachine_globals, $atts ) {
		
		if(empty($event->state['sale_active'])){
			$event->link = '/' . esc_html($ticketmachine_globals->event_slug) .'/?id=' . esc_html($event->id);
		}else{
			$event->link = esc_html($ticketmachine_globals->webshop_url) .'/events/unseated/select_unseated?event_id=' . esc_html($event->id);
		}
		
		$event->event_location = (object) $event->event_location;

		if(isset($atts['columns']) && $atts['columns'] == 1) {
			$colmd = 12;
			$colxl = 12;
		}elseif(isset($atts['columns']) && $atts['columns'] == 2){
			$colmd = 6;
			$colxl = 6;
		}else{
			$colmd = 6;
			$colxl = 4;
		}
		
		$ticketmachine_output = '<div class="col-12 col-md-' . $colmd . ' col-xl-' . $colxl . ' card-group">';
			$ticketmachine_output .= '<card class="card mb-4">';
				$ticketmachine_output .= '<a aria-label="' . esc_attr($event->ev_name) . ' am ' . ticketmachine_i18n_date("d. F Y", $event->ev_date) . '" href="' . $event->link . '" class="card-img-top ratio-16-9" style="background-image:url( ' . esc_url($event->event_img_url) . ' )" title="' . esc_attr($event->ev_name) . '">';
					$ticketmachine_output .= '<div class="event-badges">';
					if($event->rules["sale_active"] && !$event->state["sale_active"]) {
						$ticketmachine_output .= '<div class="badge bg-danger float-right mt-1 me-2">'. __("Sold out", "ticketmachine-event-manager") .'</div>';
					}
					if($event->rules["badge"]) {
						$ticketmachine_output .= '<div class="badge bg-danger float-right mt-1 me-2">'. esc_html($event->rules["badge"]) .'</div>';
					}
					$ticketmachine_output .= '</div>';
				$ticketmachine_output .= '</a>';
				$ticketmachine_output .= '<div class="card-body position-relative">';
				
					$ticketmachine_output .= '<div class="card-date" title="' . ticketmachine_i18n_date("d. F Y", $event->ev_date) . '">';

					if(isset($event->endtime) && ticketmachine_i18n_date("d.m.Y", $event->ev_date) != ticketmachine_i18n_date("d.m.Y", $event->endtime) ) {
							$ticketmachine_output .= '<div class="card-day">' . ticketmachine_i18n_date("d.m.y", $event->ev_date) . ' - ' . ticketmachine_i18n_date("d.m.y", $event->endtime) . '</div>';
					}else{
							$ticketmachine_output .= '<div>' . ticketmachine_i18n_date("d. F Y", $event->ev_date) . '</div>';
					}
					$ticketmachine_output .= '</div>';


				  $ticketmachine_output .= '<h5 class="card-title" title="' . esc_attr($event->ev_name) . '">' . esc_html($event->ev_name) . '</h5>';
				  $ticketmachine_output .= '<div class="card-price"></div>';

				  $ticketmachine_output .= '<div class="d-sm-flex justify-content-between align-items-center gap-3">';

					if(isset($event->has_location) && $event->has_location == 1){   
						if(empty($event->ev_location_name)) {
							$event_location = $event->event_location->street . " " . $event->event_location->house_number;
						}else{
							$event_location = $event->ev_location_name;
						}
						$ticketmachine_output .= '<div class="card-text mt-0 pt-sm-1 ellipsis"><i class="fas fa-map-marker-alt tm-icon"></i> &nbsp; ';
							if(isset($event->has_location_link) && $event->has_location_link == 1){        
								$ticketmachine_output .= '<a aria-label="' . esc_attr__("Event Location", 'ticketmachine-event-manager') . ': ' . esc_html($event->ev_location_name) . '" href="' . esc_url($ticketmachine_globals->map_query_url . urlencode($event->ev_location_name . " " .$event->event_location->street . " " . $event->event_location->house_number . " " . $event->event_location->zip . " " . $event->event_location->city . " " . $event->event_location->country )) . '" target="_blank" title="' . esc_attr__("Event Location", 'ticketmachine-event-manager') . ': ' . esc_html($event_location) . '">' . esc_html($event_location) . '</a>';
							}else{
								$ticketmachine_output .= $event_location;
							}
						$ticketmachine_output .= '</div>';
					}

					  $ticketmachine_output .= '<a aria-label="' . esc_attr__("To ticket selection for", 'ticketmachine-event-manager') . ' ' . esc_html($event->ev_name)  . '"';
					  $ticketmachine_output .= ' href="' . $event->link . '"';
					  $ticketmachine_output .=' class="btn btn-primary btn-sm px-3 mt-md-2 ticket-btn" title="' . esc_html__("To ticket selection", 'ticketmachine-event-manager') . '">';
					
					  if(empty($event->rules['sale_active'])){
						  $ticketmachine_output .= esc_html__("More", 'ticketmachine-event-manager') . ' &nbsp;<i class="fas fa-angle-right"></i>';
					  }else{
						$ticketmachine_output .= esc_html__("Tickets", 'ticketmachine-event-manager') . ' &nbsp;<i class="fas fa-ticket-alt"></i>';
					  }
					  $ticketmachine_output .= '</a>';
				  $ticketmachine_output .= '</div>';
				$ticketmachine_output .= '</div>';
			$ticketmachine_output .= '</card>';
		$ticketmachine_output .= '</div>';
		
		return $ticketmachine_output;

	}
	
?>