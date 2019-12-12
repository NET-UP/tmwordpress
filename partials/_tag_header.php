<?php

    function tm_tag_header ( $globals ) {

        $tm_output = '<label class="mr-3">' . __("Schlagwörter", "ticketmachine") . ':</label> <div class="card-meta-tag keyword">' . $globals->tag . ' <a class="ml-2" href="' . str_replace("tag=" . $globals->tag, "", $_SERVER[REQUEST_URI]) . '"><i class="fa fa-times"></i></a></div>';

        return $tm_output;
    }

?>