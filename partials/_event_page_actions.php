<?php #TO DO Add categroies
    function tm_event_page_actions ( $event, $globals ) {
#<% unless @event.categories.select{|ec| ec.ec_price >= 0}.empty? %>
#<% end %>
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
        $url = "https://";   
        else  
        $url = "http://";   
        // Append the host(domain name, ip) to the URL.   
        $url.= $_SERVER['HTTP_HOST'];   

        // Append the requested resource location to the URL   
        $url.= $_SERVER['REQUEST_URI'];    

        include(WP_PLUGIN_DIR . "/ticketmachine/includes/google_calendar.php");

        $tm_output .= '
                <div class="title-height tm_actions text-right no-mobile-height mb-3 mb-lg-0">
                    <a class="btn btn-secondary px-3 mb-1 mb-lg-0" href="/events">
                        <i class="fas fa-chevron-left"></i> &nbsp; Zurück
                    </a>
                    <a class="btn btn-secondary mb-1 mb-lg-0" target="_blank" href="/wp-content/plugins/ticketmachine/includes/ical.php?id=' . $event->id . '&url=' . $url .'" title="' . __('Als iCal speichern', 'ticketmachine') . '">
                        <i class="fas fa-calendar-alt"></i>
                    </a>
                    <a class="btn btn-secondary mb-1 mb-lg-0" target="_blank" href="' . $google_calendar_url . '" title="' . __('Im Google Kalender speichern', 'ticketmachine') . '">
                        <i class="fab fa-google"></i>
                    </a>
                    <a class="btn btn-secondary share-popup mb-1 mb-lg-0" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u='. $url .'" title="' . __('Auf Facebook teilen', 'ticketmachine') . '">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a class="btn btn-secondary share-popup mb-1 mb-lg-0" target="_blank" href="https://twitter.com/intent/tweet?text='. $url .'" title="' . __('Auf Twitter teilen', 'ticketmachine') . '">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a class="btn btn-secondary mb-1 mb-lg-0" href="mailto:?subject='. $event->ev_name .'&body='. $url .'" title="' . __('Per E-Mail teilen', 'ticketmachine') . '">
                        <i class="fas fa-envelope"></i>
                    </a>
                    <a class="btn btn-secondary d-inline-block d-sm-none mb-1 mb-lg-0" href="WhatsApp://send?text='. $url .'" title="' . __('Per WhatsApp teilen', 'ticketmachine') . '">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a class="btn btn-secondary d-inline-block d-sm-none mb-1 mb-lg-0" href="fb-messenger://share/?link='. $url .'">
                        <i class="fab fa-facebook-messenger"></i>
                    </a>
                </div>';

        return $tm_output;
	}
?>