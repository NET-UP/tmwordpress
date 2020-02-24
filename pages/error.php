<?php

    function ticketmachine_error_page( $message, $actions = [], $title= "Oops!") {

        $ticketmachine_output = "<h1>" . __($title, "ticketmachine") . "</h1>";

        $ticketmachine_output .= ticketmachine_alert($message, "error", $actions);

        return $ticketmachine_output;
    }

?>