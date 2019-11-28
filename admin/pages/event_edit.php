<?php
    global $globals, $api;
    $tm_json = apiRequest($api->get_single_event);
    $event = (object)$tm_json;
?>
<?php
    print_r($event);
?>

<div class="wrap">
    <h2>TicketMachine > Veranstaltung bearbeiten</h2>  <!-- needs create/update -->
    <label class="screen-reader-text" id="event-name-prompt-text" for="ev_name">Event Name hier eingeben</label>
    <input type="text" name="ev_name" size="30" value="" id="ev_name" spellcheck="true" autocomplete="off" value="<?php echo $event->ev_name; ?>"> <!-- load event name to value -->
    <?php 
        $content = '';
        $editor_id = 'mycustomeditor';

        wp_editor( $content, $editor_id );
    ?>

    <div class="col-xs-12">
        <label for="event_edit_locationname">Veranstaltungsort</label>
        <input id="event_location_name" type="text" class="form-control">
        <div class="row">
            <div class="col-xs-8">
                <label for="event_edit_strasse">Straße</label>
                <input id="event_edit_strasse" type="text" class="form-control" value="">
            </div>
            <div class="col-xs-4">
                <label for="house_number">Haus-Nr.</label>
                <input id="event_edit_hausnr" type="text" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <label for="event_edit_plz">PLZ</label>
                <input id="event_edit_plz" type="text" class="form-control">
            </div>
            <div class="col-xs-8">
                <label for="event_edit_ort">Ort</label>
                <input id="event_edit_ort" type="text" class="form-control">
            </div>
        </div>
        <div class="row">
            <label for="event_edit_land">Land</label>
            <input id="event_edit_land" type="text" class="form-control">
        </div>
    </div>

    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-4">
                <label>Einlasszeit</label>
            </div>
            <div class="col-xs-4">
                <div class="input-group">
                    <input type="text" class="form-control event_date hasDatepicker">
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
                <label>Veranstaltungsbeginn</label>
            </div>
            <div class="col-xs-4">
                <div class="input-group">
                    <input type="text" class="form-control event_date hasDatepicker">
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
                <label>Veranstaltungsende</label>
            </div>
            <div class="col-xs-4">
                <div class="input-group">
                    <input type="text" class="form-control event_date hasDatepicker">
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
<?php
    
?>