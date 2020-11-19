<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    function ticketmachine_event_organizer_details ( $organizer, $tm_globals ) {
        $ticketmachine_output = '<div class="card mb-3">'; 
                                    
                $ticketmachine_output .=       '<div class="col-12">
                                        <h4 class="d-inline-block">'. esc_html__("Organizer details", "ticketmachine-event-manager") .'</h4>
                                        <br>
                                        <div>'. esc_html($organizer->og_name) .'</div>
                                        <div>'. esc_html($organizer->zip) .' '. esc_html($organizer->city) .'</div>
                                        <div>'. esc_html($event->event_location['street']) .' '. esc_html($event->event_location['house_number']) .'</div>
                                    </div>';

            $ticketmachine_output .=   '</div>
                       </div>';
        return $ticketmachine_output;
    }

?>