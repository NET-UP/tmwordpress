<?php
    $ical = "BEGIN:VCALENDAR
            VERSION:2.0
            PRODID:-//hacksw/handcal//NONSGML v1.0//EN
            BEGIN:VEVENT
            UID:" . md5(uniqid(mt_rand(), true)) . "@yourhost.test
            DTSTAMP:" . gmdate('Ymd').'T'. gmdate('His') . "Z
            DTSTART:19970714T170000Z
            DTEND:19970715T035959Z
            SUMMARY:Bastille Day Party
            END:VEVENT
            END:VCALENDAR";
    header('Content-type: text/calendar; charset=utf-8');
    header('Content-Disposition: inline; filename=calendar.ics');
?>