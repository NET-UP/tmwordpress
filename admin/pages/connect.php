<?php

  if(!$globals->webshop_url) {
      include(WP_PLUGIN_DIR . "/ticketmachine/globals.php");
  }
    
  $authorize_url = "http://cloud." . $api->environment . "ticketmachine.de/oauth/authorize";
  $authorize_url .= "?";
  $authorize_url .= http_build_query($api->auth->data);
  echo '<p>Not authorized</p>';
  echo '<p><a class="button button-primary" href="'.$authorize_url.'">Mit TicketMachine verbinden</a></p>';

?>