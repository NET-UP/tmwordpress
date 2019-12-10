
<?php #TO DO Add categroies
    function tm_event_page_tickets ( $event, $globals ) {
#<% unless @event.categories.select{|ec| ec.ec_price >= 0}.empty? %>
#<% end %>
        if($event->state["prices_shown"] == true){
            
        }

$google_calendar_url ='https://calendar.google.com/calendar/r/eventedit?
&text='. urlencode($event->ev_name) .'
&dates='. date("Ymd", strtotime($event->ev_date)) .'T'. date("His", strtotime($event->ev_date)) . 'Z' . '/'. date("Ymd", strtotime($event->endtime)).'T'. date("His", strtotime($event->endtime)) . 'Z
&details='. str_replace(' ', '+', wp_strip_all_tags($event->ev_description)) .'
&location='. urlencode($event->ev_location_name) .'
&sf=true
&output=xml';

        $tm_output .= '
                <a class="btn btn-secondary px-3" href="/events">
                    <i class="fas fa-chevron-left"></i> &nbsp; Zurück
                </a>
                <a target="_blank" href="/wp-content/plugins/ticketmachine/includes/ical.php?id=' . $event->id . '"><i class="fas fa-calendar-alt" title="Als ical speichern"></i></a>
                <a target="_blank" href="' . $google_calendar_url . '">GOOGLE</a>';

        return $tm_output;
	}
?>