<?php 
    global $globals, $api;

    if(!isset($_POST['rules']['shown'])) {
        $_POST['rules']['shown'] = 1;
    }
    if(!isset($_POST['approved'])) {
        $_POST['approved'] = 0;
    }
    if(isset($_POST['tags'])) {
        $_POST['tags'] = explode(",", $_POST['tags']);
        array_walk($_POST['tags'], function(&$value, &$key) {
            $value = sanitize_text_field($value);
        });
    }
    if(isset($_POST['entrytime'])) {
        $_POST['entrytime'] = sanitize_text_field(ticketmachine_i18n_reverse_date($_POST['entrytime']['date'] . $_POST['entrytime']['time']));
    }
    if(isset($_POST['ev_date'])) {
        $_POST['ev_date'] = sanitize_text_field(ticketmachine_i18n_reverse_date($_POST['ev_date']['date'] . $_POST['ev_date']['time']));
    }
    if(isset($_POST['endtime'])) {
        $_POST['endtime'] = sanitize_text_field(ticketmachine_i18n_reverse_date($_POST['endtime']['date'] . $_POST['endtime']['time']));
    }

    if(isset($_POST['description'])) {
        $_POST['description'] = sanitize_text_field(strip_shortcodes($_POST['description']));
    }

    if(!empty($_POST['id'])) {
        $_POST['id'] = absint($_POST['id']);
    }
    if(isset($_POST['vat_id'])){
        $_POST['vat_id'] = 1;
    }
    $_POST['organizer_id'] = absint($_POST['organizer_id']);
    $_POST['approved'] = absint($_POST['approved']);
    $_POST['rules']['shown'] = absint($_POST['rules']['shown']);
    $_POST['rules']['sale_active'] = absint($_POST['rules']['sale_active']);
    $_POST['vat_id'] = absint($_POST['vat_id']);

    $post_json = $_POST;

    print_r($post_json);
    
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