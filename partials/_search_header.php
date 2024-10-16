<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    if(!function_exists("ticketmachine_search_header")){
        function ticketmachine_search_header ( $ticketmachine_globals, $current_page ) {

            $ticketmachine_output = "";
            $params = "?";
    
            if(isset($ticketmachine_globals->search_query)){
                $params .= "q=" . $ticketmachine_globals->search_query;
            }
    
            if(!empty($ticketmachine_globals->tag)){
                $params .= "&tag=" . $ticketmachine_globals->tag;
            }
    
            $ticketmachine_output .= "<form class='mb-3'>";
    
                if(isset($_GET['display'])){
                    $ticketmachine_output .= "<input type='hidden' name='display' value='" . esc_html(sanitize_text_field($_GET['display'])) . "'/>";
                }
    
                $ticketmachine_output .= "<div class='form-row'>
                                    <div class='col-12'>
                                        <div class='d-flex flex-column flex-md-row gap-2 gap-md-4'>
                                            <div class='input-group'>
                                                <input name='q' class='form-control' placeholder='" . esc_attr__("Search for events", "ticketmachine-event-manager") . "' value='" . esc_attr__($ticketmachine_globals->search_query) . "'/>
                                                <input type='hidden' name='tag' value='" . esc_html($ticketmachine_globals->tag) . "'/>
                                                <button type='submit' alt='" . esc_attr__("Submit search", "ticketmachine-event-manager") . "' class='btn btn-secondary'><i class='fas fa-search'></i></button>
                                            </div>";
    
    
                                            if(isset($_GET['display']) && sanitize_text_field($_GET['display']) == "calendar"){
                                $ticketmachine_output .= "<div class='btn-group' style='min-width: 200px'>
                                                    <a href='#' aria-label='" . esc_attr__("To previous month", "ticketmachine-event-manager") . "' class='btn btn-secondary' id='calendar-prev'><i class='fas fa-angle-left'></i></a>
                                                    <a href='#' class='btn btn-secondary' id='calendar-title'></a>
                                                    <a href='#' aria-label='" . esc_attr__("To next month", "ticketmachine-event-manager") . "' class='btn btn-secondary' id='calendar-next'><i class='fas fa-angle-right'></i></a>
                                                </div>";
                                            }
    
                                            $ticketmachine_output .= "<div class='btn-group'>";
                                            
                                                if($ticketmachine_globals->show_boxes){
                                                    $ticketmachine_output .= "<a class='btn ";
                                                        if(isset($current_page) && $current_page == 'boxes'){ 
                                                            $ticketmachine_output .= "btn-primary active"; 
                                                        }else{
                                                            $ticketmachine_output .= "btn-secondary"; 
                                                        }
                                                    $ticketmachine_output .="' title='" . esc_attr__("Show events as boxes", "ticketmachine-event-manager") . "' aria-label='" . esc_attr__("Show events as boxes", "ticketmachine-event-manager") . "' href='" . esc_url(str_replace("?&", "?", $ticketmachine_globals->current_url . $params)) . "'><i class='fas fa-th'></i></i></a>";
                                                }
                                            
                                                if($ticketmachine_globals->show_list){
                                                    $ticketmachine_output .= "<a class='btn ";
                                                        if(isset($current_page) && $current_page == 'list'){ 
                                                            $ticketmachine_output .= "btn-primary active"; 
                                                        }else{
                                                            $ticketmachine_output .= "btn-secondary"; 
                                                        }
                                                    $ticketmachine_output .="' title='" . esc_attr__("Show events as list", "ticketmachine-event-manager") . "' aria-label='" . esc_attr__("Show events as list", "ticketmachine-event-manager") . "' href='" . esc_url(str_replace("?&", "?", $ticketmachine_globals->current_url . $params . "&display=list")) . "'><i class='fas fa-list'></i></a>";
                                                }
    
                                                if($ticketmachine_globals->show_calendar){
                                                    $ticketmachine_output .= "<a class='btn ";
                                                        if(isset($current_page) && $current_page == 'calendar'){ 
                                                            $ticketmachine_output .= "btn-primary active"; 
                                                        }else{
                                                            $ticketmachine_output .= "btn-secondary"; 
                                                        }
                                                    $ticketmachine_output .="' title='" . esc_attr__("Show events in calendar", "ticketmachine-event-manager") . "' aria-label='" . esc_attr__("Show events in calendar", "ticketmachine-event-manager") . "' href='" . esc_url(str_replace("?&", "?", $ticketmachine_globals->current_url . $params . "&display=calendar")) . "' data-calendar-view='month'><i class='far fa-calendar-alt'></i></a>";
                                                }
    
                    $ticketmachine_output .= "</div>
                                        </div>
                                    </div>
                                </div>
                            </form>";
    
            return $ticketmachine_output;
        }
    }
?>