<?php 
    header("Content-Type: text/plain"); 
    require_once('../../../../wp-load.php');

    global $wpdb;

    $rows = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}ticketmachine_log");

    foreach ($rows as $row) {
        echo "[" . $row->log_time . "] - " . $row->log_type . "\n";
        echo $row->log_message . "]\n\n";
    }
?>

