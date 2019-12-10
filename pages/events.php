<?php

	function tm_display_events ( $atts, $globals, $api ) {
		$params = [ "query" => $_GET['q'], "query" => $_GET['s'] ];
		$events = tmapi_events($params);
		
		//echo "<pre>" . print_r($tm_json) . "</pre>";
		
		$tm_output = "<div class='row tm_events_container'>";
		
		if($atts['display'] == "calendar" && $globals->show_calendar || $globals->show_calendar && !$globals->show_list){
		
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
				<div class='page-header col-12'>
					<form>
						<input type='hidden' name='display' value='calendar'/>
						<div class='form-row'>
							<div class='col-12'>
								<div class='input-group'>
									<input name='q' class='form-control' placeholder='" . __("Suche nach Veranstaltungen") . "' value='" . $globals->search_query . "'/>
									<div class='input-group-append'>
										<button type='submit' alt='" . __("Suche absenden") . "' class='btn btn-secondary form-control'><i class='fas fa-search'></i></button>
									</div>
									<div class='col-12 d-sm-none mb-3'></div>
									<div class='btn-group ml-0 ml-sm-4'>
										<a href='#' aria-label='" . __("Zum vorigen Monat") . "' class='btn btn-secondary' id='calendar-prev'><i class='fas fa-angle-left'></i></a>
										<a href='#' class='btn btn-secondary' id='calendar-title'></a>
										<a href='#' aria-label='" . __("Zum nächsten Monat") . "' class='btn btn-secondary' id='calendar-next'><i class='fas fa-angle-right'></i></a>
									</div>";

									if($globals->show_list && $globals->show_calendar){
										$tm_output .= 	"<div class='btn-group ml-4'>
															<a class='btn btn-secondary' aria-label='" . __("Events als Liste anzeigen") . "' href='" . $globals->current_url . "?q=" . $globals->search_query . "'><i class='fas fa-list'></i></a>
															<a class='btn btn-primary active' aria-label='" . __("Events als Kalender anzeigen") . "' href='" . $globals->current_url . "?display=calendar&q=" . $globals->search_query . "' data-calendar-view='month'><i class='far fa-calendar-alt'></i></a>
														</div>";
									}

			$tm_output .= "		</div>
							</div>
						</div>
					</form>
				</div>
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
			
			$tm_output .= "
				<div class='page-header col-12'>
					<form>
						<div class='form-row'>
							<div class='col-12'>
								<div class='input-group'>
									<input name='q' class='form-control' placeholder='" . __("Suche nach Veranstaltungen") . "' value='" . $globals->search_query . "'/>
									<div class='input-group-append'>
										<button type='submit' aria-label='" . __("Suche absenden") . "' class='btn btn-secondary form-control'><i class='fas fa-search'></i></button>
									</div>";

									if($globals->show_list && $globals->show_calendar){
										$tm_output .= 	"<div class='btn-group ml-4'>
															<a class='btn btn-primary active' aria-label='" . __("Events als Liste anzeigen") . "' href='" . $globals->current_url . "?q=" . $globals->search_query . "'><i class='fas fa-list'></i></a>
															<a class='btn btn-secondary' aria-label='" . __("Events als Kalender anzeigen") . "' href='" . $globals->current_url . "?display=calendar&q=" . $globals->search_query . "' data-calendar-view='month'><i class='far fa-calendar-alt'></i></a>
														</div>";
									}

			$tm_output .= "		</div>
							</div>
						</div>
					</form>
				</div>";
		
			$prev = NULL;
			$i = 0;
			
			if(empty($events)) {	
				$tm_output .= "<div class='col-12 text-center mt-2 p-4'>";
				$tm_output .= __("Es konnten keine Veranstaltungen gefunden werden", "ticketmachine");
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