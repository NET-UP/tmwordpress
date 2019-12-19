<?php

    function tm_alert( $message, $type ) {

        if($type == "error") {
            $type = "danger";
        }

        $tm_output = "<div class='alert alert-" . $type . "'>" . $message . "</div>";

        return $tm_output;
    }

?>