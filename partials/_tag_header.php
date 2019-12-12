<?php

    function tm_tag_header ( $globals ) {

        $tm_output = '<label class="mr-3 ml-1">' . __("Schlagwörter", "ticketmachine") . ':</label> <div class="card-meta-tag keyword">' . $globals->tag . ' <a class="ml-2" href="' . str_replace("tag=" . urldecode($globals->tag), "", $_SERVER[REQUEST_URI]) . '"><i class="fa fa-times"></i></a></div>';

        $a = rawurldecode($_SERVER[REQUEST_URI]);
        $b = $globals->tag;
        if (strpos($a, $b)){
            print_r($b . " was found in " . $a);
        }else{
            print_r($b . " was NOT found in " . $a);
        }
        return $tm_output;
    }

?>