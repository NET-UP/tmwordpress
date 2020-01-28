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
		$params = array_push_assoc($params, "approved", 1);
		$events = tmapi_events($params)->result;
		
		if(isset($atts['display']) && $atts['display'] == "calendar" && $globals->show_calendar || $globals->show_calendar && !$globals->show_boxes && !$globals->show_list){
			$current_page = "calendar";
		}elseif(isset($atts['display']) && $atts['display'] == "list" && $globals->show_list || $globals->show_list && !$globals->show_boxes){
			$current_page = "list";
		}elseif($globals->show_boxes){
			$current_page = "boxes";
		}else{
			$current_page = "unknown";
		}
		
		//echo "<pre>" . print_r($tm_json) . "</pre>";
		
		$tm_output = "<div class='row tm_events_container'>";
		
		$tm_output .= "<div class='page-header col-12'>" . tm_search_header($globals, $current_page);
			if($globals->tag){
				$tm_output .= tm_tag_header($globals);
			}
		$tm_output .= "</div>";
		
		if($current_page == "calendar"){
		
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
			
		}elseif($current_page == "list"){
			
			$tm_output .= '<ul class="list-unstyled">';

            foreach($events as $event){
                $event = (object)$event;

                $tm_output .= '<li class="media">';

                if($atts['show_image'] > 0){
                    $tm_output .= '<div class="mr-3 media-img" style="background-image:url('. $event->event_img_url .')"></div>';
                }
                                    
                    $tm_output .= '<div class="media-body">';
                    $tm_output .= '<h5 class="mt-0 mb-1"><a href="/event?id=' . $event->id . '">' . $event->ev_name . '</a></h5>';

                    if($atts['show_description'] > 0){
                        if(!$atts['description_length']){
                            $atts['description_length'] = 15;
                        }
                        $tm_output .= '<div>' . wp_trim_words(wp_strip_all_tags($event->ev_description), $atts['description_length'], "...") . '</div>';
                    }

                $tm_output .= '</div>
                            </li>';
            }

            if($atts['show_more'] > 0){
                $tm_output .= '<li class="media"><a href="/events">' . __("Show all events", "ticketmachine") . '</a></li>';
            }

			$tm_output .= '</ul>';

		}elseif($current_page == "boxes"){
			
			$prev = NULL;
			$i = 0;
			
			if(empty($events)) {	
				$tm_output .= "<div class='col-12 text-center mt-1'>";
					$tm_output .= tm_alert(__("No events could be found", "ticketmachine"), "error");
				$tm_output .= "</div>";
				
			}else{
				foreach($events as $event) {
					$event = (object)$event;
					
					$curr = $event->ev_date;
					if ($i == 0 || date( $globals->group_by , strtotime( $curr ) ) != date( $globals->group_by, strtotime( $prev ) ) ) {
						$tm_output .= "<div class='col-12 mt-2'><h5 class='line-header'><span>" . strftime( $globals->format_date, strtotime($event->ev_date) ) . "</span></h5></div>";
						$prev = $curr;
					}
					
					$tm_output .= tm_event_list_item ( $event, $globals );
					
					$i++;
				}
			}

		}else{
			echo __("List, Boxes & Calendar are deactivated", "ticketmachine");
		}
		return $tm_output;
		
	}

?>