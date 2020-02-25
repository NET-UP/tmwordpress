
<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    global $globals, $api, $wpdb;
    $ticketmachine_config = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_config LIMIT 0,1");
    $ticketmachine_config = $ticketmachine_config[0];
?>

<div class="wrap tm-admin-page">
    <h1 class="dont-display"></h1>
    
    <div class="container">
        <div class="center-center container-md">
            
            <img style="width: 75px;margin:0 auto;margin-bottom:2em;display:block;" src=" <?php echo dirname(plugin_dir_url( __FILE__ ), 1) . '/assets/img/logo.png'; ?>" alt="TicketMachine Logo">         
            <div class="box">
                <div class="text-center box-body pt-3 pb-4 px-4">

                    <h1>
                        <?php esc_html_e("Thanks for installing me!", "ticketmachine"); ?>
                    </h1>
                    <div>
                        <?php esc_html_e("Just one more step to start using TicketMachine.", "ticketmachine"); ?>
                    </div>

                    <i class="fas fa-cloud tm-icon-big mt-4 mb-2"></i>
                    <div class="mb-3">
                        <?php esc_html_e("All events are saved through the TicketMachine Cloud Service.", "ticketmachine"); ?>
                    </div>
                    <hr>

                    <i class="far fa-user-circle tm-icon-big mt-2 mb-2"></i>
                    <div>
                        <?php esc_html_e("In order to create your TicketMachine account (or connect to your current one), we require your email adress.", "ticketmachine"); ?>
                    </div>

                    <?php
                        $current_locale = get_locale();
                        $parsed_locale = substr($current_locale, 0, strpos($current_locale, '_'));

                        $authorize_url = $api->auth->proxy;
                        $authorize_url .= "?";
                        $authorize_url .= http_build_query($api->auth->data);
                    ?>
                    
                    <a class="button button-primary mt-4 px-3 py-md-1" style="font-size:14px" href="<?php echo $authorize_url; ?>">
                        <?php esc_html_e("Connect with TicketMachine", "ticketmachine"); ?>
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>