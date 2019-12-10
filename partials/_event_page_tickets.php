
<?php #TO DO Add categroies
    function tm_event_page_tickets ( $event, $globals ) {
#<% unless @event.categories.select{|ec| ec.ec_price >= 0}.empty? %>
#<% end %>
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
        if($event->state["prices_shown"] == true){
            
        }
        $tm_output .= '
                <a class="btn btn-secondary px-3" href="/events">
                    <i class="fas fa-chevron-left"></i> &nbsp; Zurück
                </a>'
                $ical;
        return $tm_output;
	}
?>