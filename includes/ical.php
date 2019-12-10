<?php
	if ( !defined('ABSPATH') ) {
        //If wordpress isn't loaded load it up.
        $path = $_SERVER['DOCUMENT_ROOT'];
        include_once $path . '/wp-load.php';
    }

    include(str_replace("/includes", "", plugin_dir_path( __FILE__ )) . 'globals.php');

    $tm_json = apiRequest($api->get_single_event_no_categories);
    $event = (object)$tm_json;
#19970714T170000Z
#19970715T035959Z
    if($event->id) {
        $ical = "BEGIN:VCALENDAR
                VERSION:2.0
                PRODID:-//hacksw/handcal//NONSGML v1.0//EN
                BEGIN:VEVENT
                UID:" . md5(uniqid(mt_rand(), true)) . "@yourhost.test
                DTSTAMP:" . gmdate('Ymd').'T'. gmdate('His') . "Z
                DTSTART:". date("Ymd", strtotime($event->ev_date))."T". date("His", strtotime($event->ev_date)) ."
                DTEND:  ". date("Ymd", strtotime($event->endtime))."T". date("His", strtotime($event->endtime)) ."
                SUMMARY:". $event->ev_name ."
                END:VEVENT
                END:VCALENDAR";

        header('Content-type: text/calendar; charset=utf-8');
        header('Content-Disposition: inline; filename=calendar.ics');

        echo $ical;   
    }
?>