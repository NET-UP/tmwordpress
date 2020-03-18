<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    function ticketmachine_error_page( $message, $actions = [], $title= "Oops!") {

        $ticketmachine_output = "<h1>" . esc_html__($title, "ticketmachine-event-manager") . "</h1>";

        $ticketmachine_output .= ticketmachine_alert($message, "error", $actions);

        return $ticketmachine_output;
    }

?>