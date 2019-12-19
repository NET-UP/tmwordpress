<?php

    function tm_error_page( $message ) {
        $tm_output = tm_alert($message, "error");

        return $tm_output;
    }

?>