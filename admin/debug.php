<?php 
    header("Content-Type: text/plain"); 
    require_once('../../../../wp-load.php');

    global $wpdb;

    if(current_user_can( 'manage_options' )){
        $rows = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}ticketmachine_log ORDER BY log_time DESC LIMIT 0,35");
    
        foreach ($rows as $row) {
            echo "[" . date("c", $row->log_time) . "] - " . $row->log_type . "\n";
            $message = json_decode($row->log_message );
            echo $message . "]\n\n";
        }
    }
?>

