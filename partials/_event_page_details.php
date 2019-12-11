<?php

    function tm_event_page_details ( $event, $globals ) {
        $tm_output .= '<div>
                        <h2>Details</h2>
                        <div>Beginn:</div>
                        <div>'. date( "d", strtotime($event->ev_date) ) .'</div>
                        <div>Ende:</div>
                        <div>'. date( "d", strtotime($event->endtime)) .'</div>
                        <div>Eintritt:</div>
                        <div>kostenlos</div>
                        <div>Schlagwörter:</div>
                        <div>';
                        foreach($event->tags as $tag) { 
                            $tm_output .= "<div>".$tag."</div>"; 
                        } 
                        
        $tm_output .=  '</div>
                        <h2>Veranstaltungsort</h2>
                        <div>'. $event->ev_location_name .'</div>
                        <div>'. $event->event_location['city'] .' '. $event->event_location['zip'] .'</div>
                        <div>'. $event->event_location['street'] .' '. $event->event_location['house_number'] .'</div>
                       </div>'
        return $tm_output;
    }

?>