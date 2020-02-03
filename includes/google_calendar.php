<?php

$google_calendar_url ='https://calendar.google.com/calendar/r/eventedit?
&text='. urlencode($event->ev_name) .'
&dates='. date_i18n(DATE_ISO8601, strtotime(get_date_from_gmt($event->ev_date))) . '/'. date_i18n(DATE_ISO8601, strtotime(get_date_from_gmt($event->endtime))).'
&details='. str_replace(' ', '+', wp_strip_all_tags($event->ev_description)) .'
&location='. urlencode($event->ev_location_name) .'
&sf=true
&output=xml';

?>