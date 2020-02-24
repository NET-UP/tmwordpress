<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    function ticketmachine_event_page_details ( $event, $globals ) {
        $ticketmachine_output = '<div class="card mb-3">
                            <div class="row card-body position-relative">
                                <div class="col-sm-6">
                                    <h3 class="d-inline-block">'. esc_html__("Details", "ticketmachine") .'</h3>
                                    <br>
                                    <label>'. esc_html__("Start", "ticketmachine") .':</label>
                                    <div class="mb-2">
                                        <i class="far fa-calendar-alt tm-icon" aria-hidden="true"></i> &nbsp;'. ticketmachine_i18n_date("d. F", $event->ev_date) .' 
                                        &nbsp; <i class="far fa-clock tm-icon" aria-hidden="true"></i> &nbsp;'. ticketmachine_i18n_date("H:i", $event->ev_date) .'
                                    </div>
                                    <label>'. esc_html__("End", "ticketmachine").':</label>
                                    <div class="mb-2">
                                        <i class="far fa-calendar-alt tm-icon" aria-hidden="true"></i> &nbsp;'. ticketmachine_i18n_date("d. F", $event->endtime) .' 
                                        &nbsp; <i class="far fa-clock tm-icon" aria-hidden="true"></i> &nbsp;'. ticketmachine_i18n_date("H:i", $event->endtime) .'
                                    </div> 
                                    <label class="d-none">'. esc_html__("Entry", "ticketmachine").': </label>
                                    <div class="mb-2 d-none">'. esc_html__("free", "ticketmachine") .'</div>
                                </div>'; 
                                
            if(isset($event->has_location) && $event->has_location == 1){
                                    
                $ticketmachine_output .=       '<div class="col-sm-6">
                                        <h3 class="d-inline-block">'. esc_html__("Event Location", "ticketmachine") .'</h3>
                                        <br>
                                        <div>'. $event->ev_location_name .'</div>
                                        <div>'. $event->event_location['city'] .' '. $event->event_location['zip'] .'</div>
                                        <div>'. $event->event_location['street'] .' '. $event->event_location['house_number'] .'</div>
                                    </div>';
            }      

            $ticketmachine_output .=   '</div>
                       </div>';
        return $ticketmachine_output;
    }

?>