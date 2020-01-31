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
    }
    if(isset($_POST['entrytime'])) {
        $_POST['entrytime'] = date(DATE_ISO8601, strtotime($_POST['entrytime']['date'] . $_POST['entrytime']['time']));
    }
    if(isset($_POST['ev_date'])) {
        $_POST['ev_date'] = date(DATE_ISO8601, strtotime($_POST['ev_date']['date'] . $_POST['ev_date']['time']));
    }
    if(isset($_POST['endtime'])) {
        $_POST['endtime'] = date(DATE_ISO8601, strtotime($_POST['endtime']['date'] . $_POST['endtime']['time']));
    }

    if(isset($_POST['description'])) {
        $_POST['description'] = strip_shortcodes($_POST['description']);
    }

    if($_POST['id'] > 0) {
        $_POST['id'] = (int)$_POST['id'];
    }
    if(isset($_POST['vat_id'])){
        $_POST['vat_id'] = 1;
    }
    $_POST['organizer_id'] = (int)$_POST['organizer_id'];
    $_POST['approved'] = (int)$_POST['approved'];
    $_POST['rules']['shown'] = (int)$_POST['rules']['shown'];
    $_POST['rules']['sale_active'] = (int)$_POST['rules']['sale_active'];
    $_POST['vat_id'] = (int)$_POST['vat_id'];

    $post = stripslashes($post);

    $post_json = json_encode($post, JSON_UNESCAPED_SLASHES);
    
    $tm_json = tmapi_event($post_json, "POST");
    $response = (object)$tm_json;
    
    echo "<pre>" . htmlentities(print_r($_POST, true)) . "</pre>";
?>


<?php if(isset($response->model_error[0]['error_code']) && strlen($response->model_error[0]['error_code']) > 0){ ?>
    <div class="notice notice-error is-dismissable">
        <p><?php echo __($response->model_error[0]['error_message']); ?></p>
    </div>
<?php }elseif(empty($tm_json)){ ?>
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