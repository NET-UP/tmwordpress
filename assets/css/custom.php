<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    global $wpdb;
    $ticketmachine_design = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_design LIMIT 0,1");
    $ticketmachine_design = $ticketmachine_design[0];
    $ticketmachine_config = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_config LIMIT 0,1");
    $ticketmachine_config = $ticketmachine_config[0];

    $ticketmachine_custom_css = "
        body .ticketmachine_page a:not(.btn):not(.fc-event):not(.tm-list-title) {
            color: ". $ticketmachine_design->link_text_color ." !important;
        }

        body .ticketmachine_page a:not(.btn):hover,  body .ticketmachine_page a:not(.btn):focus {
            color: ". $ticketmachine_design->link_text_color_hover .";
            text-decoration:underline !important;
        }
        body .ticketmachine_page .card-img-top:focus {
            border:2px solid ". $ticketmachine_design->button_primary_background_color ." !important;
        }
        
        body .ticketmachine_page #calendar .fc-event:focus {
            box-shadow: 0px 0px 1px 2px ". $ticketmachine_design->button_primary_background_color ." !important;
        }

        body .ticketmachine_page h1, 
        body .ticketmachine_page h2, 
        body .ticketmachine_page h3, 
        body .ticketmachine_page h4, 
        body .ticketmachine_page h5,
        body .ticketmachine_page h1, 
        body .ticketmachine_page h2, 
        body .ticketmachine_page h3, 
        body .ticketmachine_page h4, 
        body .ticketmachine_page h5 {
            color: ". $ticketmachine_design->box_header_color ." !important;
        }

        body .ticketmachine_page .card-meta-tag {
            color: ". $ticketmachine_design->box_meta_color ." !important;
        }

        body .ticketmachine_page .card-body,
        body .ticketmachine_page .card-text,
        body .ticketmachine_page .media-body,
        body .ticketmachine_page .fc-dayGrid-view .fc-week-number, 
        body .ticketmachine_page .fc-dayGrid-view .fc-day-number {
            color: ". $ticketmachine_design->box_text_color ." !important;
        }

        body .ticketmachine_page .card,
        body .ticketmachine_page .media,
        body .ticketmachine_page .fc-bg > table > tbody > tr,
        body .ticketmachine_page .fc-row .fc-bg {
            background: ". $ticketmachine_design->container_background_color ." !important;
        }

        body .ticketmachine_page .card,
        body .ticketmachine_page .media,
        body .ticketmachine_page hr,
        body .ticketmachine_page .table-bordered th, 
        body .ticketmachine_page .table-bordered td,
        body .ticketmachine_page #calendar .fc-row .fc-content-skeleton td {
            border-color: ". $ticketmachine_design->box_border_color ." !important;
        }

        body .ticketmachine_page .card-date {
            color: ". $ticketmachine_design->date_text_color ." !important;
        }

        body .ticketmachine_page .card-price {
            color: ". $ticketmachine_design->price_text_color ." !important;
        }

        body .ticketmachine_page .btn-primary,
        body .ticketmachine_page .btn-primary:visited,
        body .ticketmachine_page .btn-primary:not(:disabled):not(.disabled).active,
        body .ticketmachine_page .btn-primary:not(:disabled):not(.disabled):active,
        .show>.ticketmachine_page .btn-primary.dropdown-toggle,
        body .ticketmachine_page .btn-primary.disabled,
        body .ticketmachine_page .btn-primary:disabled {
            background-color: ". $ticketmachine_design->button_primary_background_color ." !important;
            color: ". $ticketmachine_design->button_primary_text_color ." !important;
            border-color: ". $ticketmachine_design->button_primary_border_color ." !important;
        }

        body .ticketmachine_page .btn-primary:hover,
        body .ticketmachine_page .btn-primary:active,
        body .ticketmachine_page .btn-primary:focus {
            background-color: ". $ticketmachine_design->button_primary_background_color_hover ." !important;
            color: ". $ticketmachine_design->button_primary_text_color_hover ." !important;
            border-color: ". $ticketmachine_design->button_primary_border_color_hover ." !important;
        }

        body .btn-secondary,
        body .btn-secondary:visited,
        body .btn-secondary:not(:disabled):not(.disabled).active,
        body .btn-secondary:not(:disabled):not(.disabled):active {
            background-color: ". $ticketmachine_design->button_secondary_background_color ." !important;
            color: ". $ticketmachine_design->button_secondary_text_color ." !important;
            border-color: ". $ticketmachine_design->button_secondary_border_color ." !important;
        }

        body .btn-secondary:hover,
        body .btn-secondary:active,
        body .btn-secondary:focus,
        .show>.btn-secondary.dropdown-toggle {
            background-color: ". $ticketmachine_design->button_secondary_background_color_hover ." !important;
            color: ". $ticketmachine_design->button_secondary_text_color_hover ." !important;
            border-color: ". $ticketmachine_design->button_secondary_border_color_hover ." !important;
        }

        body .spinner-border {
            border-color: ". $ticketmachine_design->button_primary_background_color ." !important;
            color: ". $ticketmachine_design->button_primary_background_color ." !important;
            border-right-color: transparent !important;
        }";

        if(!$ticketmachine_config->show_calendar_start_time) {
            $ticketmachine_custom_css .= "body .ticketmachine_page #calendar .fc-day-grid-event .fc-time {
                display: none;
            }";
        }
        
        return "<style class='ticketmachine_custom_css'>" . $ticketmachine_custom_css . "</style>";

?>