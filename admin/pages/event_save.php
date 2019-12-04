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

    $post_json = json_encode($_POST);
    
    $tm_json = apiRequest($api->get_single_event_no_categories, $post_json);?>

<pre>
<?php print_r($post_json); ?>
<?php print_r($tm_json); ?>
</pre>