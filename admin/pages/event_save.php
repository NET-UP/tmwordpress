<?php 
    global $globals, $api;

    if(!isset($_POST['shown'])) {
        $_POST['shown'] = 0;
    }
    if(isset($_POST['tags'])) {
        $_POST['tags'] = explode(",", $_POST['tags']);
    }
    if(isset($_POST['entrytime'])) {
        $_POST['entrytime'] = new DateTime(strtotime($_POST['entrytime']['date'] . $_POST['entrytime']['time']));
        $_POST['entrytime'] = $_POST['entrytime']->format(DateTime::ISO8601);
    }
    if(isset($_POST['ev_date'])) {
        $_POST['ev_date'] = new DateTime(strtotime($_POST['ev_date']['date'] . $_POST['ev_date']['time']));
        $_POST['ev_date'] = $_POST['ev_date']->format(DateTime::ISO8601);
    }
    if(isset($_POST['endtime'])) {
        $_POST['endtime'] = new DateTime(strtotime($_POST['endtime']['date'] . $_POST['endtime']['time']));
        $_POST['endtime'] = $_POST['endtime']->format(DateTime::ISO8601);
    }
    
    #$tm_json = apiRequest($api->get_single_event, TRUE);
?>

<pre>
<?php print_r($_POST); ?>
</pre>