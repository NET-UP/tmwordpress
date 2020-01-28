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

        $tm_output = '
                <div class="title-height tm_actions text-right no-mobile-height mb-3 mb-lg-0">
                    <a class="btn btn-secondary px-3 mb-1 mb-lg-0 ml-1" href="/' . $globals->events_slug . '">
                        <i class="fas fa-chevron-left"></i> &nbsp; ' . __('Back', 'ticketmachine') . '
                    </a>';
                    if ($globals->show_social_media_ical && $globals->show_social_media) {
                        $tm_output .= '<a class="btn btn-secondary mb-1 mb-lg-0 ml-1" target="_blank" href="/wp-content/plugins/ticketmachine/includes/ical.php?id=' . $event->id . '&url=' . $url .'" title="' . __('Save as iCal', 'ticketmachine') . '">
                            <i class="fas fa-calendar-alt"></i>
                        </a>';
                    }
                    if ($globals->show_social_media_google_cal && $globals->show_social_media) {
                        $tm_output .= '<a class="btn btn-secondary mb-1 mb-lg-0 ml-1" target="_blank" href="' . $google_calendar_url . '" title="' . __('Save to Google Calendar', 'ticketmachine') . '">
                            <i class="fab fa-google"></i>
                        </a>';
                    }
                    if ($globals->show_social_media_facebook && $globals->show_social_media) {
                        $tm_output .= '<a class="btn btn-secondary share-popup mb-1 mb-lg-0 ml-1" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u='. $url .'" title="' . __('Share to Facebook', 'ticketmachine') . '">
                            <i class="fab fa-facebook-f"></i>
                        </a>';
                    }
                    if ($globals->show_social_media_twitter && $globals->show_social_media) {
                        $tm_output .= '<a class="btn btn-secondary share-popup mb-1 mb-lg-0 ml-1" target="_blank" href="https://twitter.com/intent/tweet?text='. $url .'" title="' . __('Share to Twitter', 'ticketmachine') . '">
                            <i class="fab fa-twitter"></i>
                        </a>';
                    }
                    if ($globals->show_social_media_email && $globals->show_social_media) {
                        $tm_output .= '<a class="btn btn-secondary mb-1 mb-lg-0 ml-1" href="mailto:?subject='. $event->ev_name .'&body='. $url .'" title="' . __('Share via Email', 'ticketmachine') . '">
                            <i class="fas fa-envelope"></i>
                        </a>';
                    }
                    if ($globals->show_social_media_messenger && $globals->show_social_media) {
                        $tm_output .= '<a class="btn btn-secondary d-inline-block d-md-none mb-1 mb-lg-0 ml-1" href="fb-messenger://share/?link='. $url .'" title="' . __('Share with Messenger', 'ticketmachine') . '">
                            <i class="fab fa-facebook-messenger"></i>
                        </a>';
                    }
                    if ($globals->show_social_media_whatsapp && $globals->show_social_media) {
                        $tm_output .= '<a class="btn btn-secondary d-inline-block d-md-none mb-1 mb-lg-0 ml-1" href="WhatsApp://send?text='. $url .'" title="' . __('Share with WhatsApp', 'ticketmachine') . '">
                            <i class="fab fa-whatsapp"></i>
                        </a>';
                    }
        $tm_output .= '
                </div>';

        return $tm_output;
	}
?>