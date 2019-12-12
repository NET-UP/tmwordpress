<?php

    function tm_tag_header ( $globals ) {
        

        $a = htmlentities(urldecode($_SERVER[REQUEST_URI]));
        $b = "tag=" . $globals->tag;

        $tm_output = '<label class="mr-3 ml-1">' . __("Schlagwörter", "ticketmachine") . ':</label> 
                      <div class="card-meta-tag keyword">' . $globals->tag . ' 
                        <a class="ml-2" href="' . str_replace($b, "", $a) .'">
                            <i class="fa fa-times"></i>
                        </a>
                      </div>';

        if (strpos($a, $b)){
            print_r($b . " was found in " . $a);
            #print_r(mb_detect_encoding($a) . mb_detect_encoding($b));
        }else{
            print_r($b . " was NOT found in " . $a);
            #print_r(mb_detect_encoding($a) . mb_detect_encoding($b));
        }
        return $tm_output;
    }

?>