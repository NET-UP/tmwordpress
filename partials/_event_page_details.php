<?php

    function tm_event_page_details ( $event, $globals ) {
        $tm_output .= '<div class="card mb-4">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h3 class="d-inline-block">Details</h3>
                                    <div>Beginn:</div>
                                    <div>'. date( "d. F", strtotime($event->ev_date) ) .'</div>
                                    <div>Ende:</div>
                                    <div>'. date( "d. F", strtotime($event->endtime)) .'</div>
                                    <div>Eintritt:</div>
                                    <div>kostenlos</div>
                                    <div>Schlagwörter:</div>';
                                    
            $tm_output .=       '</div>
                                <div class="col-sm-6">
                                    <h3 class="d-inline-block">Veranstaltungsort</h3>
                                    <div>'. $event->ev_location_name .'</div>
                                    <div>'. $event->event_location['city'] .' '. $event->event_location['zip'] .'</div>
                                    <div>'. $event->event_location['street'] .' '. $event->event_location['house_number'] .'</div>
                                </div>
                            </div>
                       </div>';
        return $tm_output;
    }

?>