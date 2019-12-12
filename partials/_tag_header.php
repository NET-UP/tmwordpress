<?php

    function tm_tag_header ( $globals ) {
        

        $tm_output = '<label class="mr-3 ml-1">' . __("Schlagwörter", "ticketmachine") . ':</label> 
                      <div class="card-meta-tag keyword">' . $globals->tag . ' 
                        <a class="ml-2" href="' . $_SERVER[REQUEST_URI] . '">
                            <i class="fa fa-times"></i>
                        </a>
                      </div>';

        $a = $_SERVER[REQUEST_URI];
        $b = urlencode($globals->tag);
        #$b = rawurlencode("Fußball");
        if (strcmp($a, $b)){
            print_r($b . " was found in " . $a);
            #print_r(mb_detect_encoding($a) . mb_detect_encoding($b));
        }else{
            print_r($b . " was NOT found in " . $a);
            #print_r(mb_detect_encoding($a) . mb_detect_encoding($b));
        }
        return $tm_output;
    }

?>