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
                $link = strtok($_SERVER["REQUEST_URI"], '?') . "?" . $query_result;
                $href = " href='" . $link . "'";
                $disabled = "";
            }else{
                $href = "";
                $disabled = "disabled";
            }
            $ticketmachine_output .= "<a class='btn btn-secondary " . $disabled . "'" . $href . "><i class='fas fa-angle-left'></i></a>";

            $ticketmachine_output .= "<button class='btn btn-secondary disabled' disabled>" .$params['pg'] . "</button>";

            if($meta['has_next_page'] && $meta['next'] <  $meta['count_filtered']) {
                $query['pg'] = $params['pg']+1;
                $query_result = http_build_query($query);
                $link = strtok($_SERVER["REQUEST_URI"], '?') . "?" . $query_result;
                $href = " href='" . $link . "'";
                $disabled = "";
            }else{
                $href = "";
                $disabled = "disabled";
            }
            $ticketmachine_output .= "<a class='btn btn-secondary " . $disabled . "'" . $href . "><i class='fas fa-angle-right'></i></a>";

            $ticketmachine_output .= '</div>';
        }

        return $ticketmachine_output;
	}
?>