<?php

    // Function to easily debug arrays and objects
    function ticketmachine_debug($var){
        print_r("<pre>");
        print_r($var);
        print_r("</pre>");
    }

    // Function to easily add key value pairs to an array
	function ticketmachine_array_push_assoc($array, $key, $value){
		$array[$key] = $value;
		return $array;
	}

    
	// Internationalize DateTime objects
	function ticketmachine_i18n_date($format, $datetime) {
		$formatted_date = date_i18n($format, strtotime(get_date_from_gmt($datetime)) );
		return $formatted_date;
	}

	// Uninternationalize DateTime objects
	function ticketmachine_i18n_reverse_date($datetime) {
		$formatted_date = gmdate(DATE_ISO8601, strtotime(date_i18n(DATE_ISO8601, strtotime($datetime))));
		return $formatted_date;
	}

?>