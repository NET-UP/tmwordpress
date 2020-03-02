<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    function ticketmachine_event_page_information ( $event, $globals ) {

        $ticketmachine_output = '
        
            <div class="title-height no-height-mobile">
                <h5>
                    <span>
                        <h3 class="d-inline-block">' . esc_html($event->ev_name) . '</h3>
                    </span>
                </h5>
            </div>
            <card class="card mb-3">
                <div class="card-img-top full" style="background-image:url('. esc_url($event->event_img_url) .')">
                    <div class="badge badge-danger float-right mt-1 mr-2">'. esc_html($event->rules["badge"]) .'</div>
                </div>
                <div class="card-body position-relative">

                    <div class="card-meta">
                        <div class="card-meta-tag"><i class="far fa-calendar-alt tm-icon" aria-hidden="true"></i> &nbsp;'. ticketmachine_i18n_date("d.m.Y", $event->ev_date) .'</div> 
                        <div class="card-meta-tag"><i class="far fa-clock tm-icon" aria-hidden="true"></i> &nbsp;'. ticketmachine_i18n_date("H:i", $event->ev_date) .'</div>';

                        if(isset($event->has_location) && $event->has_location == 1){                       
                             $ticketmachine_output .= '<div class="card-meta-tag"><i class="fas fa-map-marker-alt tm-icon"></i> &nbsp; <a aria-label="' . esc_attr("Event Location", 'ticketmachine') . ': ' . esc_html($event->ev_location_name) . '" href="' . esc_url($globals->map_query_url . urlencode($event->ev_location_name . " " .$event->event_location['street'] . " " . $event->event_location["house_number"] . " " . $event->event_location["zip"] . " " . $event->event_location["city"] . " " . $event->event_location["country"] )) . '" target="_blank" title="' . esc_attr("Event Location", 'ticketmachine') . ': ' . esc_html($event->ev_location_name) . '">' . esc_html($event->ev_location_name) . '</a> </div>';
                        }
                       

        $ticketmachine_output .= '</div>

                    <div class="card-text mt-3">
                        '. wpautop($event->ev_description) .'
                    </div>

                    <div class="card-meta text-center pt-1 pb-1 hidden read-more-container">
                        <button title="' . esc_attr("Read More", "ticketmachine") . '" class="btn btn-sm btn-secondary read-more" style="border-radius: 20px;">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>

                    <div class="card-meta mt-2">';

                    foreach($event->tags as $tag) {
                        $ticketmachine_output .= "<a href='/" . esc_html($globals->events_slug) . "?tag=" . esc_html($tag) . "' class='card-meta-tag keyword'>". $tag ."</a>";
                    }
        $ticketmachine_output .= '
                    </div>

                </div>
            </card>
        ';

        return $ticketmachine_output;
	}
?>