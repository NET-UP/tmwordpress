
<?php
    include( str_replace("/admin/pages", "", plugin_dir_path(__FILE__)) . 'admin/includes/scriptstyles.php');
    
    global $globals, $api, $wpdb;
    $tm_config = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_config LIMIT 0,1");
    $tm_config = $tm_config[0];

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

        //print_r($token);
		$save_array = 
            array(
                "activated" => 1,
            );

        //$wpdb->update(
        //    $wpdb->prefix . "ticketmachine_config",
        //    $save_array,
        //    array('id' => $tm_config->id)
        //);

    }else{
?>


<div class="wrap tm-admin-page">
    <div class="container">
        <div class="box box-md">
            <div class="card p-4">
                <div class="row">
                    <div class="col-12 text-center" style="font-size: 16px;line-height: 20px;">
                        <img style="width: 75px;" src=" <?php echo dirname(plugin_dir_url( __FILE__ ), 1) . '/assets/img/logo.png'; ?>" alt="TicketMachine Logo">
                        <h1>Vielen Dank, dass Sie mich installiert haben!</h1>
                        <div>Sie sind noch einen Schritt entfernt, um TicketMachine nutzen zu können.</div>
                        <i class="fas fa-cloud tm-icon-big mt-4 mb-2"></i>
                        <div>
                            Alle Veranstaltungen werden über den TicketMachine Cloud Service gespeichert.
                            <i class="fas fa-info-circle" title="Was soll hier stehen?"></i>
                        </div>
                        <i class="far fa-user-circle tm-icon-big mt-4 mb-2"></i>
                        <div>
                            Um Ihr TicketMachine Konto zu erstellen (oder eine Verbindung herzustellen), benötigen wir Ihre E-Mail-Adresse.
                        </div>

                        <?php
        
                            $api->auth->testdata = array(
                                'response_type' => 'code',
                                'client_id' => "4c0bcf69d871fb55362382f436e768b277592f3243e7da9bfef4dff997392fe0",
                                'redirect_uri' => $api->auth->redirect_uri . "?start_uri=" . $api->auth->start_uri,
                                'state' => $_SESSION['state'],
                                'scope' => 'public organizer organizer/event'
                            );
        
                        //$authorize_url = "http://apiv2." . $api->environment . "ticketmachine.de/oauth/authorize";
                        $authorize_url = "http://localhost:3002/oauth/authorize";
                        $authorize_url .= "?";
                        //$authorize_url .= http_build_query($api->auth->data);
                        $authorize_url .= http_build_query($api->auth->testdata);
                        echo '<p><a class="button button-primary" href="'.$authorize_url.'">Mit TicketMachine verbinden</a></p>';
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    }
?>