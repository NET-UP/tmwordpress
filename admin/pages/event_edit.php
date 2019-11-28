<?php
    global $globals, $api;
    $tm_json = apiRequest($api->get_single_event);
    $event = (object)$tm_json;
    print_r($event);
?>

<div class="wrap">
    <h2>TicketMachine > Veranstaltung bearbeiten</h2>  <!-- needs create/update -->
    <input type="text" name="ev_name" size="30" value="" id="ev_name" spellcheck="true" autocomplete="off">
    <?php 
        $content = '';
        $editor_id = 'mycustomeditor';

    wp_editor( $content, $editor_id );
    ?>
</div>
<?php
    
?>