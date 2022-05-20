<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    function ticketmachine_pagination ( $meta, $params ) {
        ticketmachine_debug($meta);
        ticketmachine_debug($params);
        $query = $_GET;
        $ticketmachine_output = "";
        if($params['per_page'] < $meta['count_filtered']) {
            $ticketmachine_output .= '<div class="float-right btn-group">';

            if($meta['has_previous_page']) {
                $query['pg'] = $params['pg']-1;
                $query_result = http_build_query($query);
                $href = strtok($_SERVER["REQUEST_URI"], '?') . "?" . $query_result;
                $disabled = "";
            }else{
                $href = " href='" . $href . "'";
                $disabled = "disabled";
            }
            $ticketmachine_output .= "<button class='btn btn-secondary'" . $href . $disabled . "><i class='fas fa-angle-left'></i></button>";

            $ticketmachine_output .= "<";

            if($meta['has_next_page'] && $meta['next'] <  $meta['count_filtered']) {
                $query['pg'] = $params['pg']+1;
                $query_result = http_build_query($query);
                $href = strtok($_SERVER["REQUEST_URI"], '?') . "?" . $query_result;
                $disabled = "";
            }else{
                $href = " href='" . $href . "'";
                $disabled = "disabled";
            }
            $ticketmachine_output .= "<button class='btn btn-secondary'" . $href . $disabled . "><i class='fas fa-angle-right'></i></button>";

            $ticketmachine_output .= '</div>';
        }

        return $ticketmachine_output;
	}
?>