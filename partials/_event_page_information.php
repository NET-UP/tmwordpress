<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    function ticketmachine_event_page_information ( $event, $ticketmachine_globals ) {

        if(ticketmachine_i18n_date("H:i", $event->ev_date) == "00:00" && ticketmachine_i18n_date("H:i", $event->endtime) == "23:59") {
            $dateoutput = __("Entire Day", "ticketmachine-event-manager");
        }else{
            $dateoutput = ticketmachine_i18n_date("H:i", $event->ev_date);
        }

		$image_ratio = $ticketmachine_globals->event_detail_image_ratio;

		if(empty($image_ratio)) {
			$image_ratio = "16:9";
		}

		$image_ratio = str_replace($image_ratio, " ", "");
		$image_ratio = str_replace($image_ratio, "/", "-");
		$image_ratio = str_replace($image_ratio, ":", "-");

        $ticketmachine_output = '
            <div class="title-height no-height-mobile">
                <h3>' . esc_html($event->ev_name) . '</h3>
            </div>
            <div class="card mb-3">
                <div class="card-img-top ratio-' . $image_ratio . '" style="background-image:url('. esc_url($event->event_img_url) .')">
                    <div class="event-badges"><div class="badge bg-danger float-right mt-1 me-2">'. esc_html($event->rules["badge"]) .'</div></div>
                </div>
                <div class="card-body position-relative">

                    <div class="card-meta">
                        <div class="card-meta-tag"><i class="far fa-calendar-alt tm-icon" aria-hidden="true"></i> &nbsp;'. ticketmachine_i18n_date("d.m.Y", $event->ev_date) .'</div>
                        <div class="card-meta-tag"><i class="far fa-clock tm-icon" aria-hidden="true"></i> &nbsp;'. $dateoutput .'</div>';

                        if(isset($event->has_location) && $event->has_location == 1){   
                            if(empty($event->ev_location_name)) {
                                $event_location = $event->event_location['street'] . " " . $event->event_location['house_number'];
                            }else{
                                $event_location = $event->ev_location_name;
                            }
                            $ticketmachine_output .= '<div class="card-meta-tag"><i class="fas fa-map-marker-alt tm-icon"></i> &nbsp; ';
                                if(isset($event->has_location_link) && $event->has_location_link == 1){        
                                    $ticketmachine_output .= '<a aria-label="' . esc_attr__("Event Location", 'ticketmachine-event-manager') . ': ' . esc_html($event->ev_location_name) . '" href="' . esc_url($ticketmachine_globals->map_query_url . urlencode($event->ev_location_name . " " .$event->event_location['street'] . " " . $event->event_location["house_number"] . " " . $event->event_location["zip"] . " " . $event->event_location["city"] . " " . $event->event_location["country"] )) . '" target="_blank" title="' . esc_attr__("Event Location", 'ticketmachine-event-manager') . ': ' . esc_html($event_location) . '">' . esc_html($event_location) . '</a>';
                                }else{
                                    $ticketmachine_output .= $event_location;
                                }
                            $ticketmachine_output .= '</div>';
                        }
                       

        $ticketmachine_output .= '</div>

                    <div class="card-text mt-3';
                    
                    if($ticketmachine_globals->detail_page_layout != 1){ 
                        $ticketmachine_output .= ' read-more-enabled';
                    }
                        
        $ticketmachine_output .= '">'. wpautop($event->ev_description) .'
                    </div>';

        if($ticketmachine_globals->detail_page_layout != 1){
            $ticketmachine_output .= '<div class="card-meta text-center pt-1 pb-1 hidden read-more-container">
                            <button title="' . esc_attr__("Read More", "ticketmachine-event-manager") . '" class="btn btn-sm btn-secondary read-more" style="border-radius: 20px;">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>';
        }

        $ticketmachine_output .= '<div class="card-meta mt-2">';
                    foreach($event->tags as $tag) {
                        $ticketmachine_output .= "<a href='/" . esc_html($ticketmachine_globals->events_slug) . "?tag=" . esc_html($tag) . "' class='card-meta-tag keyword'>". $tag ."</a>";
                    }
        $ticketmachine_output .= '
                    </div>

                </div>
            </div>
        ';

        return $ticketmachine_output;
	}
?>