<?php 
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    global $globals, $api;

	if (isset($_POST['submit'])) {
        if( current_user_can('edit_posts') ) {	

            if ( ! isset( $_POST['ticketmachine_event_edit_form_nonce'] ) || ! wp_verify_nonce( $_POST['ticketmachine_event_edit_form_nonce'], 'ticketmachine_action_save_event' ) ) {
                print 'Sorry, your nonce did not verify.';
                exit;
            } else {
                $post = $_POST;
                $errors = array();

                if(!isset($post['rules']['shown'])) {
                    $post['rules']['shown'] = 0;
                }
                if(!isset($post['approved'])) {
                    $post['approved'] = 0;
                }
                if(isset($post['tags'])) {
                    $post['tags'] = explode(",", $post['tags']);
                    array_walk($post['tags'], function(&$value, &$key) {
                        $value = sanitize_text_field($value);
                    });
                }
                if(isset($post['entrytime'])) {
                    $post['entrytime'] = sanitize_text_field(ticketmachine_i18n_reverse_date($post['entrytime']['date'] . $post['entrytime']['time']));
                }
                if(isset($post['ev_date'])) {
                    $post['ev_date'] = sanitize_text_field(ticketmachine_i18n_reverse_date($post['ev_date']['date'] . $post['ev_date']['time']));
                }
                if(isset($post['endtime'])) {
                    $post['endtime'] = sanitize_text_field(ticketmachine_i18n_reverse_date($post['endtime']['date'] . $post['endtime']['time']));
                }

                if(isset($post['description'])) {
                    $post['description'] = sanitize_text_field(strip_shortcodes($post['description']));
                }

                if(!empty($post['id'])) {
                    $post['id'] = absint($post['id']);
                }
                if(isset($post['vat_id'])){
                    $post['vat_id'] = 1;
                }

                if(empty($globals->organizer_id) || !is_int($globals->organizer_id)){
                    $errors[] = "No organizer id could be found";
                }
                
                if(empty($errors)){
                    $post['organizer_id'] = absint($globals->organizer_id);
                    $post['approved'] = absint($post['approved']);
                    $post['rules']['shown'] = absint($post['rules']['shown']);
                    $post['rules']['sale_active'] = absint($post['rules']['sale_active']);
                    $post['vat_id'] = absint($post['vat_id']);

                    $post_json = $post;
                    
                    $ticketmachine_json = ticketmachine_tmapi_event($post_json, "POST");
                    $response = (object)$ticketmachine_json;
                }
            }
    
        ?>

        <?php if(isset($response->model_error[0]['error_code']) && strlen($response->model_error[0]['error_code']) > 0){ ?>
            <div class="notice notice-error is-dismissable">
                <p><?php echo __($response->model_error[0]['error_message']); ?></p>
            </div>
        <?php }elseif(empty($ticketmachine_json) || !empty($errors)){ ?>
            <div class="notice notice-error is-dismissable">
                <p><?php echo __('Something went wrong', 'ticketmachine'); ?>!</p>
            </div>
        <?php }else{ ?>
            <div class="notice notice-success is-dismissable">
                <p>
                    <?php echo __('Event saved', 'ticketmachine'); ?>!
                    &nbsp;-&nbsp;
                    <a target="_blank" href="/<?php echo $globals->event_slug; ?>?id=<?php echo $response->id; ?>">
                        <?php 
                            if($response->approved == 1){
                                echo __('View', 'ticketmachine'); 
                            }else{
                                echo __('Preview', 'ticketmachine'); 
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