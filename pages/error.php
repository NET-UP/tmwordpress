<?php

    function tm_error_page( $message, $actions = [] ) {
        $tm_output = tm_alert($message, "error", $actions);

        return $tm_output;
    }

?>