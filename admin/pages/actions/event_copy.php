<?php 
    global $globals, $api;
    
    if(isset($_GET['id'])){
        absint($_POST['id']) = absint($_GET['id']);
        absint($_POST['organizer_id']) = $globals->organizer_id;
        
        $post_json = json_encode($_POST);
        $ticketmachine_json = ticketmachine_tmapi_event_copy($post_json);
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
            <?php esc_html_e('Event successfully copied', 'ticketmachine'); ?>!
            &nbsp;-&nbsp;
            <a target="_blank" href="/<?php echo $globals->event_slug; ?>?id=<?php echo $response->id; ?>">
                <?php 
                    if($response->approved == 1){
                        esc_html_e('View', 'ticketmachine'); 
                    }else{
                        esc_html_e('Preview', 'ticketmachine'); 
                    }
                ?>
            </a>
        </p>
    </div>
<?php } ?>