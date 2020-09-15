<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    function ticketmachine_event_page_google_map($event, $tm_globals) {

        if(isset($event->has_location) && $event->has_location == 1){
            if (isset($_COOKIE["allow_google_maps"])){
                $ticketmachine_output = "<iframe width='100%' height='300' id='mapcanvas' src='" . esc_url("https://maps.google.com/maps?q=". urlencode($event->ev_location_name . ' ' . $event->event_location['street'] . ' ' . $event->event_location['house_number'] . ' ' . $event->event_location['zip'] . ' ' . $event->event_location['city'] . ' ' . $event->event_location['country'] ) ."&z=10&ie=UTF8&iwloc=&output=embed") . "' frameborder='0' scrolling='no' marginheight='0' marginwidth='0'>
                                </iframe>";
            }else{
                $ticketmachine_output = "<div class='allow-google-maps-container'><div class='vertical-center text-center'><button class='allow-google-maps btn btn-primary' data-embed='" . esc_url("https://maps.google.com/maps?q=". urlencode($event->ev_location_name . ' ' . $event->event_location['street'] . ' ' . $event->event_location['house_number'] . ' ' . $event->event_location['zip'] . ' ' . $event->event_location['city'] . ' ' . $event->event_location['country'] ) ."&z=10&ie=UTF8&iwloc=&output=embed") . "'>" . esc_html__('Allow Google Maps', 'ticketmachine-event-manager') . "</button></div></div>";
            }
        }

        $ticketmachine_output .= "<a class='disallow-google-maps float-right";
        
        if (!isset($_COOKIE["allow_google_maps"])){
            $ticketmachine_output .= " hidden";
        }

        $ticketmachine_output .= "' href='#'>" . esc_html__('Disallow Google Maps', 'ticketmachine-event-manager') . "</a>";

        return $ticketmachine_output;
    }
?>
