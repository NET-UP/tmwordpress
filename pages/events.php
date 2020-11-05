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
			
		//Calendar styles
		wp_enqueue_style( 'calendar_CSS_1' );
		wp_enqueue_style( 'calendar_CSS_2' );
		wp_enqueue_style( 'calendar_CSS_3' );
		wp_enqueue_style( 'calendar_CSS_4' );
		wp_enqueue_style( 'calendar_CSS_t' );
		//Calendar scripts
		wp_enqueue_script( 'calendar_JS_1' );
		wp_enqueue_script( 'calendar_JS_2' );
		wp_enqueue_script( 'calendar_JS_3' );
		wp_enqueue_script( 'calendar_JS_4' );
		wp_enqueue_script( 'calendar_JS_5' );
		wp_enqueue_script( 'calendar_JS_6' );
    
        wp_localize_script( 'ticketmachine-calendar-script', 'ticketmachine_calendar_data', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
		) );
		
        wp_enqueue_script( 'ticketmachine-calendar-script' );

			$ticketmachine_output .= "
			<input type='hidden' id='ticketmachine_ev_url' value='" . ticketmachine_tmapi_events($params, "GET", FALSE, array(), 1) . "'></input>
			<div id='ticketmachine_cal_error' class='col-12 text-center mt-1' style='display:none;'>" . ticketmachine_alert(esc_html__("No events could be found", "ticketmachine-event-manager"), "error") . "</div>
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
					$ticketmachine_output .= ticketmachine_alert(esc_html__("No events could be found", "ticketmachine-event-manager"), "error");
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
		
						if(empty($event->state['sale_active'])){
							$event->link = '/' . esc_html($tm_globals->event_slug) .'/?id=' . esc_html($event->id);
						}else{
							$event->link = esc_html($tm_globals->webshop_url) .'/events/unseated/select_unseated?event_id=' . esc_html($event->id);
						}

						$ticketmachine_output .= '<li class="media mx-0 mt-2 p-3">';

						if(isset($atts['show_image']) && $atts['show_image'] > 0){
							$ticketmachine_output .= '<a class="mr-3 media-img" href="' . $event->link . '" style="background-image:url('. $event->event_img_url .')"></a>';
						}
											
							$ticketmachine_output .= '<div class="media-body">';

							if(isset($atts['show_date']) && $atts['show_date'] > 0){
								$ticketmachine_output .= '
								<div class="card-meta-tag"><i class="far fa-calendar-alt tm-icon" aria-hidden="true"></i> &nbsp;'. ticketmachine_i18n_date("d.m.Y", $event->ev_date) .'</div> 
								<div class="card-meta-tag"><i class="far fa-clock tm-icon" aria-hidden="true"></i> &nbsp;'. ticketmachine_i18n_date("H:i", $event->ev_date) .'</div>';
							}

							$ticketmachine_output .= '<h5 class="mt-0 mb-1"><a class="tm-list-title" href="' . $event->link . '">' . $event->ev_name . '</a></h5>';
							
							if(isset($atts['show_description']) && $atts['show_description'] > 0){
								if(empty($atts['description_length'])){
									$atts['description_length'] = 15;
								}
								if(isset($atts['description_length'])){
									$ticketmachine_output .= '<div>' . esc_html(wp_trim_words(wp_strip_all_tags($event->ev_description), $atts['description_length'], "...")) . '</div>';
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