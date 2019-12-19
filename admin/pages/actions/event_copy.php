<?php 
    global $globals, $api;
    
    if(isset($_GET['id'])){
        $_POST['id'] = $_GET['id'];
        $_POST['organizer_id'] = $globals->organizer_id;
        
        $post_json = json_encode($_POST);
        $tm_json = tmapi_event_copy($post_json);
        $response = (object)$tm_json;
    }
?>

<?php if(isset($response->model_error[0]['error_code']) && strlen($response->model_error[0]['error_code']) > 0){ ?>
    <div class="notice notice-error is-dismissable">
        <p><?php echo __($response->model_error[0]['error_message']); ?></p>
    </div>
<?php }elseif(empty($tm_json)){ ?>
    <div class="notice notice-error is-dismissable">
        <p><?php echo __('Etwas ist schiefgelaufen!'); ?></p>
    </div>
<?php }else{ ?>
    <div class="notice notice-success is-dismissable">
        <p><?php echo __('Event erfolgreich kopiert!'); ?></p>
    </div>
<?php } ?>