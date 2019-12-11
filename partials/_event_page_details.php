<?php

    function tm_event_page_details ( $event, $globals ) {
        $tm_output .= '<div class="card mb-4">
                            <div class="row card-body position-relative">
                                <div class="col-sm-6 card-meta">
                                    <h3 class="d-inline-block card-meta-tag">Details</h3>
                                    <div class="card-meta-tag"> Beginn &nbsp;
                                        <i class="far fa-calendar-alt tm-icon" aria-hidden="true"></i> &nbsp;'. date( "d. F", strtotime($event->ev_date) ) .'
                                    </div> 
                                    <div class="card-meta-tag">Ende &nbsp;
                                        <i class="far fa-calendar-alt tm-icon" aria-hidden="true"></i> &nbsp;'. date( "d. F", strtotime($event->endtime) ) .'
                                    </div> 
                                    <div class="card-meta-tag">Eintritt: kostenlos</div>';       
            $tm_output .=       '</div>
                                <div class="col-sm-6 card-meta">
                                    <h3 class="d-inline-block card-meta-tag">Veranstaltungsort</h3>
                                    <div class="card-meta-tag">'. $event->ev_location_name .'</div>
                                    <div class="card-meta-tag">'. $event->event_location['city'] .' '. $event->event_location['zip'] .'</div>
                                    <div class="card-meta-tag">'. $event->event_location['street'] .' '. $event->event_location['house_number'] .'</div>
                                </div>
                            </div>
                       </div>';
        return $tm_output;
    }

?>