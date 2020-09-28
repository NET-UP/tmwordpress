<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    function ticketmachine_event_page_details ( $event, $tm_globals ) {
        $ticketmachine_output = '<div class="card mb-3">
                            <div class="row card-body position-relative">
                                <div class="col-sm-6">
                                    <h4 class="d-inline-block">'. esc_html__("Details", "ticketmachine-event-manager") .'</h4>
                                    <br>
                                    <label>'. esc_html__("Start", "ticketmachine-event-manager") .':</label>
                                    <div class="mb-2">
                                        <i class="far fa-calendar-alt tm-icon" aria-hidden="true"></i> &nbsp;'. ticketmachine_i18n_date("d. F", $event->ev_date) .' 
                                        &nbsp; <i class="far fa-clock tm-icon" aria-hidden="true"></i> &nbsp;'. ticketmachine_i18n_date("H:i", $event->ev_date) .'
                                    </div>
                                    <label>'. esc_html__("End", "ticketmachine-event-manager").':</label>
                                    <div class="mb-2">
                                        <i class="far fa-calendar-alt tm-icon" aria-hidden="true"></i> &nbsp;'. ticketmachine_i18n_date("d. F", $event->endtime) .' 
                                        &nbsp; <i class="far fa-clock tm-icon" aria-hidden="true"></i> &nbsp;'. ticketmachine_i18n_date("H:i", $event->endtime) .'
                                    </div> 
                                    <label class="d-none">'. esc_html__("Entry", "ticketmachine-event-manager").': </label>
                                    <div class="mb-2 d-none">'. esc_html__("free", "ticketmachine-event-manager") .'</div>
                                </div>'; 
                                
            if(isset($event->has_location) && $event->has_location == 1){
                                    
                $ticketmachine_output .=       '<div class="col-sm-6">
                                        <h4 class="d-inline-block">'. esc_html__("Location", "ticketmachine-event-manager") .'</h4>
                                        <br>
                                        <div>'. esc_html($event->ev_location_name) .'</div>
                                        <div>'. esc_html($event->event_location['city']) .' '. esc_html($event->event_location['zip']) .'</div>
                                        <div>'. esc_html($event->event_location['street']) .' '. esc_html($event->event_location['house_number']) .'</div>
                                    </div>';
            }      

            $ticketmachine_output .=   '</div>
                       </div>';
        return $ticketmachine_output;
    }

?>