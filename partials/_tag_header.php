<?php

    function tm_tag_header ( $globals ) {

        $tm_output = '<div class="card-meta-tag keyword">' . $globals->tag . ' <a class="ml-2"><i class="fa fa-times"></i></a></div>';

        return $tm_output;
    }

?>