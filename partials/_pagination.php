<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    function ticketmachine_pagination ( $meta, $params ) {
        ticketmachine_debug($meta);
        $query = $_GET;
        $ticketmachine_output = '<div class="col-12"><div class="float-right btn-group">';
        if($meta['has_previous_page']) {
            $query['pg'] = $params['pg']-1;
            $query_result = http_build_query($query);
            $ticketmachine_output .= "<a class='btn btn-secondary' href='" . strtok($_SERVER["REQUEST_URI"], '?') . "?" . $query_result . "'><i class='fas fa-angle-left'></i></a>";
        }

        $ticketmachine_output .= "<";

        if($meta['has_next_page'] && $meta['next'] <  $meta['count_filtered']) {
            $query['pg'] = $params['pg']+1;
            $query_result = http_build_query($query);
            $ticketmachine_output .= "<a class='btn btn-secondary' href='" . strtok($_SERVER["REQUEST_URI"], '?') . "?" . $query_result . "'><i class='fas fa-angle-right'></i></a>";
        }
        
        $ticketmachine_output .= '</div></div>';

        return $ticketmachine_output;
	}
?>