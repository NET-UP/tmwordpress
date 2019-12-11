<?php
    function tm_event_page_google($event, $globals) {
        $tm_output .= "<iframe width='100%' height='300' id='mapcanvas' src='' frameborder='0' scrolling='no' marginheight='0' marginwidth='0'>
                        </iframe>"
        if () {
            $tm_output .= "<iframe width='100%' height='300' id='mapcanvas' src='https://maps.google.com/maps?q=". urlencode($event->event_location->street . ' ' . $event->event_location['house_number'] . ' ' . $event->event_location['zip'] . ' ' . $event->event_location['city'] . ' ' . $event->event_location['country'] ) ."&z=10&ie=UTF8&iwloc=&output=embed' frameborder='0' scrolling='no' marginheight='0' marginwidth='0'>
                        </iframe>";
 
        }
        
        return $tm_output;
    }
?>
