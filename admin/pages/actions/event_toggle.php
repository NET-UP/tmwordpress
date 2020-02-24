<?php 
    global $globals, $api;
    
    if(isset($_GET['id'])){
        $params = [ "id" => $_GET['id'] ];
        $ticketmachine_json_a = tmapi_event($params);
        $_POST = (array)$ticketmachine_json_a;

        $_POST['id'] = $_GET['id'];
        $_POST['organizer_id'] = $globals->organizer_id;
        $_POST['approved'] = 1 - $_POST['approved'];
        $_POST['rules']['shown'] = $_POST['approved'];
        
        $post_json = json_encode($_POST);
        $ticketmachine_json = tmapi_event($post_json, "POST");
        $response = (object)$ticketmachine_json;
    }
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
            <?php 
                if($response->approved == 1){
                    echo __('Published', 'ticketmachine'); 
                }else{
                    echo __('Deactivated', 'ticketmachine'); 
                }
            ?>!
            &nbsp;-&nbsp;
            <a href="?page=ticketmachine_events&action=deactivate&id=<?php echo $response->id; ?>"><?php echo __('Undo', 'ticketmachine'); ?></a>
        </p>
    </div>
<?php } ?>
