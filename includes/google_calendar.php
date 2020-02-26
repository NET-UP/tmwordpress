<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    $google_calendar_url ='https://calendar.google.com/calendar/r/eventedit?text='. urlencode($event->ev_name) .'&dates='. ticketmachine_i18n_date("Ymd", $event->ev_date) .'T'. ticketmachine_i18n_date("His", $event->ev_date) . 'Z' . '/'. ticketmachine_i18n_date("Ymd", $event->endtime).'T'. ticketmachine_i18n_date("His", $event->endtime) . 'Z&details='. str_replace(' ', '+', wp_strip_all_tags($event->ev_description)) .'&location='. urlencode($event->ev_location_name) .'&sf=true&output=xml';

?>