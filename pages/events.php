<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	function ticketmachine_display_events ( $atts ) {
		global $globals, $api;

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
		$events = ticketmachine_ticketmachine_tmapi_events($params)->result;
		
		if(isset($atts['display']) && $atts['display'] == "calendar" && $globals->show_calendar || $globals->show_calendar && !$globals->show_boxes && !$globals->show_list){
			$current_page = "calendar";
		}elseif(isset($atts['display']) && $atts['display'] == "list" && $globals->show_list || $globals->show_list && !$globals->show_boxes){
			$current_page = "list";
		}elseif($globals->show_boxes){
			$current_page = "boxes";
		}else{
			$current_page = "unknown";
		}
		
		$ticketmachine_output = "<div class='row ticketmachine_events_container'>";
		
		$ticketmachine_output .= "<div class='page-header col-12'>" . ticketmachine_search_header($globals, $current_page);
			if($globals->tag){
				$ticketmachine_output .= ticketmachine_tag_header($globals);
			}
		$ticketmachine_output .= "</div>";
		
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

			// Provide a global object to our JS file containing the AJAX url and security nonce
			wp_localize_script( 'calendar_JS_0', 'ajax_object',
				array(
					'ajax_url'      => admin_url('admin-ajax.php'),
					'ajax_nonce'    => wp_create_nonce('ajax_nonce'),
				)
			);

			add_action( 'wp_ajax_nopriv_ajax_validate_email', 'ticketmachine_calendar_ajax_events' );

			function ticketmachine_calendar_ajax_events() {
				$params = [ 
					"query" => sanitize_text_field($_POST['q']), 
					"sort" =>  sanitize_text_field($_POST['sort']), 
					"tag" =>  sanitize_text_field($_POST['tag']), 
					"approved" => 1 
				];
				$events = ticketmachine_ticketmachine_tmapi_events($params);

				$calendar = array();
				$i = 0;
						
				if(empty($events->result)) {	
					header('HTTP/1.0 400 Bad error');
				}else{

					foreach($events->result as $event) {
						$event = (object) $event;
						
						$params = [ "id" => $event->id ];
						
						if($event->state['sale_active'] > 0){
							$event->status_color = "#d4edda";
							$event->status_text_color = "#155724";
						}else{
							$event->status_color = "#f8d7da";
							$event->status_text_color = "#721c24";
						}

						//Override for free plugin
						$event->status_color = "#d4edda";
						$event->status_text_color = "#155724";
						
						$start = strtotime(get_date_from_gmt($event->ev_date)) * 1000;
						$end = strtotime(get_date_from_gmt($event->endtime)) * 1000;	

						if ($end < (strtotime("midnight", time())*1000)){
							$event->status_color = "#eeeeee";
							$event->status_text_color = "#999999";
						}elseif($i == 0) {
							$i = 1;
							$default_date = $start;
						}
						
						$calendar[] = array(
							'id' =>$event->id,
							'title' => $event->ev_name,
							'url' => "/" . $globals->event_slug . "/?id=" . $event->id,
							'start' => $start,
							'end' => $end,
							'class' => "event-success",
							'color' => $event->status_color,
							'textColor' => $event->status_text_color,
							'defaultDate' => $default_date
						);
					}
				}

				$calendarData = $calendar;
					
				return json_encode($calendarData);
			}
			
			$ticketmachine_output .= "
			<input type='hidden' id='ticketmachine_ev_url' value='" . plugins_url('', dirname(__FILE__) ) . "/event.php'></input>
			<div id='ticketmachine_cal_error' class='col-12 text-center mt-1' style='display:none;'>" . ticketmachine_alert(esc_html__("No events could be found", "ticketmachine"), "error") . "</div>
				<div class='col-12 mt-3'>
					<div class='row'>
						<div class='col-12'>
							<div id='ticketmachine_spinner'>
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
			
			if(empty($events)) {	
				$ticketmachine_output .= "<div class='col-12 text-center mt-1'>";
					$ticketmachine_output .= ticketmachine_alert(esc_html__("No events could be found", "ticketmachine"), "error");
				$ticketmachine_output .= "</div>";
				
			}else{

				//ToDo: Move to setting page
				$atts['show_image'] = 1;
				$atts['show_description'] = 1;
				$atts['show_date'] = 1;
				
				$ticketmachine_output .= '<div class="col-12">';
					$ticketmachine_output .= '<ul class="list-unstyled mx-0">';

					foreach($events as $event){
						$event = (object)$event;

						$ticketmachine_output .= '<li class="media mx-0 mt-2">';

						if(isset($atts['show_image']) && $atts['show_image'] > 0){
							$ticketmachine_output .= '<a class="mr-3 media-img" href="/' . $globals->event_slug . '?id=' . $event->id . '" style="background-image:url('. $event->event_img_url .')"></a>';
						}
											
							$ticketmachine_output .= '<div class="media-body">';
							$ticketmachine_output .= '<h5 class="mt-0 mb-1"><a href="/' . $globals->event_slug . '?id=' . $event->id . '">' . $event->ev_name . '</a></h5>';

							if(isset($atts['show_date']) && $atts['show_date'] > 0){
								$ticketmachine_output .= '
								<div class="card-meta-tag"><i class="far fa-calendar-alt tm-icon" aria-hidden="true"></i> &nbsp;'. ticketmachine_i18n_date("d.m.Y", $event->ev_date) .'</div> 
								<div class="card-meta-tag"><i class="far fa-clock tm-icon" aria-hidden="true"></i> &nbsp;'. ticketmachine_i18n_date("H:i", $event->ev_date) .'</div>';
							}
							
							if(isset($atts['show_description']) && $atts['show_description'] > 0){
								if(empty($atts['description_length'])){
									$atts['description_length'] = 15;
								}
								if(isset($atts['description_length'])){
									$ticketmachine_output .= '<div>' . wp_trim_words(wp_strip_all_tags($event->ev_description), $atts['description_length'], "...") . '</div>';
								}
							}

						$ticketmachine_output .= '</div>
									</li>';
					}

					$ticketmachine_output .= '</ul>';
				$ticketmachine_output .= '</div>';

			}

		}elseif($current_page == "boxes"){
			
			$prev = NULL;
			$i = 0;
			
			if(empty($events)) {	
				$ticketmachine_output .= "<div class='col-12 text-center mt-1'>";
					$ticketmachine_output .= ticketmachine_alert(esc_html__("No events could be found", "ticketmachine"), "error");
				$ticketmachine_output .= "</div>";
				
			}else{
				$ticketmachine_output .= "<div class='col-12 mt-2'></div>";

				foreach($events as $event) {
					$event = (object)$event;
					
					$curr = $event->ev_date;
					if(isset($globals->event_grouping) && $globals->event_grouping != "None") {
						if ($i == 0 || date( $globals->group_by , strtotime( $curr ) ) != date( $globals->group_by, strtotime( $prev ) ) ) {
							$ticketmachine_output .= "<div class='col-12 my-2'>
												<div class='d-flex'>
													<hr class='my-auto flex-grow-1'>
													<h3 class='px-4'>" . ticketmachine_i18n_date($globals->format_date, $event->ev_date) . "</h3>
													<hr class='my-auto flex-grow-1'>
												</div>
											</div>";
							$prev = $curr;
						}
					}
					
					$ticketmachine_output .= ticketmachine_event_list_item ( $event, $globals );
					
					$i++;
				}
			}

		}else{
			esc_html_e("List, Boxes & Calendar are deactivated", "ticketmachine");
		}
		return $ticketmachine_output;
		
	}

?>