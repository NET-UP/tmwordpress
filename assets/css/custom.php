<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    include_once("{$_SERVER['DOCUMENT_ROOT']}/wp-load.php"); 
    header("Content-type: text/css; charset: UTF-8");
    global $wpdb;
    $ticketmachine_design = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_design LIMIT 0,1");
    $ticketmachine_design = $ticketmachine_design[0];
?>
    body .ticketmachine_page a:not(.btn) {
        color: <?php echo $ticketmachine_design->link_text_color; ?>;
    }

    body .ticketmachine_page a:not(.btn):hover,  body .ticketmachine_page a:not(.btn):focus {
        color: <?php echo $ticketmachine_design->link_text_color_hover; ?>;
        text-decoration:underline;
    }
    .card-img-top:focus {
        border:2px solid <?php echo $ticketmachine_design->button_primary_background_color; ?>;
    }

    
    .ticketmachine_page #calendar .fc-event:focus {
        box-shadow: 0px 0px 1px 2px <?php echo $ticketmachine_design->button_primary_background_color; ?>;
    }

    .ticketmachine_page .card {
        background: <?php echo $ticketmachine_design->container_background_color; ?>;
    }

    .ticketmachine_page .card-date {
        color: <?php echo $ticketmachine_design->date_text_color; ?>;
    }

    .ticketmachine_page .card-price {
        color: <?php echo $ticketmachine_design->price_text_color; ?>;
    }

    .ticketmachine_page .btn-primary,
    .ticketmachine_page .btn-primary:visited,
    .ticketmachine_page .btn-primary:not(:disabled):not(.disabled).active,
    .ticketmachine_page .btn-primary:not(:disabled):not(.disabled):active,
    .show>.ticketmachine_page .btn-primary.dropdown-toggle,
    .ticketmachine_page .btn-primary.disabled,
    .ticketmachine_page .btn-primary:disabled {
        background-color: <?php echo $ticketmachine_design->button_primary_background_color; ?>;
        color: <?php echo $ticketmachine_design->button_primary_text_color; ?> !important;
        border-color: <?php echo $ticketmachine_design->button_primary_border_color; ?>;
    }

    .ticketmachine_page .btn-primary:hover,
    .ticketmachine_page .btn-primary:active,
    .ticketmachine_page .btn-primary:focus {
        background-color: <?php echo $ticketmachine_design->button_primary_background_color_hover; ?> !important;
        color: <?php echo $ticketmachine_design->button_primary_text_color_hover; ?> !important;
        border-color: <?php echo $ticketmachine_design->button_primary_border_color_hover; ?> !important;
    }

    .btn-secondary,
    .btn-secondary:visited,
    .btn-secondary:not(:disabled):not(.disabled).active,
    .btn-secondary:not(:disabled):not(.disabled):active {
        background-color: <?php echo $ticketmachine_design->button_secondary_background_color; ?>;
        color: <?php echo $ticketmachine_design->button_secondary_text_color; ?> !important;
        border-color: <?php echo $ticketmachine_design->button_secondary_border_color; ?>;
    }

    .btn-secondary:hover,
    .btn-secondary:active,
    .btn-secondary:focus,
    .show>.btn-secondary.dropdown-toggle {
        background-color: <?php echo $ticketmachine_design->button_secondary_background_color_hover; ?> !important;
        color: <?php echo $ticketmachine_design->button_secondary_text_color_hover; ?> !important;
        border-color: <?php echo $ticketmachine_design->button_secondary_border_color_hover; ?> !important;
    }

    .spinner-border {
        border-color: <?php echo $ticketmachine_design->button_primary_background_color; ?> !important;
        color: <?php echo $ticketmachine_design->button_primary_background_color; ?> !important;
        border-right-color: transparent !important;
    }