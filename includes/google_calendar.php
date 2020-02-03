<?php

$google_calendar_url ='https://calendar.google.com/calendar/r/eventedit?
&text='. urlencode($event->ev_name) .'
&dates='. date_i18n("Ymd", strtotime(get_date_from_gmt($event->ev_date))) .'T'. date_i18n("His", strtotime(get_date_from_gmt($event->ev_date))) . 'Z' . '/'. date_i18n("Ymd", strtotime(get_date_from_gmt($event->endtime))).'T'. date_i18n("His", strtotime(get_date_from_gmt($event->endtime))) . 'Z
&details='. str_replace(' ', '+', wp_strip_all_tags($event->ev_description)) .'
&location='. urlencode($event->ev_location_name) .'
&sf=true
&output=xml';

?>