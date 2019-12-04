<?php 
    global $globals, $api;
    
    if(isset($_GET['id'])){
        $_POST['id'] = $_GET['id'];
        $_POST['organizer_id'] = $globals->organizer_id;
        
        $post_json = json_encode($_POST);
        $tm_json = apiRequest($api->delete_single_event, $post_json, "POST");
        $response = (object)$tm_json['model_error'][0];
    }
?>

<?php if(strlen($response->error_code) > 0){ ?>
    <div class="notice notice-error is-dismissable">
        <p><?php echo __($response->error_message); ?></p>
    </div>
<?php }elseif(empty($response)){ ?>
    <div class="notice notice-error is-dismissable">
        <p><?php echo __('Etwas ist schiefgelaufen!'); ?></p>
    </div>
<?php }else{ ?>
    <div class="notice notice-success is-dismissable">
        <p><?php echo __('Gespeichert!'); ?></p>
    </div>
<?php } ?>

<pre><?php print_r($tm_json); ?></pre>