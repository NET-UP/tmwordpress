<?php 
    global $globals, $api;
    
    if(isset($_GET['id'])){
        $params = [ "id" => $_GET['id'] ];
        $tm_json_a = tmapi_event($params);
        $_POST = (array)$tm_json_a;

        $_POST['id'] = $_GET['id'];
        $_POST['organizer_id'] = $globals->organizer_id;
        $_POST['approved'] = 1 - $_POST['approved'];
        $_POST['rules']['shown'] = $_POST['approved'];
        
        $post_json = json_encode($_POST);
        $tm_json = tmapi_event($post_json, "POST");
        $response = (object)$tm_json;
    }
?>

<?php if(isset($response->model_error[0]['error_code']) && strlen($response->model_error[0]['error_code']) > 0){ ?>
    <div class="notice notice-error is-dismissable">
        <p><?php echo __($response->error_message); ?></p>
    </div>
<?php }elseif(empty($tm_json)){ ?>
    <div class="notice notice-error is-dismissable">
        <p><?php echo __('Etwas ist schiefgelaufen!'); ?></p>
    </div>
<?php }else{ ?>
    <div class="notice notice-success is-dismissable">
        <p><?php echo __('Gespeichert!'); ?></p>
    </div>
<?php } ?>
