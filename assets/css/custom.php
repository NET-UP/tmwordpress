<?php
    include_once("{$_SERVER['DOCUMENT_ROOT']}/wp-load.php"); 
    header("Content-type: text/css; charset: UTF-8");
    global $wpdb;
    $tm_design = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_design LIMIT 0,1");
    $tm_design = $tm_design[0];
?>
    body .tm_page a:not(.btn) {
        color: <?php echo $tm_design->link_text_color; ?>;
    }

    body .tm_page a:not(.btn):hover,  body .tm_page a:not(.btn):focus {
        color: <?php echo $tm_design->link_text_color_hover; ?>;
        text-decoration:underline;
    }
    .card-img-top:focus {
        border:2px solid <?php echo $tm_design->button_primary_background_color; ?>;
    }

    
    .tm_page #calendar .fc-event:focus {
        box-shadow: 0px 0px 1px 2px <?php echo $tm_design->button_primary_background_color; ?>;
    }

    .tm_events_container .card {
        background: <?php echo $tm_design->container_background_color; ?>;
    }

    .tm_events_container .card-date {
        color: <?php echo $tm_design->date_text_color; ?>;
    }

    .tm_events_container .card-price {
        color: <?php echo $tm_design->price_text_color; ?>;
    }

    .tm_events_container .btn-primary,
    .tm_events_container .btn-primary:visited,
    .tm_events_container .btn-primary:not(:disabled):not(.disabled).active,
    .tm_events_container .btn-primary:not(:disabled):not(.disabled):active,
    .show>.tm_events_container .btn-primary.dropdown-toggle,
    .tm_events_container .btn-primary.disabled,
    .tm_events_container .btn-primary:disabled {
        background-color: <?php echo $tm_design->button_primary_background_color; ?>;
        color: <?php echo $tm_design->button_primary_text_color; ?> !important;
        border-color: <?php echo $tm_design->button_primary_border_color; ?>;
    }

    .tm_events_container .btn-primary:hover,
    .tm_events_container .btn-primary:active,
    .tm_events_container .btn-primary:focus {
        background-color: <?php echo $tm_design->button_primary_background_color_hover; ?> !important;
        color: <?php echo $tm_design->button_primary_text_color_hover; ?> !important;
        border-color: <?php echo $tm_design->button_primary_border_color_hover; ?> !important;
    }

    .btn-secondary,
    .btn-secondary:visited,
    .btn-secondary:not(:disabled):not(.disabled).active,
    .btn-secondary:not(:disabled):not(.disabled):active {
        background-color: <?php echo $tm_design->button_secondary_background_color; ?>;
        color: <?php echo $tm_design->button_secondary_text_color; ?> !important;
        border-color: <?php echo $tm_design->button_secondary_border_color; ?>;
    }

    .btn-secondary:hover,
    .btn-secondary:active,
    .btn-secondary:focus,
    .show>.btn-secondary.dropdown-toggle {
        background-color: <?php echo $tm_design->button_secondary_background_color_hover; ?> !important;
        color: <?php echo $tm_design->button_secondary_text_color_hover; ?> !important;
        border-color: <?php echo $tm_design->button_secondary_border_color_hover; ?> !important;
    }

    .spinner-border {
        border-color: <?php echo $tm_design->button_primary_background_color; ?> !important;
        color: <?php echo $tm_design->button_primary_background_color; ?> !important;
        border-right-color: transparent !important;
    }