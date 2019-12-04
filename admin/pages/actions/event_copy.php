<?php 
    global $globals, $api;
    
    $tm_json = apiRequest($api->get_single_event_no_categories, FALSE, "POST");
    $response = (object)$tm_json['model_error'][0];
?>

<?php if(strlen($response->error_code) > 0){ ?>
    <div class="notice notice-error is-dismissable">
        <p><?php echo __($response->error_message); ?></p>
    </div>
<?php }else{ ?>
    <div class="notice notice-success is-dismissable">
        <p><?php echo __('Gespeichert!'); ?></p>
    </div>
<?php } ?>

<pre>
    <?php #print_r($_POST); ?>
</pre>