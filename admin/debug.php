<?php 
    header("Content-Type: text/plain"); 
    require_once('../../../../wp-load.php');

    global $wpdb;

    $rows = $wpdb->get_results( "SELECT * FROM `ticketmachine_log`");

    foreach ($rows as $row) {
        echo "[]";
    }
?>

