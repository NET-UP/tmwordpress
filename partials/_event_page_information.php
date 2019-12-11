<?php
    function tm_event_page_information ( $event, $globals ) {

        $tm_output .= '
            <card class="card mb-3">
                <div class="card-img-top" style="background-image:url('. $event->event_img_url .')">
                    <div class="badge badge-danger float-right mt-1 mr-2">'. $event->rules["badge"] .'</div>
                </div>
                <div class="card-body position-relative">

                    <div class="card-meta">
                        <div class="card-meta-tag" role="fe_nu_ev_date"><i class="far fa-calendar-alt tm-icon" aria-hidden="true"></i> &nbsp;'. date( "d.m.Y", strtotime($event->ev_date) ) .'</div> 
                        <div class="card-meta-tag" role="fe_nu_ev_time"><i class="far fa-clock tm-icon" aria-hidden="true"></i> &nbsp;'. date( "H:i", strtotime($event->ev_date) ) .'</div>
                        <div class="card-meta-tag" role="fe_nu_ev_location"><i class="fas fa-map-marker-alt tm-icon"></i> &nbsp; <a aria-label="' . __("Veranstaltungsort: ") . $event->ev_location_name . '" href="' . $globals->map_query_url . urlencode($event->event_location->street . " " . $event->event_location["house_number"] . " " . $event->event_location["zip"] . " " . $event->event_location["city"] . " " . $event->event_location["country"] ) . '" target="_blank" title="' . __("Veranstaltungsort: ") . $event->ev_location_name . '">' . $event->ev_location_name . '</a> </div>
                        
                    </div>

                    <div class="card-text mt-3" role="fe_nu_ev_des">
                        '. $event->ev_description .'
                    </div>

                    <div class="card-meta">';

                    foreach($event->tags as $tags) {
                        $tm_output .= "<div class='card-meta-tag keyword'>". $tags ."</div>";
                    }
        $tm_output .= '
                    </div>

                </div>
            </card>
        ';

        return $tm_output;
	}
?>