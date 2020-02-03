<?php

$google_calendar_url ='https://calendar.google.com/calendar/r/eventedit?
&text='. urlencode($event->ev_name) .'
&dates='. date("Ymd", strtotime(get_date_from_gmt($event->ev_date))) .'T'. date("His", strtotime(get_date_from_gmt($event->ev_date))) . 'Z' . '/'. date("Ymd", strtotime(get_date_from_gmt($event->endtime))).'T'. date("His", strtotime(get_date_from_gmt($event->endtime))) . 'Z
&details='. str_replace(' ', '+', wp_strip_all_tags($event->ev_description)) .'
&location='. urlencode($event->ev_location_name) .'
&sf=true
&output=xml';

?>