
<?php
    global $globals, $api;

    if($_GET['code']) {
        echo "Authorization complete!<br>Code: " . $_GET['code'];

        // Exchange the auth code for an access token
	    $token = apiRequest($api->token, array(
            'grant_type' => 'authorization_code',
            'client_id' => $api->client_id,
            'client_secret' => $api->client_secret,
            'code' => $_GET['code'],
            'redirect_uri' => $api->auth->redirect_uri . "?start_uri=" . $api->auth->start_uri
        ));
        $_SESSION['access_token'] = $token['access_token'];

        print_r($token);
    }
?>


<div class="wrap tm-admin-page">
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="row">
                <div class="col-xs-12 text-center" style="font-size: 16px;">
                    <img style="width: 90px;" src=" <?php echo dirname(plugin_dir_url( __FILE__ ), 1) . '/assets/img/logo.png'; ?>" alt="TicketMachine Logo">
                    <h1>Vielen Dank, dass Sie mich installiert haben!</h1>
                    <div>Sie sind noch einen Schritt entfernt, um TicketMachine nutzen zu können.</div>
                    <i class="fas fa-cloud tm-icon-big mt-4 mb-2"></i>
                    <div>Alle Veranstaltungen werden über den TicketMachine Cloud Service
                        <i class="fas fa-info-circle" title="Was soll hier stehen?"></i>
                        <br>
                        gespeichert.
                    </div>
                    <i class="far fa-user-circle tm-icon-big mt-4 mb-2"></i>
                    <div>Um Ihr TicketMachine Konto zu erstellen (oder eine Verbindung herzustellen),
                    <br>
                     benötigen wir Ihre E-Mail-Adresse.
                    </div>

                    <?php
                      //$authorize_url = "http://apiv2." . $api->environment . "ticketmachine.de/oauth/authorize";
                      $authorize_url = "http://localhost:3002/oauth/authorize";
                      $authorize_url .= "?";
                      $authorize_url .= http_build_query($api->auth->data);
                      echo '<p><a class="button button-primary" href="'.$authorize_url.'">Mit TicketMachine verbinden</a></p>';
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>