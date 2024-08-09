<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	function ticketmachine_display_events ( $atts ) {
		global $ticketmachine_globals, $ticketmachine_api;

		$params = array();
		if(isset($_GET['q'])){
			$params = ticketmachine_array_push_assoc($params, "query", sanitize_text_field($_GET['q']));
		}
		if(isset($_GET['sort'])){
			$params = ticketmachine_array_push_assoc($params, "sort", sanitize_text_field($_GET['sort']));
		}
		if(isset($_GET['tag'])){
			$params = ticketmachine_array_push_assoc($params, "tag", sanitize_text_field($_GET['tag']));
		}
		if(isset($_GET['pg'])){
			$params = ticketmachine_array_push_assoc($params, "pg", sanitize_text_field($_GET['pg']));
		}
		if(isset($atts["per_page"])) {
			$params = ticketmachine_array_push_assoc($params, "per_page", $atts["per_page"]);
		}
		$params = ticketmachine_array_push_assoc($params, "approved", 1);
		
		if(isset($atts['display']) && $atts['display'] == "calendar" && $ticketmachine_globals->show_calendar || $ticketmachine_globals->show_calendar && !$ticketmachine_globals->show_boxes && !$ticketmachine_globals->show_list){
			$current_page = "calendar";
		}elseif(isset($atts['display']) && $atts['display'] == "list" && $ticketmachine_globals->show_list || $ticketmachine_globals->show_list && !$ticketmachine_globals->show_boxes){
			$current_page = "list";
		}elseif($ticketmachine_globals->show_boxes){
			$current_page = "boxes";
		}else{
			$current_page = "unknown";
		}
		
		$ticketmachine_output = "<div class='row ticketmachine_events_container'>";
		
		$ticketmachine_output .= "<div class='page-header col-12'>" . ticketmachine_search_header($ticketmachine_globals, $current_page);
			if($ticketmachine_globals->tag){
				$ticketmachine_output .= ticketmachine_tag_header($ticketmachine_globals);
			}
		$ticketmachine_output .= "</div>";
		
		if($current_page == "calendar"){
			
			include_once plugin_dir_path( __FILE__ ) . "../widgets/event_calendar.php";
			$ticketmachine_output .= ticketmachine_widget_event_calendar( $params, 0 );
			
		}elseif($current_page == "list"){

			include_once plugin_dir_path( __FILE__ ) . "../widgets/event_list.php";
			$ticketmachine_output .= ticketmachine_widget_event_list ( $params, 0 );

		}elseif($current_page == "boxes"){
			
			include_once plugin_dir_path( __FILE__ ) . "../widgets/event_boxes.php";
			$ticketmachine_output .= ticketmachine_widget_event_boxes ( $params, 0 );

		}else{
			esc_html_e("List, Boxes & Calendar are deactivated", "ticketmachine-event-manager");
		}
		return $ticketmachine_output;
		
	}

?>