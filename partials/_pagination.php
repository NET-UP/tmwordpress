<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    function ticketmachine_pagination ( $meta, $params ) {
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
            
            $query_first = $query;
            $query_first['pg'] = 1;
            $query_result_first = http_build_query($query_first);
            $link_first = strtok($_SERVER["REQUEST_URI"], '?') . "?" . $query_result_first;
            $href_first = " href='" . $link_first . "'";

            $ticketmachine_output .= "<a class='btn btn-secondary " . $disabled . "'" . $href . "><i class='fas fa-angle-left'></i></a>";
            if(!$disabled && $params['pg'] > 2) $ticketmachine_output .= "<a class='btn btn-secondary'" . $href_first . ">1</a>";
            if(!$disabled && $params['pg'] > 3) $ticketmachine_output .= "<a class='btn btn-secondary'>...</a>";
            if(!$disabled) $ticketmachine_output .= "<a class='btn btn-secondary'" . $href . ">" .$params['pg']-1 . "</a>";

            $ticketmachine_output .= "<button class='btn btn-primary' readonly>" .$params['pg'] . "</button>";

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

            $query_last = $query;
            $query_last['pg'] =  ceil( $meta['count_filtered'] / $params['per_page'] );
            $query_result_last = http_build_query($query_last);
            $link_last = strtok($_SERVER["REQUEST_URI"], '?') . "?" . $query_result_last;
            $href_last = " href='" . $link_last . "'";
            
            if(!$disabled) $ticketmachine_output .= "<a class='btn btn-secondary " . $disabled . "'" . $href . ">" .$params['pg']+1 . "</a>";
            if(!$disabled && $params['pg'] < $query_last['pg']-2) $ticketmachine_output .= "<a class='btn btn-secondary'>...</a>";
            if(!$disabled && $params['pg'] < $query_last['pg']-1) $ticketmachine_output .= "<a class='btn btn-secondary'" . $href_last . ">".$query_last['pg']."</a>";
            $ticketmachine_output .= "<a class='btn btn-secondary " . $disabled . "'" . $href . "><i class='fas fa-angle-right'></i></a>";

            $ticketmachine_output .= '</div>';
        }

        return $ticketmachine_output;
	}
?>