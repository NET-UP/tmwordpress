<?php 
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    global $globals, $api;

    if( current_user_can('edit_posts') ) {	

        if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'ticketmachine_action_toggle_event' ) ) {
            print 'Sorry, your nonce did not verify.';
            exit;
        } else {
            $event_id = (int)$_GET['id'];
            $errors = array();
            
            if(!empty($event_id)){
                $params = [ "id" => absint($event_id) ];
                $ticketmachine_json_a = ticketmachine_tmapi_event($params);
                $post = (array)$ticketmachine_json_a;

                //validation
                if(!empty($post['approved'])){
                    $post['approved'] = 1;
                    $post['rules']['shown'] = 1;
                }else{
                    $post['approved'] = 0;
                    $post['rules']['shown'] = 0;
                }

                if(empty($globals->organizer_id) || !is_int($globals->organizer_id)){
                    $errors[] = "No organizer id could be found";
                }
                
                if(empty($errors)){
                    $post['id'] = absint($event_id);
                    $post['organizer_id'] = absint($globals->organizer_id);
                    $post['approved'] = 1 - absint($post['approved']);
                    $post['rules']['shown'] = absint($post['approved']);

                    $post_json = $post;
                    $ticketmachine_json = ticketmachine_tmapi_event($post_json, "POST");
                    $response = (object)$ticketmachine_json;
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
                    <?php 
                        if($response->approved == 1){
                            esc_html_e('Published', 'ticketmachine-event-manager'); 
                        }else{
                            esc_html_e('Deactivated', 'ticketmachine-event-manager'); 
                        }
                        $ticketmachine_action_toggle_url = add_query_arg(  '_wpnonce', wp_create_nonce( 'ticketmachine_action_toggle_event' ), admin_url( 'admin.php?page=ticketmachine_events&action=deactivate&id='.$response->id ) );
                    ?>!
                    &nbsp;-&nbsp;
                    <a href="<?php echo esc_url($ticketmachine_action_toggle_url); ?>"><?php esc_html_e('Undo', 'ticketmachine-event-manager'); ?></a>
                </p>
            </div>
        <?php 
            }
        }
    } 
?>