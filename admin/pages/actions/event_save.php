<?php 
    global $globals, $api;

    if(!isset($_POST['rules']['shown'])) {
        absint($_POST['rules']['shown']) = 1;
    }
    if(!isset($_POST['approved'])) {
        absint($_POST['approved']) = 0;
    }
    if(isset($_POST['tags'])) {
        sanitize_text_field($_POST['tags']) = explode(",", $_POST['tags']);
    }
    if(isset($_POST['entrytime'])) {
        sanitize_text_field($_POST['entrytime']) = ticketmachine_i18n_reverse_date($_POST['entrytime']['date'] . $_POST['entrytime']['time']);
    }
    if(isset($_POST['ev_date'])) {
        sanitize_text_field($_POST['ev_date']) = ticketmachine_i18n_reverse_date($_POST['ev_date']['date'] . $_POST['ev_date']['time']);
    }
    if(isset($_POST['endtime'])) {
        sanitize_text_field($_POST['endtime']) = ticketmachine_i18n_reverse_date($_POST['endtime']['date'] . $_POST['endtime']['time']);
    }

    if(isset($_POST['description'])) {
        sanitize_text_field($_POST['description']) = strip_shortcodes($_POST['description']);
    }

    if(!empty($_POST['id'])) {
        absint($_POST['id']) = (int)absint($_POST['id']);
    }
    if(isset($_POST['vat_id'])){
        absint($_POST['vat_id']) = 1;
    }
    absint($_POST['organizer_id']) = (int)absint($_POST['organizer_id']);
    absint($_POST['approved']) = (int)absint($_POST['approved']);
    absint($_POST['rules']['shown']) = (int)absint($_POST['rules']['shown']);
    absint($_POST['rules']['sale_active']) = (int)absint($_POST['rules']['sale_active']);
    absint($_POST['vat_id']) = (int)absint($_POST['vat_id']);

    $post_json = json_encode(
        str_replace("\r\n", "<br>", str_replace("&nbsp;", "", str_replace('\"', "'", $_POST))), 
        JSON_UNESCAPED_SLASHES
    );
    
    $ticketmachine_json = ticketmachine_tmapi_event($post_json, "POST");
    $response = (object)$ticketmachine_json;
    
?>


<?php if(isset($response->model_error[0]['error_code']) && strlen($response->model_error[0]['error_code']) > 0){ ?>
    <div class="notice notice-error is-dismissable">
        <p><?php echo __($response->model_error[0]['error_message']); ?></p>
    </div>
<?php }elseif(empty($ticketmachine_json)){ ?>
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
<?php } ?>