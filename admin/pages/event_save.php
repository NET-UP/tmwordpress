<?php 
    global $globals, $api;

    echo "test: " . date($_POST['entrytime']['date'] . " " . $_POST['entrytime']['time']);

    if(!isset($_POST['shown'])) {
        $_POST['shown'] = 0;
    }
    if(isset($_POST['tags'])) {
        $_POST['tags'] = explode(",", $_POST['tags']);
    }
    
    #$tm_json = apiRequest($api->get_single_event, TRUE);
?>

<pre>
<?php print_r($_POST); ?>
</pre>