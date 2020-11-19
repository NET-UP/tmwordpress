<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    function ticketmachine_event_organizer_details ( $organizer, $tm_globals ) {
        $ticketmachine_output = '<div class="row"><div class="col-12"><div class="card mb-3"> 
                                    <div class="row card-body position-relative">';
                                    
                $ticketmachine_output .= '<div class="col-sm-6">
                                            <h4 class="d-inline-block">'. esc_html__("Organizer", "ticketmachine-event-manager") .'</h4>
                                            <br>
                                            <div><strong>'. esc_html($organizer->og_name) .'</strong></div>
                                            <div>'. esc_html($organizer->street) .' '. esc_html($organizer->og_house_number) .'</div>
                                            <div>'. esc_html($organizer->zip) .' '. esc_html($organizer->city) .'</div>
                                            <div>'. esc_html($event->event_location['street']) .' '. esc_html($event->event_location['house_number']) .'</div>
                                        </div>';
                                    
                $ticketmachine_output .= '<div class="col-sm-6">
                                            <h4 class="d-inline-block">'. esc_html__("Contact", "ticketmachine-event-manager") .'</h4>
                                            <br>
                                            <div><strong>'. esc_html($organizer->og_name) .'</strong></div>
                                            <div>'. esc_html($organizer->zip) .' '. esc_html($organizer->city) .'</div>
                                            <div>'. esc_html($event->event_location['street']) .' '. esc_html($event->event_location['house_number']) .'</div>
                                        </div>';

            $ticketmachine_output .=   '</div></div></div>
                       </div>';
        return $ticketmachine_output;
    }

?>