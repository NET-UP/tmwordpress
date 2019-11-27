<?php

  if(!$globals->webshop_url) {
      include(str_replace("/admin/pages", "", plugin_dir_path( __FILE__ )) . 'globals.php');
  }
    
  $authorize_url = "http://apiv2." . $api->environment . "ticketmachine.de/oauth/authorize";
  $authorize_url .= "?";
  $authorize_url .= http_build_query($api->auth->data);
  echo '<p>Not authorized</p>';
  echo '<p><a class="button button-primary" href="'.$authorize_url.'">Mit TicketMachine verbinden</a></p>';

?>