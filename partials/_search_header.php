<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    function ticketmachine_search_header ( $tm_globals, $current_page ) {

        $ticketmachine_output = "";
        $params = "?";

        if(isset($tm_globals->search_query)){
            $params .= "q=" . $tm_globals->search_query;
        }

        if(!empty($tm_globals->tag)){
            $params .= "&tag=" . $tm_globals->tag;
        }

        $ticketmachine_output .= "<form class='mb-3'>";

            if(isset($_GET['display'])){
                $ticketmachine_output .= "<input type='hidden' name='display' value='" . esc_html(sanitize_text_field($_GET['display'])) . "'/>";
            }

            $ticketmachine_output .= "<div class='form-row'>
                                <div class='col-12'>
                                    <div class='input-group'>
                                        <input name='q' class='form-control' placeholder='" . esc_attr__("Search for events", "ticketmachine-event-manager") . "' value='" . esc_attr__($tm_globals->search_query) . "'/>
                                        <input type='hidden' name='tag' value='" . esc_html($tm_globals->tag) . "'/>
                                        <div class='input-group-append'>
                                            <button type='submit' alt='" . esc_attr__("Submit search", "ticketmachine-event-manager") . "' class='btn btn-secondary form-control'><i class='fas fa-search'></i></button>
                                        </div>
                                        <div class='col-12 d-sm-none mb-3'></div>";


                                        if(isset($_GET['display']) && sanitize_text_field($_GET['display']) == "calendar"){
                            $ticketmachine_output .= "<div class='btn-group ml-0 ml-sm-4'>
                                                <a href='#' aria-label='" . esc_attr__("To previous month", "ticketmachine-event-manager") . "' class='btn btn-secondary' id='calendar-prev'><i class='fas fa-angle-left'></i></a>
                                                <a href='#' class='btn btn-secondary' id='calendar-title'></a>
                                                <a href='#' aria-label='" . esc_attr__("To next month", "ticketmachine-event-manager") . "' class='btn btn-secondary' id='calendar-next'><i class='fas fa-angle-right'></i></a>
                                            </div>";
                                        }

                                        $ticketmachine_output .= "<div class='btn-group ml-sm-4'>";
                                        
                                            if($tm_globals->show_boxes){
                                                $ticketmachine_output .= "<a class='btn ";
                                                    if(isset($current_page) && $current_page == 'boxes'){ 
                                                        $ticketmachine_output .= "btn-primary active"; 
                                                    }else{
                                                        $ticketmachine_output .= "btn-secondary"; 
                                                    }
                                                $ticketmachine_output .="' title='" . esc_attr__("Show events as boxes", "ticketmachine-event-manager") . "' aria-label='" . esc_attr__("Show events as boxes", "ticketmachine-event-manager") . "' href='" . esc_url(str_replace("?&", "?", $tm_globals->current_url . $params)) . "'><i class='fas fa-th'></i></i></a>";
                                            }
                                        
                                            if($tm_globals->show_list){
                                                $ticketmachine_output .= "<a class='btn ";
                                                    if(isset($current_page) && $current_page == 'list'){ 
                                                        $ticketmachine_output .= "btn-primary active"; 
                                                    }else{
                                                        $ticketmachine_output .= "btn-secondary"; 
                                                    }
                                                $ticketmachine_output .="' title='" . esc_attr__("Show events as list", "ticketmachine-event-manager") . "' aria-label='" . esc_attr__("Show events as list", "ticketmachine-event-manager") . "' href='" . esc_url(str_replace("?&", "?", $tm_globals->current_url . $params . "&display=list")) . "'><i class='fas fa-list'></i></a>";
                                            }

                                            if($tm_globals->show_calendar){
                                                $ticketmachine_output .= "<a class='btn ";
                                                    if(isset($current_page) && $current_page == 'calendar'){ 
                                                        $ticketmachine_output .= "btn-primary active"; 
                                                    }else{
                                                        $ticketmachine_output .= "btn-secondary"; 
                                                    }
                                                $ticketmachine_output .="' title='" . esc_attr__("Show events in calendar", "ticketmachine-event-manager") . "' aria-label='" . esc_attr__("Show events in calendar", "ticketmachine-event-manager") . "' href='" . esc_url(str_replace("?&", "?", $tm_globals->current_url . $params . "&display=calendar")) . "' data-calendar-view='month'><i class='far fa-calendar-alt'></i></a>";
                                            }

                                        $ticketmachine_output .= "</div>";

                $ticketmachine_output .= "		</div>
                                </div>
                            </div>
                        </form>";

        return $ticketmachine_output;
    }

?>