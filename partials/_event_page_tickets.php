
<?php #TO DO Add categroies
    function tm_event_page_tickets ( $event, $globals ) {
#<% unless @event.categories.select{|ec| ec.ec_price >= 0}.empty? %>
#<% end %>
        if($event->state["prices_shown"] == true){
            
        }
        $tm_output .= '
                <a class="btn btn-secondary px-3" href="/events">
                    <i class="fas fa-chevron-left"></i> &nbsp; Zurück
                </a>
                <a href="/wp-content/plugins/ticketmachine/includes/ical.php?id=' . $event->id . '"><i class="fas fa-calendar-alt"></i></a>';
        return $tm_output;
	}
?>