<?php 
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    global $globals, $api;

    if ( ! isset( $_POST['ticketmachine_event_edit_form_nonce'] ) || ! wp_verify_nonce( $_POST['ticketmachine_event_edit_form_nonce'], 'ticketmachine_action_save_event' ) ) {
        print 'Sorry, your nonce did not verify.';
        exit;
    } else {
        
        if(isset($_GET['id'])){
            $_POST['id'] = absint($_GET['id']);
            $_POST['organizer_id'] = absint($globals->organizer_id);
            
            $post_json = json_encode($_POST);
            $ticketmachine_json = ticketmachine_tmapi_event($post_json, "POST");
            $response = (object)$ticketmachine_json;
        }
    ?>

    <?php if(isset($response->model_error[0]['error_code']) && strlen($response->model_error[0]['error_code']) > 0){ ?>
        <div class="notice notice-error is-dismissable">
            <p><?php esc_html_e($response->model_error[0]['error_message']); ?></p>
        </div>
    <?php }elseif(empty($ticketmachine_json)){ ?>
        <div class="notice notice-error is-dismissable">
            <p><?php esc_html_e('Something went wrong', 'ticketmachine'); ?>!</p>
        </div>
    <?php }else{ ?>
        <div class="notice notice-success is-dismissable">
            <p><?php esc_html_e('Event successfully deleted', 'ticketmachine'); ?>!</p>
        </div>

    <?php 
        } 
    }
?>