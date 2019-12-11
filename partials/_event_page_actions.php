<?php #TO DO Add categroies
    function tm_event_page_actions ( $event, $globals ) {
#<% unless @event.categories.select{|ec| ec.ec_price >= 0}.empty? %>
#<% end %>
        if($event->state["prices_shown"] == true){
            
        }

        include(WP_PLUGIN_DIR . "/ticketmachine/includes/google_calendar.php");

        $tm_output .= '
                <div class="title-height no-height-mobile">
                    <a class="btn btn-secondary px-3" href="/events">
                        <i class="fas fa-chevron-left"></i> &nbsp; Zurück
                    </a>
                    <a class="btn btn-secondary" target="_blank" href="/wp-content/plugins/ticketmachine/includes/ical.php?id=' . $event->id . '" title="' . __('Als iCal speichern', 'ticketmachine') . '"><i class="fas fa-calendar-alt"></i></a>
                    
                    <a class="btn btn-secondary" target="_blank" href="' . $google_calendar_url . '" title="' . __('Im Google Kalender speichern', 'ticketmachine') . '"><i class="fab fa-google"></i></a>
                    </div>';

        return $tm_output;
	}
?>