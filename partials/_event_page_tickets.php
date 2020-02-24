
<?php #TO DO Add categroies
    function ticketmachine_event_page_tickets ( $event, $globals ) {
#<% unless @event.categories.select{|ec| ec.ec_price >= 0}.empty? %>
#<% end %>
        if($event->state["prices_shown"] == true){
            
        }

        include(WP_PLUGIN_DIR . "/ticketmachine/includes/google_calendar.php");

        $ticketmachine_output = '';

        return $ticketmachine_output;
	}
?>