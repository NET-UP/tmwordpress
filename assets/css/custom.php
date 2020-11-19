<?php
    header("Content-type: text/css; charset: UTF-8");

    global $wpdb;
    $ticketmachine_design = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_design LIMIT 0,1");
    $ticketmachine_design = $ticketmachine_design[0];

    $ticketmachine_custom_css = "
        body .ticketmachine_page a:not(.btn):not(.fc-event):not(.tm-list-title) {
            color: ". $ticketmachine_design->link_text_color ." !important;
        }

        body .ticketmachine_page a:not(.btn):hover,  body .ticketmachine_page a:not(.btn):focus {
            color: ". $ticketmachine_design->link_text_color_hover .";
            text-decoration:underline !important;
        }
        .card-img-top:focus {
            border:2px solid ". $ticketmachine_design->button_primary_background_color ." !important;
        }

        
        .ticketmachine_page #calendar .fc-event:focus {
            box-shadow: 0px 0px 1px 2px ". $ticketmachine_design->button_primary_background_color ." !important;
        }

        .ticketmachine_page .card {
            background: ". $ticketmachine_design->container_background_color ." !important;
        }

        .ticketmachine_page .card-date {
            color: ". $ticketmachine_design->date_text_color ." !important;
        }

        .ticketmachine_page .card-price {
            color: ". $ticketmachine_design->price_text_color ." !important;
        }

        .ticketmachine_page .btn-primary,
        .ticketmachine_page .btn-primary:visited,
        .ticketmachine_page .btn-primary:not(:disabled):not(.disabled).active,
        .ticketmachine_page .btn-primary:not(:disabled):not(.disabled):active,
        .show>.ticketmachine_page .btn-primary.dropdown-toggle,
        .ticketmachine_page .btn-primary.disabled,
        .ticketmachine_page .btn-primary:disabled {
            background-color: ". $ticketmachine_design->button_primary_background_color ." !important;
            color: ". $ticketmachine_design->button_primary_text_color ." !important;
            border-color: ". $ticketmachine_design->button_primary_border_color ." !important;
        }

        .ticketmachine_page .btn-primary:hover,
        .ticketmachine_page .btn-primary:active,
        .ticketmachine_page .btn-primary:focus {
            background-color: ". $ticketmachine_design->button_primary_background_color_hover ." !important;
            color: ". $ticketmachine_design->button_primary_text_color_hover ." !important;
            border-color: ". $ticketmachine_design->button_primary_border_color_hover ." !important;
        }

        .btn-secondary,
        .btn-secondary:visited,
        .btn-secondary:not(:disabled):not(.disabled).active,
        .btn-secondary:not(:disabled):not(.disabled):active {
            background-color: ". $ticketmachine_design->button_secondary_background_color ." !important;
            color: ". $ticketmachine_design->button_secondary_text_color ." !important;
            border-color: ". $ticketmachine_design->button_secondary_border_color ." !important;
        }

        .btn-secondary:hover,
        .btn-secondary:active,
        .btn-secondary:focus,
        .show>.btn-secondary.dropdown-toggle {
            background-color: ". $ticketmachine_design->button_secondary_background_color_hover ." !important;
            color: ". $ticketmachine_design->button_secondary_text_color_hover ." !important;
            border-color: ". $ticketmachine_design->button_secondary_border_color_hover ." !important;
        }

        .spinner-border {
            border-color: ". $ticketmachine_design->button_primary_background_color ." !important;
            color: ". $ticketmachine_design->button_primary_background_color ." !important;
            border-right-color: transparent !important;
        }";
    
        return $ticketmachine_custom_css;
?>