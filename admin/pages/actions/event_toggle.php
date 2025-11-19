<?php 
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    global $ticketmachine_globals, $ticketmachine_api;

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
                $ticketmachine_post = (array)$ticketmachine_json_a;

                //validation
                if(!empty($ticketmachine_post['approved'])){
                    $ticketmachine_post['approved'] = 1;
                    $ticketmachine_post['rules']['shown'] = 1;
                }else{
                    $ticketmachine_post['approved'] = 0;
                    $ticketmachine_post['rules']['shown'] = 0;
                }

                if(empty($ticketmachine_globals->organizer_id) || !is_int($ticketmachine_globals->organizer_id)){
                    $errors[] = "No organizer id could be found";
                }
                
                if(empty($errors)){
                    $ticketmachine_post['id'] = absint($event_id);
                    $ticketmachine_post['organizer_id'] = absint($ticketmachine_globals->organizer_id);
                    $ticketmachine_post['approved'] = 1 - absint($ticketmachine_post['approved']);
                    $ticketmachine_post['rules']['shown'] = absint($ticketmachine_post['approved']);

                    $ticketmachine_post_json = $ticketmachine_post;
                    $ticketmachine_json = ticketmachine_tmapi_event($ticketmachine_post_json, "POST");
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
        <?php 
            }else{ 
                $redirect_url = add_query_arg(
                    array(
                        'page'   => esc_html($_GET['page']),
                        'status' => isset($_GET['status']) ? esc_html($_GET['status']) : false,
                        'saved' => 'success',
                        'action' => 'toggled',
                        'state' => $response->approved == 1 ? 'published' : 'deactivated',
                        'id' => $event_id
                    ),
                    admin_url( 'admin.php' )
                );
                
                wp_safe_redirect( $redirect_url );
            }
        }
    } 
?>