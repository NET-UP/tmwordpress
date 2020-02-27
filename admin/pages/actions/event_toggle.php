<?php 
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    global $globals, $api;

    if ( ! isset( $_POST['ticketmachine_event_edit_form_nonce'] ) || ! wp_verify_nonce( $_POST['ticketmachine_event_edit_form_nonce'], 'ticketmachine_action_save_event' ) ) {
        print 'Sorry, your nonce did not verify.';
        exit;
    } else {
        
        if(isset($_GET['id'])){
            $params = [ "id" => absint($_GET['id']) ];
            $ticketmachine_json_a = ticketmachine_tmapi_event($params);
            $_POST = (array)$ticketmachine_json_a;

            $_POST['id'] = absint($_GET['id']);
            $_POST['organizer_id'] = absint($globals->organizer_id);
            $_POST['approved'] = 1 - absint($_POST['approved']);
            $_POST['rules']['shown'] = absint($_POST['approved']);
            
            $post_json = $_POST;
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
            <p>
                <?php 
                    if($response->approved == 1){
                        esc_html_e('Published', 'ticketmachine'); 
                    }else{
                        esc_html_e('Deactivated', 'ticketmachine'); 
                    }
                ?>!
                &nbsp;-&nbsp;
                <a href="?page=ticketmachine_events&action=deactivate&id=<?php echo $response->id; ?>"><?php esc_html_e('Undo', 'ticketmachine'); ?></a>
            </p>
        </div>

    <?php 
        } 
    }
?>