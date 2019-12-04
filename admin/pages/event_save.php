<?php 
    global $globals, $api;

    if(!isset($_POST['shown'])) {
        $_POST['shown'] = 0;
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

    if($_POST['id'] > 0) {
        $_POST['id'] = (int)$_POST['id'];
    }
    $_POST['organizer_id'] = (int)$_POST['organizer_id'];
    $_POST['approved'] = (int)$_POST['approved'];
    $_POST['shown'] = (int)$_POST['shown'];

    $post_json = json_encode($_POST);
    
    $tm_json = apiRequest($api->get_single_event_no_categories, $post_json, "POST");
    $response = (object)$tm_json['model_error'][0];
?>

<?php if(strlen($response->error_code) > 0){ ?>
    <div class="notice notice-error is-dismissable">
        <p><?php echo __('Etwas ist schiefgelaufen.'); ?></p>
    </div>
<?php }else{ ?>
    <div class="notice notice-success is-dismissable">
        <p><?php echo __('Gespeichert!'); ?></p>
    </div>
<?php } ?>

<pre>
    <?php print_r($tm_json); ?>
</pre>