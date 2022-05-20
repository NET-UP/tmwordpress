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
                $ticketmachine_output .= "<a class='btn btn-secondary " . $disabled . "'" . $href . ">" . $query['pg'] . "</a>";
            }

            $ticketmachine_output .= "<button class='btn btn-secondary' readonly>" .$params['pg'] . "</button>";

            if($meta['has_next_page'] && $meta['next'] <  $meta['count_filtered']) {
                $query['pg'] = $params['pg']+1;
                $query_result = http_build_query($query);
                $link = strtok($_SERVER["REQUEST_URI"], '?') . "?" . $query_result;
                $href = " href='" . $link . "'";
                $ticketmachine_output .= "<a class='btn btn-secondary " . $disabled . "'" . $href . ">" . $query['pg'] . "</a>";
            }

            $ticketmachine_output .= '</div>';
        }

        return $ticketmachine_output;
	}
?>