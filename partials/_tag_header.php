<?php

    function tm_tag_header ( $globals ) {

        $tm_output = '<label class="mr-3 ml-1">' . __("Schlagwörter", "ticketmachine") . ':</label> <div class="card-meta-tag keyword">' . $globals->tag . ' <a class="ml-2" href="' . str_replace("tag=" . urldecode($globals->tag), "", $_SERVER[REQUEST_URI]) . '"><i class="fa fa-times"></i></a></div>';

        print_r(urldecode($_SERVER[REQUEST_URI]) ." - tag=" . $globals->tag);#
        if (strpos(urldecode($_SERVER[REQUEST_URI]), "tag=" . $globals->tag)){
            print_r("billie big gay");
        }
        return $tm_output;
    }

?>