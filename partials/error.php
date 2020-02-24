<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    function ticketmachine_alert( $message, $type, $actions = [] ) {

        if($type == "error") {
            $type = "danger";
        }

        $ticketmachine_output = "<div class='alert alert-" . $type . "'>" . __($message, "ticketmachine") . "</div>";

        if(!empty($actions)){
            foreach($actions as $text => $url){
                $ticketmachine_output .= "<a href='" . $url . "' class='btn btn-secondary'>" . __($text, "ticketmachine") . "</a>";
            }
        }

        return $ticketmachine_output;
    }

?>