
<?php #TO DO Add categroies
    function tm_event_page_tickets ( $event, $globals ) {
#<% unless @event.categories.select{|ec| ec.ec_price >= 0}.empty? %>
#<% end %>
        if($event->state["prices_shown"] == true){
            
        }

        $googe_calendar_url =   'http://www.google.com/calendar/render?
                                action=TEMPLATE
                                &text='. urlencode($event->ev_name) .'
                                &dates='. date("Ymd", strtotime($event->ev_date)) .'T'. date("His", strtotime($event->ev_date)) . 'Z' . '/'. date("Ymd", strtotime($event->endtime)).'T'. date("His", strtotime($event->endtime)) . 'Z
                                &details='. urlencode($event->ev_description) .'
                                &location='. urlencode($event->ev_location_name) .'
                                &trp=false
                                &sprop=
                                &sprop=name:';

        $tm_output .= '
                <a class="btn btn-secondary px-3" href="/events">
                    <i class="fas fa-chevron-left"></i> &nbsp; Zurück
                </a>
                <a href="/wp-content/plugins/ticketmachine/includes/ical.php?id=' . $event->id . '"><i class="fas fa-calendar-alt" title="Als ical speichern"></i></a>
                <a href="' . $google_calendar_url . '">GOOGLE</a>';

        return $tm_output;
	}
?>