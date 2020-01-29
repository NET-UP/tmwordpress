
<?php
    include( str_replace("/admin/pages", "", plugin_dir_path(__FILE__)) . 'admin/includes/scriptstyles.php');
    
    global $globals, $api, $wpdb;
    $tm_config = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_config LIMIT 0,1");
    $tm_config = $tm_config[0];
?>

<div class="wrap tm-admin-page">
    <div class="container">
        <div class="center-center">
            
            <img style="width: 75px;margin:0 auto;margin-bottom:2em;display:block;" src=" <?php echo dirname(plugin_dir_url( __FILE__ ), 1) . '/assets/img/logo.png'; ?>" alt="TicketMachine Logo">         
            <div class="box box-md">
                <div class="px-5 py-4 text-center">

                    <h1>
                        <?php echo __("Thanks for installing me!", "ticketmachine"); ?>
                    </h1>
                    <div>
                        <?php echo __("Just one more step to start using TicketMachine.", "ticketmachine"); ?>
                    </div>

                    <i class="fas fa-cloud tm-icon-big mt-4 mb-2"></i>
                    <div class="mb-3">
                        <?php echo __("All events are saved through the TicketMachine Cloud Service.", "ticketmachine"); ?>
                    </div>
                    <hr>

                    <i class="far fa-user-circle tm-icon-big mt-2 mb-2"></i>
                    <div>
                        <?php echo __("In order to create your TicketMachine account (or connect to your current one), we require your email adress.", "ticketmachine"); ?>
                    </div>

                    <?php
                        $current_locale = get_locale();
                        $parsed_locale = substr($current_locale, 0, strpos($current_locale, '_'));

                        $api->auth->testdata = array(
                            'response_type' => 'code',
                            'client_id' => "4c0bcf69d871fb55362382f436e768b277592f3243e7da9bfef4dff997392fe0",
                            'redirect_uri' => $api->auth->redirect_uri . "?start_uri=" . $api->auth->start_uri,
                            'state' => $_SESSION['state'],
                            'scope' => 'public organizer organizer/event',
                            'locale' => $parsed_locale
                        );

                        //$authorize_url = "http://apiv2." . $api->environment . "ticketmachine.de/oauth/authorize";
                        $authorize_url = "http://localhost:3002/oauth/authorize";
                        $authorize_url .= "?";
                        //$authorize_url .= http_build_query($api->auth->data);
                        $authorize_url .= http_build_query($api->auth->testdata);
                    ?>
                    
                    <a class="button button-primary mt-4 px-3 py-md-1" style="font-size:14px" href="<?php echo $authorize_url; ?>">
                        <?php echo __("Connect with TicketMachine", "ticketmachine"); ?>
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>