<?php

	function tm_display_events ( $atts ) {
		global $globals, $api;

		$params = array();
		if(isset($_GET['q'])){
			$params = array_push_assoc($params, "query", $_GET['q']);
		}
		if(isset($_GET['sort'])){
			$params = array_push_assoc($params, "sort", $_GET['sort']);
		}
		if(isset($_GET['tag'])){
			$params = array_push_assoc($params, "tag", $_GET['tag']);
		}
		$events = tmapi_events($params)->result;
		
		//echo "<pre>" . print_r($tm_json) . "</pre>";
		
		$tm_output = "<div class='row tm_events_container'>";
		
		$tm_output .= "<div class='page-header col-12'>" . tm_search_header($globals);
			if($globals->tag){
				$tm_output .= tm_tag_header($globals);
			}
		$tm_output .= "</div>";
		
		if(isset($atts['display']) && $atts['display'] == "calendar" && $globals->show_calendar || $globals->show_calendar && !$globals->show_list){
		
			//Calendar Packages
			wp_enqueue_style( 'calendar_CSS_1', plugins_url('../assets/packages/core/main.css', __FILE__ ) );
			wp_enqueue_style( 'calendar_CSS_2', plugins_url('../assets/packages/daygrid/main.css', __FILE__ ) );
			wp_enqueue_style( 'calendar_CSS_3', plugins_url('../assets/packages/timegrid/main.css', __FILE__ ) );
			wp_enqueue_style( 'calendar_CSS_4', plugins_url('../assets/packages/list/main.css', __FILE__ ) );
			wp_enqueue_style( 'calendar_CSS_5', plugins_url('../assets/packages/bootstrap/main.css', __FILE__ ) );
			
			wp_enqueue_script( 'calendar_JS_1', plugins_url('../assets/packages/core/main.js', __FILE__ ) );
			wp_enqueue_script( 'calendar_JS_2', plugins_url('../assets/packages/interaction/main.js', __FILE__ ) );
			wp_enqueue_script( 'calendar_JS_3', plugins_url('../assets/packages/daygrid/main.js', __FILE__ ) );
			wp_enqueue_script( 'calendar_JS_4', plugins_url('../assets/packages/timegrid/main.js', __FILE__ ) );
			wp_enqueue_script( 'calendar_JS_5', plugins_url('../assets/packages/list/main.js', __FILE__ ) );
			wp_enqueue_script( 'calendar_JS_6', plugins_url('../assets/packages/bootstrap/main.js', __FILE__ ) );
			
			//Underscore
			wp_enqueue_script( 'underscore_JS', plugins_url('../assets/js/ext/underscore.js', __FILE__ ) );
			
			//Calendar Config
			wp_enqueue_script( 'calendar_JS_0', plugins_url('../assets/js/calendar.js', __FILE__ ) );
			
			$tm_output .= "
				<div class='col-12 mt-3'>
					<div class='row'>
						<div class='col-12'>
							<div id='tm_spinner'>
								<div class='text-center'>
									<div class='spinner-border text-primary' role='status'>
										<span class='sr-only'>Laden...</span>
									</div>
								</div>
							</div>
						</div>
						<div id='calendar' class='col-12'></div>
					</div>
				</div>";
			
		}elseif($globals->show_list){
		
			$prev = NULL;
			$i = 0;
			
			if(empty($events)) {	
				$tm_output .= "<div class='col-12 text-center mt-1'>";
					$tm_output .= tm_alert(__("Es konnten keine Veranstaltungen gefunden werden", "ticketmachine"), "error");
				$tm_output .= "</div>";
				
			}else{
				foreach($events as $event) {
					$event = (object)$event;
					
					$curr = $event->ev_date;
					if ($i == 0 || date( $globals->group_by , strtotime( $curr ) ) != date( $globals->group_by, strtotime( $prev ) ) ) {
						$tm_output .= "<div class='col-12 mt-2'><h5 class='line-header'><span>" . date( $globals->format_date, strtotime($event->ev_date) ) . "</span></h5></div>";
						$prev = $curr;
					}
					
					$tm_output .= tm_event_list_item ( $event, $globals );
					
					$i++;
				}
			}
		}else{
			echo __("Liste & Kalender sind deaktiviert", "ticketmachine");
		}
		return $tm_output;
		
	}

?>