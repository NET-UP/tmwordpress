<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    function ticketmachine_widget_event_calendar ( $atts, $isWidget ) {
        global $tm_globals, $api;
        $ticketmachine_output = "";

        unset($atts['page']);
        unset($atts['widget']);

        $atts['approved'] = 1;

        print_r($atts);
			
		//Calendar styles
        wp_enqueue_style( 'calendar_CSS_1' );
        wp_enqueue_style( 'calendar_CSS_2' );
        wp_enqueue_style( 'calendar_CSS_3' );
        wp_enqueue_style( 'calendar_CSS_4' );
        wp_enqueue_style( 'calendar_CSS_t' );

        if(!empty($isWidget) && $isWidget == 1){
            $ticketmachine_output .= "
                <div class='row'>
                    <div class='col-12'>
                        <div class='input-group'>
                            <div class='btn-group mb-3'>
                                <a href='#' aria-label='" . esc_attr__("To previous month", "ticketmachine-event-manager") . "' class='btn btn-secondary' id='calendar-prev'><i class='fas fa-angle-left'></i></a>
                                <a href='#' class='btn btn-secondary' id='calendar-title'></a>
                                <a href='#' aria-label='" . esc_attr__("To next month", "ticketmachine-event-manager") . "' class='btn btn-secondary' id='calendar-next'><i class='fas fa-angle-right'></i></a>
                            </div>
                        </div>
                    </div>
                </div>";
        }

        $ticketmachine_output .= "
            <input type='hidden' id='ticketmachine_ev_url' value='" . ticketmachine_tmapi_events($atts, "GET", FALSE, array(), 1) . "'></input>
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
        
            return $ticketmachine_output;
    }

?>