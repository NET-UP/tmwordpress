<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	if ( !defined('ABSPATH') ) {
        //If wordpress isn't loaded load it up.
        $path = $_SERVER['DOCUMENT_ROOT'];
        include_once $path . '/wp-load.php';
    }

    include(WP_PLUGIN_DIR . "/ticketmachine/globals.php");

    $params = [ "id" => absint($_GET['id']) ];
    $event = ticketmachine_tmapi_event($params);

    if($event->id) {
//dont move this!
$ical = "BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//TicketMachine WP-Plugin//DE
BEGIN:VEVENT
UID:" . md5(uniqid(mt_rand(), true)) . "@ticketmachine.de
DTSTAMP:" . gmdate('Ymd').'T'. gmdate('His') . "Z
DTSTART:". date("Ymd", strtotime($event->ev_date))."T". date("His", strtotime($event->ev_date)) . "Z" . "
DTEND:". date("Ymd", strtotime($event->endtime))."T". date("His", strtotime($event->endtime)) . "Z" . "
SUMMARY:". $event->ev_name ."
LOCATION:". $event->ev_location_name ."
DESCRIPTION:". $_GET['url'] ."
END:VEVENT
END:VCALENDAR";

        header('Content-type: text/calendar; charset=utf-8');
        header('Content-Disposition: inline; filename='. sanitize_file_name($event->ev_name) .'.ics');

        echo $ical;   
    }
?>