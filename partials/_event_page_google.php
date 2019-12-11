<?php
    function tm_event_page_google($event, $globals) {
        $tm_output .= "<h1>My First Google Map</h1>
        <iframe width='100%' height='100%' id='mapcanvas' src='https://maps.google.com/maps?q=London,%20United%20Kingdom&Roadmap&z=10&ie=UTF8&iwloc=&output=embed' frameborder='0' scrolling='no' marginheight='0' marginwidth='0'>
            <div class='zxos8_gm'>
                <a href='https://www.reconditioned-engines.co.uk'>https://www.reconditioned-engines.co.uk</a>
            </div>
            <div style='overflow:hidden;'>
                <div id='gmap_canvas' style='height:100%;width:700px;'>
                </div>
                <div><small>Powered by <a href='https://www.embedgooglemap.co.uk'>Embed Google Map</a></small>
            </div>
        </iframe>";
        return $tm_output;
    }
?>

