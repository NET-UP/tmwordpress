<?php 
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    global $ticketmachine_globals, $ticketmachine_api, $wpdb;

	if (isset($_POST['reject']) && is_plugin_active( 'ticketmachine-community-events/ticketmachine-community-events.php' )) {

        if ( ! isset( $_POST['ticketmachine_event_edit_form_nonce'] ) || ! wp_verify_nonce( $_POST['ticketmachine_event_edit_form_nonce'], 'ticketmachine_action_save_event' ) ) {
            print 'Sorry, your nonce did not verify.';
            exit;
        } else {
            $tm_post = $_POST;
            $table = $wpdb->prefix . 'ticketmachine_events';
            $wpdb->delete( $table, array( 'id' => $tm_post['old_id'] ) );
            ?>

            <div class="notice notice-success is-dismissable">
                <p>
                    <?php echo __('Event rejected', 'ticketmachine-event-manager'); ?>!
                </p>
            </div>
            <script> location.href='<?php echo admin_url('admin.php?page=ticketmachine_communityevents_events&status=review'); ?>'; </script>";

            <?php
        }
    }

	if (isset($_POST['submit'])) {
        if( current_user_can('edit_posts') ) {

            if ( ! isset( $_POST['ticketmachine_event_edit_form_nonce'] ) || ! wp_verify_nonce( $_POST['ticketmachine_event_edit_form_nonce'], 'ticketmachine_action_save_event' ) ) {
                print 'Sorry, your nonce did not verify.';
                exit;
            } else {
                $tm_post = $_POST;
                $errors = array();

                if(empty($tm_post['rules']['shown'])) {
                    $tm_post['rules']['shown'] = 0;
                }
                if(empty($tm_post['rules']['prices_shown'])) {
                    $tm_post['rules']['prices_shown'] = 0;
                }
                if(empty($tm_post['rules']['sale_active'])) {
                    $tm_post['rules']['sale_active'] = 0;
                }
                if(!isset($tm_post['approved'])) {
                    $tm_post['approved'] = 0;
                }
                if(isset($tm_post['tags'])) {
                    $tm_post['tags'] = explode(",", $tm_post['tags']);
                    array_walk($tm_post['tags'], function(&$value) {
                        $value = sanitize_text_field($value);
                    });
                }
                if(isset($tm_post['entrytime'])) {
                    $tm_post['entrytime'] = sanitize_text_field(ticketmachine_i18n_reverse_date($tm_post['entrytime']['date'] . $tm_post['entrytime']['time']));
                }else{
                    $errors[] = "No entry time was set";
                }
                if(isset($tm_post['ev_date'])) {
                    $tm_post['ev_date'] = sanitize_text_field(ticketmachine_i18n_reverse_date($tm_post['ev_date']['date'] . $tm_post['ev_date']['time']));
                }else{
                    $errors[] = "No start time was set";
                }
                if(isset($tm_post['endtime'])) {
                    $tm_post['endtime'] = sanitize_text_field(ticketmachine_i18n_reverse_date($tm_post['endtime']['date'] . $tm_post['endtime']['time']));
                }else{
                    $errors[] = "No end time was set";
                }

                if(!empty($tm_post['ev_name'])) {
                    $tm_post['ev_name'] = sanitize_text_field($tm_post['ev_name']);
                }else{
                    $errors[] = "No event title was set";
                }

                if(empty($tm_post['artist'])) {
                    $tm_post['artist'] = "";
                }

                if(isset($tm_post['event_img_url']) && strlen($tm_post['event_img_url']) > 1) {
                    $pos = strrpos($tm_post['event_img_url'], '/') + 1;
                    $tm_post['event_img_url'] = substr($tm_post['event_img_url'], 0, $pos) . urlencode(substr($tm_post['event_img_url'], $pos));
                }

                if(isset($tm_post['description'])) {
                    $tm_post['description'] = sanitize_text_field(strip_shortcodes($tm_post['description']));
                }

                if(!empty($tm_post['id'])) {
                    $tm_post['id'] = absint($tm_post['id']);
                }
                if(isset($tm_post['vat_id'])){
                    $tm_post['vat_id'] = 1;
                }

                if(empty($ticketmachine_globals->organizer_id) || !is_int($ticketmachine_globals->organizer_id)){
                    $errors[] = "No organizer id could be found";
                }

                if(empty($tm_post['organizer']['og_name'])) {
                    unset($tm_post['organizer']);
                }
                
                if(empty($errors)){
                    if(!empty($tm_post['organizer'])){
                        $organizer = $tm_post['organizer'];
                        unset($tm_post['organizer']);
                    }
                    $tm_post['organizer_id'] = absint($ticketmachine_globals->organizer_id);
                    $tm_post['approved'] = absint($tm_post['approved']);
                    $tm_post['rules']['shown'] = absint($tm_post['rules']['shown']);
                    $tm_post['rules']['sale_active'] = absint($tm_post['rules']['sale_active']);
                    $tm_post['vat_id'] = absint($tm_post['vat_id']);

                    $tm_post_json = $tm_post;
                    
                    $ticketmachine_json = ticketmachine_tmapi_event($tm_post_json, "POST");
                    $response = (object)$ticketmachine_json;
                    if(!isset($response->error)) {
                        if(isset($tm_post['old_id']) && is_plugin_active( 'ticketmachine-community-events/ticketmachine-community-events.php' )) {
                            $table = $wpdb->prefix . 'ticketmachine_events';
                            $wpdb->update($table, array('approved'=>1,'api_event_id'=>$response->id), array('id'=>$tm_post['old_id']));
                        }
                        
                        if(!empty($organizer)) {
                            $table = $wpdb->prefix . 'ticketmachine_organizers';
                            $organizer_check = $wpdb->get_row( "SELECT * FROM $table WHERE og_name = '" . $organizer['og_name'] . "'");
                            if(!empty($organizer_check)){
                                $wpdb->update($table, $organizer, array('id' => $organizer_check->id));
                                $table = $wpdb->prefix . 'ticketmachine_organizers_events_match';
                                if(isset($tm_post['old_id'])) {
                                    $wpdb->delete($table, array('local_event_id' => $tm_post['old_id']));
                                }
                                $wpdb->delete($table, array('api_event_id' => $response->id));
                                $wpdb->insert($table, array('organizer_id' => $organizer_check->id, 'api_event_id' => $response->id));
                            }else{
                                $wpdb->insert($table, $organizer);
                                $table = $wpdb->prefix . 'ticketmachine_organizers_events_match';
                                if(isset($tm_post['old_id'])) {
                                    $wpdb->delete($table, array('local_event_id' => $tm_post['old_id']));
                                }
                                $wpdb->delete($table, array('api_event_id' => $response->id));
                                $wpdb->insert($table, array('organizer_id' => $wpdb->insert_id, 'api_event_id' => $response->id));
                            }
                        }
                    }else{
                        print_r("<pre>");
                        print_r($tm_post);
                        print_r("</pre>");
                    }

                }
            }
    
        ?>

        <?php if(isset($response->model_error[0]['error_code']) && strlen($response->model_error[0]['error_code']) > 0){ ?>
            <div class="notice notice-error is-dismissable">
                <p><?php echo __($response->model_error[0]['error_message']); ?></p>
            </div>
        <?php }elseif(isset($response->error)) { ?>
            <div class="notice notice-error is-dismissable">
                <p><?php echo __($response->error['error_message']); ?></p>
            </div>
        <?php }elseif(empty($ticketmachine_json) || !empty($errors)){ ?>
            <div class="notice notice-error is-dismissable">
                <p><?php echo __('Something went wrong', 'ticketmachine-event-manager'); ?>!</p>
            </div>
        <?php }else{ ?>
            <div class="notice notice-success is-dismissable">
                <p>
                    <?php echo __('Event saved', 'ticketmachine-event-manager'); ?>!
                    &nbsp;-&nbsp;
                    <a target="_blank" href="/<?php echo esc_html($ticketmachine_globals->event_slug); ?>?id=<?php echo esc_html($response->id); ?>">
                        <?php 
                            if($response->approved == 1){
                                echo __('View', 'ticketmachine-event-manager'); 
                            }else{
                                echo __('Preview', 'ticketmachine-event-manager'); 
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