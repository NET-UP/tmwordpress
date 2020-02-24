<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    function ticketmachine_search_header ( $globals, $current_page ) {

        $ticketmachine_output = "";
        $params = "?";

        if(isset($globals->search_query)){
            $params .= "&q=" . $globals->search_query;
        }

        if(isset($globals->tag)){
            $params .= "&tag=" . $globals->tag;
        }

        $ticketmachine_output .= "<form class='mb-3'>";

            if(isset($_GET['display'])){
                $ticketmachine_output .= "<input type='hidden' name='display' value='" . $_GET['display'] . "'/>";
            }

            $ticketmachine_output .= "<div class='form-row'>
                                <div class='col-12'>
                                    <div class='input-group'>
                                        <input name='q' class='form-control' placeholder='" . __("Search for events", "ticketmachine") . "' value='" . $globals->search_query . "'/>
                                        <input type='hidden' name='tag' value='" . $globals->tag . "'/>
                                        <div class='input-group-append'>
                                            <button type='submit' alt='" . __("Submit search", "ticketmachine") . "' class='btn btn-secondary form-control'><i class='fas fa-search'></i></button>
                                        </div>
                                        <div class='col-12 d-sm-none mb-3'></div>";


                                        if(isset($_GET['display']) && $_GET['display'] == "calendar"){
                            $ticketmachine_output .= "<div class='btn-group ml-0 ml-sm-4'>
                                                <a href='#' aria-label='" . __("To previous month", "ticketmachine") . "' class='btn btn-secondary' id='calendar-prev'><i class='fas fa-angle-left'></i></a>
                                                <a href='#' class='btn btn-secondary' id='calendar-title'></a>
                                                <a href='#' aria-label='" . __("To next month", "ticketmachine") . "' class='btn btn-secondary' id='calendar-next'><i class='fas fa-angle-right'></i></a>
                                            </div>";
                                        }

                                        $ticketmachine_output .= "<div class='btn-group ml-sm-4'>";
                                        
                                            if($globals->show_boxes){
                                                $ticketmachine_output .= "<a class='btn ";
                                                    if(isset($current_page) && $current_page == 'boxes'){ 
                                                        $ticketmachine_output .= "btn-primary active"; 
                                                    }else{
                                                        $ticketmachine_output .= "btn-secondary"; 
                                                    }
                                                $ticketmachine_output .="' title='" . __("Show events as boxes", "ticketmachine") . "' aria-label='" . __("Show events as boxes", "ticketmachine") . "' href='" . str_replace("?&", "?", $globals->current_url . $params) . "'><i class='fas fa-th'></i></i></a>";
                                            }
                                        
                                            if($globals->show_list){
                                                $ticketmachine_output .= "<a class='btn ";
                                                    if(isset($current_page) && $current_page == 'list'){ 
                                                        $ticketmachine_output .= "btn-primary active"; 
                                                    }else{
                                                        $ticketmachine_output .= "btn-secondary"; 
                                                    }
                                                $ticketmachine_output .="' title='" . __("Show events as list", "ticketmachine") . "' aria-label='" . __("Show events as list", "ticketmachine") . "' href='" . str_replace("?&", "?", $globals->current_url . $params . "&display=list") . "'><i class='fas fa-list'></i></a>";
                                            }

                                            if($globals->show_calendar){
                                                $ticketmachine_output .= "<a class='btn ";
                                                    if(isset($current_page) && $current_page == 'calendar'){ 
                                                        $ticketmachine_output .= "btn-primary active"; 
                                                    }else{
                                                        $ticketmachine_output .= "btn-secondary"; 
                                                    }
                                                $ticketmachine_output .="' title='" . __("Show events in calendar", "ticketmachine") . "' aria-label='" . __("Show events in calendar", "ticketmachine") . "' href='" . str_replace("?&", "?", $globals->current_url . $params . "&display=calendar") . "' data-calendar-view='month'><i class='far fa-calendar-alt'></i></a>";
                                            }

                                        $ticketmachine_output .= "</div>";

                $ticketmachine_output .= "		</div>
                                </div>
                            </div>
                        </form>";

        return $ticketmachine_output;
    }

?>