<?php

    function tm_tag_header ( $globals ) {

        $tm_output = '<label class="mr-3 ml-1">' . __("Schlagwörter", "ticketmachine") . ':</label> <div class="card-meta-tag keyword">' . $globals->tag . ' <a class="ml-2" href="' . str_replace("tag=" . urldecode($globals->tag), "", $_SERVER[REQUEST_URI]) . '"><i class="fa fa-times"></i></a></div>';

        print_r(str_replace("tag=" . urlencode($globals->tag), "", $_SERVER[REQUEST_URI]));

        print_r($_SERVER[REQUEST_URI]);
        return $tm_output;
    }

?>