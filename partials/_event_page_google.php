<?php
    function tm_event_page_google($event, $globals) {
        
        if (isset($_COOKIE["lg"])){
            $tm_output .= "<iframe width='100%' height='300' id='mapcanvas' src='https://maps.google.com/maps?q=". urlencode($event->event_location->street . ' ' . $event->event_location['house_number'] . ' ' . $event->event_location['zip'] . ' ' . $event->event_location['city'] . ' ' . $event->event_location['country'] ) ."&z=10&ie=UTF8&iwloc=&output=embed' frameborder='0' scrolling='no' marginheight='0' marginwidth='0'>
                            </iframe>";
        }else{
            $tm_output .= "<div id='test' style='height:300px;width:100%;'><a href='#'>Google Maps anzeigen</a></div>";
        }

        return $tm_output;
    }
?>
