<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	function ticketmachine_display_events ( $atts ) {
		global $tm_globals, $api;

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
		$params = ticketmachine_array_push_assoc($params, "approved", 1);
		$events = ticketmachine_tmapi_events($params)->result;
		
		if(isset($atts['display']) && $atts['display'] == "calendar" && $tm_globals->show_calendar || $tm_globals->show_calendar && !$tm_globals->show_boxes && !$tm_globals->show_list){
			$current_page = "calendar";
		}elseif(isset($atts['display']) && $atts['display'] == "list" && $tm_globals->show_list || $tm_globals->show_list && !$tm_globals->show_boxes){
			$current_page = "list";
		}elseif($tm_globals->show_boxes){
			$current_page = "boxes";
		}else{
			$current_page = "unknown";
		}
		
		$ticketmachine_output = "<div class='row ticketmachine_events_container'>";
		
		$ticketmachine_output .= "<div class='page-header col-12'>" . ticketmachine_search_header($tm_globals, $current_page);
			if($tm_globals->tag){
				$ticketmachine_output .= ticketmachine_tag_header($tm_globals);
			}
		$ticketmachine_output .= "</div>";
		
		if($current_page == "calendar"){
			
			include plugin_dir_path( __FILE__ ) . "../widgets/event_calendar.php";
			$ticketmachine_output .= ticketmachine_widget_event_calendar( $params, 0 );
			
		}elseif($current_page == "list"){

			include plugin_dir_path( __FILE__ ) . "../widgets/event_list.php";
			$ticketmachine_output .= ticketmachine_widget_event_list ( $params, 0 );

		}elseif($current_page == "boxes"){
			
			$prev = NULL;
			$i = 0;
			
			if(empty($events)) {	
				$ticketmachine_output .= "<div class='col-12 text-center mt-1'>";
					$ticketmachine_output .= ticketmachine_alert(esc_html__("No events could be found", "ticketmachine-event-manager"), "error");
				$ticketmachine_output .= "</div>";
				
			}else{
				$ticketmachine_output .= "<div class='col-12 mt-2'></div>";

				foreach($events as $event) {
					$event = (object)$event;
					
					$curr = $event->ev_date;
					if(isset($tm_globals->event_grouping) && $tm_globals->event_grouping != "None") {
						if ($i == 0 || date( $tm_globals->group_by , strtotime( $curr ) ) != date( $tm_globals->group_by, strtotime( $prev ) ) ) {
							$ticketmachine_output .= "<div class='col-12 my-2'>
												<div class='d-flex'>
													<hr class='my-auto flex-grow-1'>
													<h3 class='px-4'>" . ticketmachine_i18n_date($tm_globals->format_date, $event->ev_date) . "</h3>
													<hr class='my-auto flex-grow-1'>
												</div>
											</div>";
							$prev = $curr;
						}
					}
					
					$ticketmachine_output .= ticketmachine_event_list_item ( $event, $tm_globals );
					
					$i++;
				}
			}

		}else{
			esc_html_e("List, Boxes & Calendar are deactivated", "ticketmachine-event-manager");
		}
		return $ticketmachine_output;
		
	}

?>