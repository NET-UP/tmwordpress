<?php
    function tm_event_page_google_map($event, $globals) {
        
        if (isset($_COOKIE["allow_google_maps"])){
            $tm_output = "<iframe width='100%' height='300' id='mapcanvas' src='https://maps.google.com/maps?q=". urlencode($event->event_location['street'] . ' ' . $event->event_location['house_number'] . ' ' . $event->event_location['zip'] . ' ' . $event->event_location['city'] . ' ' . $event->event_location['country'] ) ."&z=10&ie=UTF8&iwloc=&output=embed' frameborder='0' scrolling='no' marginheight='0' marginwidth='0'>
                            </iframe>
                            <a class='disallow-google-maps float-right' href='#'>" . __('Disallow Google Maps', 'ticketmachine') . "</a>";
        }else{
            $tm_output = "<div class='allow-google-maps-container'><div class='vertical-center text-center'><button class='allow-google-maps btn btn-primary' data-embed='https://maps.google.com/maps?q=". urlencode($event->event_location['street'] . ' ' . $event->event_location['house_number'] . ' ' . $event->event_location['zip'] . ' ' . $event->event_location['city'] . ' ' . $event->event_location['country'] ) ."&z=10&ie=UTF8&iwloc=&output=embed'>" . __('Allow Google Maps', 'ticketmachine') . "</button></div></div>";
        }

        return $tm_output;
    }
?>
