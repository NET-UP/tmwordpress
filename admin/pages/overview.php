<?php
	defined("ABSPATH") or die("Permission denied");
	include( str_replace("/admin/pages", "", plugin_dir_path(__FILE__)) . 'admin/includes/scriptstyles.php');
	
    global $globals, $api, $wpdb;
    $tm_config = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_config LIMIT 0,1");
    $tm_config = $tm_config[0];

    if($_GET['code']) {
        // Exchange the auth code for an access token
	    //$token = apiRequest($api->token, array(
        //    'grant_type' => 'authorization_code',
        //    'client_id' => $api->client_id,
        //    'client_secret' => $api->client_secret,
        //    'code' => $_GET['code'],
        //    'redirect_uri' => $api->auth->redirect_uri . "?start_uri=" . $api->auth->start_uri
        //));
        //$_SESSION['access_token'] = $token['access_token'];

		$save_array = 
            array(
                "activated" => 1,
            );

        $wpdb->update(
            $wpdb->prefix . "ticketmachine_config",
            $save_array,
            array('id' => $tm_config->id)
        );
?>

<div class="box wrap">
    <h1>Welcome to <span>TicketMachine</span>1.0!</h1>
</div>

<?php
    }
?>

<div class="wrap tm-admin-page">
	<h1 class="wp-heading-inline mr-3">TicketMachine <i class="fas fa-angle-right mx-1"></i> <?php echo __('Overview', 'ticketmachine') ?></h1>
</div>