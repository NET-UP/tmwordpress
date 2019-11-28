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
    <div class="col-xs-12">
        <div class="row">
            <label for="ev_">Veranstaltungsort</label>
            <input type="text">
        </div>
        <div class="row">
            <div col-xs-8>
                <label for="street">Straße</label>
                <input type="text">
            </div>
            <div col-xs-4>
                <label for="house_number">Haus-Nr.</label>
                <input type="text">
            </div>
        </div>
        <div class="row">
            <div col-xs-4>
                <label for="zip">PLZ</label>
                <input type="text">
            </div>
            <div col-xs-8>
                <label for="city">Ort</label>
                <input type="text">
            </div>
        </div>
        <div class="row">
            <label for="country">Land</label>
            <input type="text">
        </div>
    </div>
    <div class="col-xs-12">
        <div class="row">
            <div col-xs-4>
                <label>Einlasszeit</label>
            </div>
            <div col-xs-4>
                <div>
                    <input type="text" class="form-control event_date hasDatepicker">
                    <label for="event_time_entry_date" class="input-group-addon w50">
                        <i class="fa fa-calendar"></i>
                    </label>
                </div>
            </div>
            <div col-xs-4>
                <div class="input-group time">
                    <input type="text" class="form-control">
                    <label for="event_time_entry_time" class="input-group-addon w50">
                        <i class="fa fa-clock-o"></i>
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <div col-xs-4>
                <label>Veranstaltungsbeginn</label>
            </div>
            <div col-xs-4>
                <div>
                    <input type="text" class="form-control event_date hasDatepicker">
                    <label for="event_time_start_date" class="input-group-addon w50">
                        <i class="fa fa-calendar"></i>
                    </label>
                </div>
            </div>
            <div col-xs-4>
                <div class="input-group time">
                    <input type="text" class="form-control">
                    <label for="event_time_start_date" class="input-group-addon w50">
                        <i class="fa fa-clock-o"></i>
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <div col-xs-4>
                <label>Veranstaltungsende</label>
            </div>
            <div col-xs-4>
                <div>
                    <input type="text" class="form-control event_date hasDatepicker">
                    <label for="event_edit_end_date" class="input-group-addon w50">
                        <i class="fa fa-calendar"></i>
                    </label>
                </div>
            </div>
            <div col-xs-4>
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