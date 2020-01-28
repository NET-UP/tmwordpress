<?php

    function tm_widget_event_calendar ( $atts ) {
		global $globals, $api;

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

        $tm_output .= "<div class='row'>
                            <div class='col-12'>
                                <div class='input-group'>
                                    <div class='btn-group mb-3'>
                                        <a href='#' aria-label='" . __("To previous month", "ticketmachine") . "' class='btn btn-secondary' id='calendar-prev'><i class='fas fa-angle-left'></i></a>
                                        <a href='#' class='btn btn-secondary' id='calendar-title'></a>
                                        <a href='#' aria-label='" . __("To next month", "ticketmachine") . "' class='btn btn-secondary' id='calendar-next'><i class='fas fa-angle-right'></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>";
        
        $tm_output .= "
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
                </div>";

        return $tm_output;
    }

?>