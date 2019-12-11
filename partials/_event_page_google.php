

<?php
    function tm_event_page_google($event, $globals) {
        $tm_output .= '<script>
                            function myMap() {
                                console.log("google");
                                var mapProp= {
                                    center:new google.maps.LatLng(51.508742,-0.120850),
                                    zoom:5,
                                };
                                var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
                            }
                    </script>'
        $tm_output .= '<h1>My First Google Map</h1>
                       <div id="googleMap" style="width:100%;height:400px;"></div>';
        return $tm_output;
    }
?>