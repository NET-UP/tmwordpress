<?php
    global $globals, $api;
    $tm_json = apiRequest($api->get_single_event);
    $event = (object)$tm_json;
    print_r($event);
?>

<div class="wrap">
    <h2>TicketMachine > 

    </h2>
</div>
<?php
    
?>