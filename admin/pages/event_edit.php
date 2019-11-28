<?php
    global $globals, $api;
    $tm_json = apiRequest($api->get_single_event);
    $event = (object)$tm_json;
?>
<?php
    print_r($event);
?>

<div class="wrap">
    <?php 
        if(isset($_GET['id'])){
            echo "<h2>TicketMachine > " . __('Veranstaltung bearbeiten', 'ticketmachine') . "</h2>";
        } else {
            echo "<h2>TicketMachine > " . __('Veranstaltung erstellen', 'ticketmachine') . "</h2>";
        }
    ?>
   
    <label class="screen-reader-text" id="event-name-prompt-text" for="ev_name"><?php echo __('Event Name hier eingeben', 'ticketmachine') ?></label>
    <input type="text" name="ev_name" size="30" id="ev_name" spellcheck="true" autocomplete="off" value="<?php echo $event->ev_name; ?>">
    <?php 
        $editor_id = 'mycustomeditor';

        wp_editor( $event->ev_description, $editor_id );
    ?>

    <div class="col-xs-12">
        <label for="event_edit_locationname"><?php echo __('Veranstaltungsort', 'ticketmachine') ?></label>
        <input id="event_location_name" type="text" class="form-control" value="<?php echo $event->ev_location_name; ?>">
        <div class="row">
            <div class="col-xs-8">
                <label for="event_edit_strasse"><?php echo __('Straße', 'ticketmachine') ?></label>
                <input id="event_edit_strasse" type="text" class="form-control" value="<?php echo $event->street; ?>">
            </div>
            <div class="col-xs-4">
                <label for="house_number"><?php echo __('Haus-Nr.', 'ticketmachine') ?></label>
                <input id="event_edit_hausnr" type="text" class="form-control" value="<?php echo $event->house_number; ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <label for="event_edit_plz"><?php echo __('PLZ', 'ticketmachine') ?></label>
                <input id="event_edit_plz" type="text" class="form-control" value="<?php echo $event->zip; ?>">
            </div>
            <div class="col-xs-8">
                <label for="event_edit_ort"><?php echo __('Ort', 'ticketmachine') ?></label>
                <input id="event_edit_ort" type="text" class="form-control" value="<?php echo $event->city; ?>">
            </div>
        </div>
        <div class="row">
            <label for="event_edit_land"><?php echo __('Land', 'ticketmachine') ?></label>
            <input id="event_edit_land" type="text" class="form-control" value="<?php echo $event->country; ?>">
        </div>
    </div>

    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-4">
                <label><?php echo __('Einlasszeit', 'ticketmachine') ?></label>
            </div>
            <div class="col-xs-4">
                <div class="input-group">
                    <input type="text" class="form-control event_date hasDatepicker" value="<?php echo $event->entrytime; ?>">
                    <label for="event_time_entry_date" class="input-group-addon w50">
                        <i class="fa fa-calendar"></i>
                    </label>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="input-group time">
                    <input type="text" class="form-control">
                    <label for="event_time_entry_time" class="input-group-addon w50">
                        <i class="fa fa-clock-o"></i>
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <label><?php echo __('Veranstaltungsbeginn', 'ticketmachine') ?></label>
            </div>
            <div class="col-xs-4">
                <div class="input-group">
                    <input type="text" class="form-control event_date hasDatepicker" value="<?php echo $event->ev_date; ?>">
                    <label for="event_time_start_date" class="input-group-addon w50">
                        <i class="fa fa-calendar"></i>
                    </label>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="input-group time">
                    <input type="text" class="form-control">
                    <label for="event_time_start_date" class="input-group-addon w50">
                        <i class="fa fa-clock-o"></i>
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <label><?php echo __('Veranstaltungsende', 'ticketmachine') ?></label>
            </div>
            <div class="col-xs-4">
                <div class="input-group">
                    <input type="text" class="form-control event_date hasDatepicker" value="<?php echo $event->endtime; ?>">
                    <label for="event_edit_end_date" class="input-group-addon w50">
                        <i class="fa fa-calendar"></i>
                    </label>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="input-group time">
                    <input type="text" class="form-control">
                    <label for="event_edit_end_date" class="input-group-addon w50">
                        <i class="fa fa-clock-o"></i>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
