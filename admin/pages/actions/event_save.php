<?php 
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    global $ticketmachine_globals, $ticketmachine_api, $wpdb;

	if (isset($_POST['reject']) && is_plugin_active( 'ticketmachine-community-events/ticketmachine-community-events.php' )) {

        if ( ! isset( $_POST['ticketmachine_event_edit_form_nonce'] ) || ! wp_verify_nonce( $_POST['ticketmachine_event_edit_form_nonce'], 'ticketmachine_action_save_event' ) ) {
            print 'Sorry, your nonce did not verify.';
            exit;
        } else {
            $ticketmachine_post = $_POST;
            $table = $wpdb->prefix . 'ticketmachine_events';
            $wpdb->delete( $table, array( 'id' => $ticketmachine_post['old_id'] ) );
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
                $ticketmachine_post = $_POST;
                $errors = array();

                if(empty($ticketmachine_post['rules']['shown'])) {
                    $ticketmachine_post['rules']['shown'] = 0;
                }
                if(empty($ticketmachine_post['rules']['prices_shown'])) {
                    $ticketmachine_post['rules']['prices_shown'] = 0;
                }
                if(empty($ticketmachine_post['rules']['sale_active'])) {
                    $ticketmachine_post['rules']['sale_active'] = 0;
                }
                if(!isset($ticketmachine_post['approved'])) {
                    $ticketmachine_post['approved'] = 0;
                }
                if(isset($ticketmachine_post['tags'])) {
                    $ticketmachine_post['tags'] = explode(",", $ticketmachine_post['tags']);
                    array_walk($ticketmachine_post['tags'], function(&$value) {
                        $value = sanitize_text_field($value);
                    });
                }
                if(isset($ticketmachine_post['entrytime'])) {
                    $ticketmachine_post['entrytime'] = sanitize_text_field(ticketmachine_i18n_reverse_date($ticketmachine_post['entrytime']['date'] . $ticketmachine_post['entrytime']['time']));
                }else{
                    $errors[] = "No entry time was set";
                }
                if(isset($ticketmachine_post['ev_date'])) {
                    $ticketmachine_post['ev_date'] = sanitize_text_field(ticketmachine_i18n_reverse_date($ticketmachine_post['ev_date']['date'] . $ticketmachine_post['ev_date']['time']));
                }else{
                    $errors[] = "No start time was set";
                }
                if(isset($ticketmachine_post['endtime'])) {
                    $ticketmachine_post['endtime'] = sanitize_text_field(ticketmachine_i18n_reverse_date($ticketmachine_post['endtime']['date'] . $ticketmachine_post['endtime']['time']));
                }else{
                    $errors[] = "No end time was set";
                }

                if(!empty($ticketmachine_post['ev_name'])) {
                    $ticketmachine_post['ev_name'] = sanitize_text_field($ticketmachine_post['ev_name']);
                }else{
                    $errors[] = "No event title was set";
                }

                if(empty($ticketmachine_post['artist'])) {
                    $ticketmachine_post['artist'] = "";
                }

                if(isset($ticketmachine_post['event_img_url']) && strlen($ticketmachine_post['event_img_url']) > 1) {
                    $pos = strrpos($ticketmachine_post['event_img_url'], '/') + 1;
                    $ticketmachine_post['event_img_url'] = substr($ticketmachine_post['event_img_url'], 0, $pos) . urlencode(substr($ticketmachine_post['event_img_url'], $pos));
                }

                if(isset($ticketmachine_post['description'])) {
                    $ticketmachine_post['description'] = sanitize_text_field(strip_shortcodes($ticketmachine_post['description']));
                }

                if(!empty($ticketmachine_post['id'])) {
                    $ticketmachine_post['id'] = absint($ticketmachine_post['id']);
                }
                if(isset($ticketmachine_post['vat_id'])){
                    $ticketmachine_post['vat_id'] = 1;
                }

                if(empty($ticketmachine_globals->organizer_id) || !is_int($ticketmachine_globals->organizer_id)){
                    $errors[] = "No organizer id could be found";
                }

                if(empty($ticketmachine_post['organizer']['og_name'])) {
                    unset($ticketmachine_post['organizer']);
                }
                
                if(empty($errors)){
                    if(!empty($ticketmachine_post['organizer'])){
                        $organizer = $ticketmachine_post['organizer'];
                        unset($ticketmachine_post['organizer']);
                    }
                    $ticketmachine_post['organizer_id'] = absint($ticketmachine_globals->organizer_id);
                    $ticketmachine_post['approved'] = absint($ticketmachine_post['approved']);
                    $ticketmachine_post['rules']['shown'] = absint($ticketmachine_post['rules']['shown']);
                    $ticketmachine_post['rules']['sale_active'] = absint($ticketmachine_post['rules']['sale_active']);
                    $ticketmachine_post['vat_id'] = absint($ticketmachine_post['vat_id']);

                    $ticketmachine_post_json = $ticketmachine_post;
                    
                    $ticketmachine_json = ticketmachine_tmapi_event($ticketmachine_post_json, "POST");
                    $response = (object)$ticketmachine_json;
                    if(!isset($response->error)) {
                        if(isset($ticketmachine_post['old_id']) && is_plugin_active( 'ticketmachine-community-events/ticketmachine-community-events.php' )) {
                            $table = $wpdb->prefix . 'ticketmachine_events';
                            $wpdb->update($table, array('approved'=>1,'api_event_id'=>$response->id), array('id'=>$ticketmachine_post['old_id']));
                        }
                        
                        if(!empty($organizer)) {
                            $table = $wpdb->prefix . 'ticketmachine_organizers';
                            $organizer_check = $wpdb->get_row( "SELECT * FROM $table WHERE og_name = '" . $organizer['og_name'] . "'");
                            if(!empty($organizer_check)){
                                $wpdb->update($table, $organizer, array('id' => $organizer_check->id));
                                $table = $wpdb->prefix . 'ticketmachine_organizers_events_match';
                                if(isset($ticketmachine_post['old_id'])) {
                                    $wpdb->delete($table, array('local_event_id' => $ticketmachine_post['old_id']));
                                }
                                $wpdb->delete($table, array('api_event_id' => $response->id));
                                $wpdb->insert($table, array('organizer_id' => $organizer_check->id, 'api_event_id' => $response->id));
                            }else{
                                $wpdb->insert($table, $organizer);
                                $table = $wpdb->prefix . 'ticketmachine_organizers_events_match';
                                if(isset($ticketmachine_post['old_id'])) {
                                    $wpdb->delete($table, array('local_event_id' => $ticketmachine_post['old_id']));
                                }
                                $wpdb->delete($table, array('api_event_id' => $response->id));
                                $wpdb->insert($table, array('organizer_id' => $wpdb->insert_id, 'api_event_id' => $response->id));
                            }
                        }
                        
                        //Upload image
                        if(!empty($ticketmachine_post['event_img_url'])) {  
                            $imageResult = ticketmachine_tmapi_update_event_image( 
                                $response->id, 
                                $ticketmachine_post['event_img_url']
                            );

                            if ( is_wp_error( $imageResult ) ) {
                                print_r("<pre>");
                                print_r($imageResult);
                                print_r("</pre>");
                            }
                            
                        }
                        
                    }else{
                        print_r("<pre>");
                        print_r($ticketmachine_post);
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