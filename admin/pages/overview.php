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
    }
?>

<div class="wrap tm-admin-page">

    <?php if($_GET['code']) { ?>

        <div class="box mb-3">
            <a href="#" class="close"><i class="fas fa-times"></i></a>
            <h1 class="text-center"><?php echo __('Welcome to', 'ticketmachine'); ?> <span>TicketMachine</span> <span class="text-primary">1.0!</span></h1>
        </div>

    <?php } ?>

	<h1 class="wp-heading-inline mr-3 mb-3">TicketMachine <i class="fas fa-angle-right mx-1"></i> <?php echo __('Overview', 'ticketmachine') ?></h1>

    <div class="row">
        <div class="col-xl-9">
            <div class="row">

                <div class="col-md-6 col-lg-4">
                    <div class="box p-3 mb-3">
                        <div class="box-title">Create an event</div>
                        <p>Just add a title, description, image, location and dates.</p>
                        <a href="?page=tm_events&action=edit" class="button button-secondary mb-1">Create event</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="box p-3 mb-3">
                        <div class="box-title">Edit events</div>
                        <p>Edit, publish and view your existing events.</p>
                        <a href="?page=tm_events" class="button button-secondary mb-1">View all events</a>
                        <a href="?page=tm_events&status=upcoming" class="button button-secondary mb-1">Upcoming events</a>
                        <a href="?page=tm_events&status=drafts" class="button button-secondary mb-1">Drafts</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="box p-3 mb-3">
                        <div class="box-title">Change settings</div>
                        <p>Easily adjust the design, page layouts, URLs and general settings.</p>
                        <a href="?page=tm_settings&tab=design" class="button button-secondary mb-1">Edit design</a>
                        <a href="?page=tm_settings&tab=general" class="button button-secondary mb-1">General settings</a>
                        <a href="?page=tm_settings&tab=detail" class="button button-secondary mb-1">Event settings</a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>