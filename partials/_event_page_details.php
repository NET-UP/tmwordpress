<?php

    function tm_event_page_details ( $event, $globals ) {
        $tm_output .= '<div class="card mb-2">
                            <div class="row card-body position-relative">
                                <div class="col-sm-6">
                                    <h3 class="d-inline-block">Details</h3>
                                    <label>__("Beginn", "ticketmachine"):</label>
                                    <div>
                                        <i class="far fa-calendar-alt tm-icon" aria-hidden="true"></i> &nbsp;'. strftime( "%e. %B", strtotime($event->ev_date) ) .' 
                                        <i class="far fa-clock tm-icon" aria-hidden="true"></i> &nbsp;'. strftime( "%H:%M", strtotime($event->ev_date) ) .'
                                    </div>
                                    <label>__("Ende", "ticketmachine"):</label>
                                    <div>
                                        <i class="far fa-calendar-alt tm-icon" aria-hidden="true"></i> &nbsp;'. strftime( "%e. %B", strtotime($event->endtime) ) .' 
                                        <i class="far fa-clock tm-icon" aria-hidden="true"></i> &nbsp;'. strftime( "%H:%M", strtotime($event->endtime) ) .'
                                    </div> 
                                    <div>__("Eintritt", "ticketmachine"): __("kostenlos", "ticketmachine")</div>';       
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