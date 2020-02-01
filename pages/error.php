<?php

    function tm_error_page( $message, $actions = [], $title= "Oops!") {

        $tm_output = "<h1>" . __($title, "ticketmachine") . "</h1>";

        $tm_output .= tm_alert($message, "error", $actions);

        return $tm_output;
    }

?>