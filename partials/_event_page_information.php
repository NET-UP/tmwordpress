<?php
    function tm_event_page_information ( $event, $globals ) {

        $tm_output = '
        
            <div class="title-height no-height-mobile">
                <h5>
                    <span>
                        <h3 class="d-inline-block">' . $event->ev_name . '</h3>
                    </span>
                </h5>
            </div>
            <card class="card mb-3">
                <div class="card-img-top full" style="background-image:url('. $event->event_img_url .')">
                    <div class="badge badge-danger float-right mt-1 mr-2">'. $event->rules["badge"] .'</div>
                </div>
                <div class="card-body position-relative">

                    <div class="card-meta">
                        <div class="card-meta-tag"><i class="far fa-calendar-alt tm-icon" aria-hidden="true"></i> &nbsp;'. date_i18n( "d.m.Y", strtotime(get_date_from_gmt($event->ev_date)) ) .'</div> 
                        <div class="card-meta-tag"><i class="far fa-clock tm-icon" aria-hidden="true"></i> &nbsp;'. date_i18n( "H:i", strtotime(get_date_from_gmt($event->ev_date)) ) .'</div>';

                        if(isset($event->has_location) && $event->has_location == 1){                       
                             $tm_output .= '<div class="card-meta-tag"><i class="fas fa-map-marker-alt tm-icon"></i> &nbsp; <a aria-label="' . __("Event Location", 'ticketmachine') . ': ' . $event->ev_location_name . '" href="' . $globals->map_query_url . urlencode($event->ev_location_name . " " .$event->event_location['street'] . " " . $event->event_location["house_number"] . " " . $event->event_location["zip"] . " " . $event->event_location["city"] . " " . $event->event_location["country"] ) . '" target="_blank" title="' . __("Event Location", 'ticketmachine') . ': ' . $event->ev_location_name . '">' . $event->ev_location_name . '</a> </div>';
                        }
                       

        $tm_output .= '</div>

                    <div class="card-text mt-3">
                        '. wpautop($event->ev_description) .'
                    </div>

                    <div class="card-meta text-center pt-1 pb-1 hidden read-more-container">
                        <button title="' . __("Read More", "ticketmachine") . '" class="btn btn-sm btn-secondary read-more" style="border-radius: 20px;">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>

                    <div class="card-meta mt-2">';

                    foreach($event->tags as $tag) {
                        $tm_output .= "<a href='/" . $globals->events_slug . "?tag=" . $tag . "' class='card-meta-tag keyword'>". $tag ."</a>";
                    }
        $tm_output .= '
                    </div>

                </div>
            </card>
        ';

        return $tm_output;
	}
?>