<?php

    function tm_alert( $message, $type, $actions = [] ) {

        if($type == "error") {
            $type = "danger";
        }

        $tm_output = "<div class='alert alert-" . $type . "'>" . $message . "</div>";

        if(!empty($actions)){
            foreach($actions as $text => $url){
                $tm_output .= "<a href='" . $url . "' class='btn'>" . $text . "</a>";
            }
        }

        return $tm_output;
    }

?>