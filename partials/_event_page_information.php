<?php
    function tm_event_page_information ( $event, $globals ) {

        $tm_output .= '
        
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
                        <div class="card-meta-tag"><i class="far fa-calendar-alt tm-icon" aria-hidden="true"></i> &nbsp;'. date( "d.m.Y", strtotime($event->ev_date) ) .'</div> 
                        <div class="card-meta-tag"><i class="far fa-clock tm-icon" aria-hidden="true"></i> &nbsp;'. date( "H:i", strtotime($event->ev_date) ) .'</div>
                        <div class="card-meta-tag"><i class="fas fa-map-marker-alt tm-icon"></i> &nbsp; <a aria-label="' . __("Veranstaltungsort: ") . $event->ev_location_name . '" href="' . $globals->map_query_url . urlencode($event->event_location->street . " " . $event->event_location["house_number"] . " " . $event->event_location["zip"] . " " . $event->event_location["city"] . " " . $event->event_location["country"] ) . '" target="_blank" title="' . __("Veranstaltungsort: ") . $event->ev_location_name . '">' . $event->ev_location_name . '</a> </div>
                        
                    </div>

                    <div class="card-text mt-3">
                        '. $event->ev_description .'
                    </div>

                    <div class="card-meta text-center pt-1 pb-1 hidden read-more-container">
                        <a href="#" title="' . __("Weiterlesen", "Ticketmachine") . '" class="btn btn-sm btn-secondary read-more" style="border-radius: 20px;">
                            <i class="fas fa-chevron-down"></i>
                        </a>
                    </div>

                    <div class="card-meta">';

                    foreach($event->tags as $tag) {
                        $tm_output .= "<a href='/events?tag=" . $tag . "' class='card-meta-tag keyword'>". $tag ."</a>";
                    }
        $tm_output .= '
                    </div>

                </div>
            </card>
        ';

        return $tm_output;
	}
?>