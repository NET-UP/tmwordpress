<?php 
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    global $ticketmachine_globals, $ticketmachine_api;

    if( current_user_can('edit_posts') ) {	

        if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'ticketmachine_action_copy_event' ) ) {
            print 'Sorry, your nonce did not verify.';
            exit;
        } else {
            $event_id = (int)$_GET['id'];
            $errors = array();
        
            if(!empty($event_id)){
                if(empty($ticketmachine_globals->organizer_id) || !is_int($ticketmachine_globals->organizer_id)){
                    $errors[] = "No organizer id could be found";
                }
                
                if(empty($errors)){
                    $ticketmachine_post = array();
                    $ticketmachine_post['id'] = absint($event_id);
                    $ticketmachine_post['organizer_id'] = absint($ticketmachine_globals->organizer_id);

                    $ticketmachine_post_json = $ticketmachine_post;
                    $ticketmachine_json = ticketmachine_tmapi_event_copy($ticketmachine_post_json);
                    $response = (object)$ticketmachine_json;
                    
                    if($response->result == "failure") {
                        $errors[] = $response->reason;
                    }
                }
            }else{
                $errors[] = "No event id was set";
            }
        ?>

        <?php if(isset($response->model_error[0]['error_code']) && strlen($response->model_error[0]['error_code']) > 0){ ?>
            <div class="notice notice-error is-dismissable">
                <p><?php esc_html_e($response->model_error[0]['error_message']); ?></p>
            </div>
        <?php }elseif(empty($ticketmachine_json) || !empty($errors)){ ?>
            <div class="notice notice-error is-dismissable">
                <p><?php esc_html_e('Something went wrong', 'ticketmachine-event-manager'); ?>!</p>
            </div>
        <?php }else{ ?>
            <div class="notice notice-success is-dismissable">
                <p>
                    <?php esc_html_e('Event successfully copied', 'ticketmachine-event-manager'); ?>!
                    &nbsp;-&nbsp;
                    <a target="_blank" href="/<?php echo esc_html($ticketmachine_globals->event_slug); ?>?id=<?php echo esc_html($response->id); ?>">
                        <?php 
                            if($response->approved == 1){
                                esc_html_e('View', 'ticketmachine-event-manager'); 
                            }else{
                                esc_html_e('Preview', 'ticketmachine-event-manager'); 
                            }
                        ?>
                    </a>
                </p>
            </div>
        <?php 
            }
        }
    } 
?>