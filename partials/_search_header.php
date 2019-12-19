<?php

    function tm_search_header ( $globals ) {

        $tm_output = "";
        $params = "?";

        if(isset($globals->search_query)){
            $params .= "&q=" . $globals->search_query;
        }

        if(isset($globals->tag)){
            $params .= "&tag=" . $globals->tag;
        }

        $tm_output .= "<form class='mb-3'>";

            if(isset($_GET['display'])){
                $tm_output .= "<input type='hidden' name='display' value='" . $_GET['display'] . "'/>";
            }

            $tm_output .= "<div class='form-row'>
                                <div class='col-12'>
                                    <div class='input-group'>
                                        <input name='q' class='form-control' placeholder='" . __("Suche nach Veranstaltungen") . "' value='" . $globals->search_query . "'/>
                                        <input type='hidden' name='tag' value='" . $globals->tag . "'/>
                                        <div class='input-group-append'>
                                            <button type='submit' alt='" . __("Suche absenden") . "' class='btn btn-secondary form-control'><i class='fas fa-search'></i></button>
                                        </div>
                                        <div class='col-12 d-sm-none mb-3'></div>";


                                        if(isset($_GET['display']) && $_GET['display'] == "calendar"){
                            $tm_output .= "<div class='btn-group ml-0 ml-sm-4'>
                                                <a href='#' aria-label='" . __("Zum vorigen Monat") . "' class='btn btn-secondary' id='calendar-prev'><i class='fas fa-angle-left'></i></a>
                                                <a href='#' class='btn btn-secondary' id='calendar-title'></a>
                                                <a href='#' aria-label='" . __("Zum nächsten Monat") . "' class='btn btn-secondary' id='calendar-next'><i class='fas fa-angle-right'></i></a>
                                            </div>";
                                        }

                                        if($globals->show_list && $globals->show_calendar){
                                            $tm_output .= "<div class='btn-group ml-sm-4'>";
                                            
                                                if($globals->show_list){
                                                    $tm_output .= "<a class='btn ";
                                                        if(!isset($_GET['display'])){ 
                                                            $tm_output .= "btn-primary active"; 
                                                        }else{
                                                            $tm_output .= "btn-secondary"; 
                                                        }
                                                    $tm_output .="' aria-label='" . __("Events als Liste anzeigen") . "' href='" . str_replace("?&", "?", $globals->current_url . $params) . "'><i class='fas fa-list'></i></a>";
                                                }

                                                if($globals->show_list){
                                                    $tm_output .= "<a class='btn ";
                                                        if(!isset($_GET['display']) == 'calendar'){ 
                                                            $tm_output .= "btn-primary active"; 
                                                        }else{
                                                            $tm_output .= "btn-secondary"; 
                                                        }
                                                    $tm_output .="'aria-label='" . __("Events als Kalender anzeigen") . "' href='" . str_replace("?&", "?", $globals->current_url . $params . "&display=calendar") . "' data-calendar-view='month'><i class='far fa-calendar-alt'></i></a>";
                                                }

                                            $tm_output .= "</div>";
                                        }

                $tm_output .= "		</div>
                                </div>
                            </div>
                        </form>";

        return $tm_output;
    }

?>