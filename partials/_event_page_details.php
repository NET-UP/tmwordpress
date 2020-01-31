<?php

    function tm_event_page_details ( $event, $globals ) {
        $tm_output = '<div class="card mb-3">
                            <div class="row card-body position-relative">
                                <div class="col-sm-6">
                                    <h3 class="d-inline-block">'. __("Details", "ticketmachine") .'</h3>
                                    <br>
                                    <label>'. __("Start", "ticketmachine") .':</label>
                                    <div class="mb-2">
                                        <i class="far fa-calendar-alt tm-icon" aria-hidden="true"></i> &nbsp;'. strftime( "%e. %B", strtotime($event->ev_date) ) .' 
                                        &nbsp; <i class="far fa-clock tm-icon" aria-hidden="true"></i> &nbsp;'. strftime( "%H:%M", strtotime($event->ev_date) ) .'
                                    </div>
                                    <label>'. __("End", "ticketmachine").':</label>
                                    <div class="mb-2">
                                        <i class="far fa-calendar-alt tm-icon" aria-hidden="true"></i> &nbsp;'. strftime( "%e. %B", strtotime($event->endtime) ) .' 
                                        &nbsp; <i class="far fa-clock tm-icon" aria-hidden="true"></i> &nbsp;'. strftime( "%H:%M", strtotime($event->endtime) ) .'
                                    </div> 
                                    <label class="d-none">'. __("Entry", "ticketmachine").': </label>
                                    <div class="mb-2 d-none">'. __("free", "ticketmachine") .'</div>';       
            $tm_output .=       '</div>
                                <div class="col-sm-6">
                                    <h3 class="d-inline-block">'. __("Event Location", "ticketmachine") .'</h3>
                                    <br>
                                    <div>'. $event->ev_location_name .'</div>
                                    <div>'. $event->event_location['city'] .' '. $event->event_location['zip'] .'</div>
                                    <div>'. $event->event_location['street'] .' '. $event->event_location['house_number'] .'</div>
                                </div>
                            </div>
                       </div>';
        return $tm_output;
    }

?>