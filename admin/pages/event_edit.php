<?php
    global $globals, $api;
    $tm_json = apiRequest($api->get_single_event);
    $event = (object)$tm_json;
?>

<div class="wrap">
    <?php 
        if(isset($_GET['id'])){
            echo "<h1 class='wp-heading-inline'>TicketMachine > " . __('Veranstaltung bearbeiten', 'ticketmachine') . "</h1>";
        } else {
            echo "<h1 class='wp-heading-inline'>TicketMachine > " . __('Veranstaltung erstellen', 'ticketmachine') . "</h1>";
        }
    ?>
    <form name="event" action="" method="post" id="event">
        <div id="eventstuff">
            <div id="event-body" class="metabox-holder columns-2">
                <div id="event-body-content" style="position: relative;">
                    <div id="eventdiv">
                        <div id="eventwrap">
                            <label class="screen-reader-text" id="event-name-prompt-text" for="ev_name"><?php echo __('Event Name hier eingeben', 'ticketmachine') ?></label>
                            <input type="text" name="ev_name" size="30" id="ev_name" spellcheck="true" autocomplete="off" value="<?php echo $event->ev_name; ?>">
                        </div>
                    </div>

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
                <div id="postbox-container-1" class="postbox-container">
                    <div id="side-sortables" class="meta-box-sortables ui-sortable">
                        <div id="submitdiv" class="postbox">
                            <button type="button" class="handlediv" aria-expanded="true">
                                <span class="screen-reader-text">Bedienfeld umschalten: Veröffentlichen</span>
                                <span class="toggle-indicator" aria-hidden="true"></span>
                            </button>
                            <h2 class="hndle ui-sortable-handle">
                                <span>Veröffentlichen</span>
                            </h2>
                            <div class="inside">
                                <div class="submitbox" id="submitpost">
                                    <div id="minor-publishing">
                                        <div id="misc-publishing-actions">
                                            <div class="misc-pub-section misc-pub-post-status">
                                                <span>Status: </span>
                                                <select>
                                                    <option value="Entwurf">Entwurf</option>
                                                </select>
                                            </div>
                                            <div class="misc-pub-section misc-pub-section misc-pub-visibility">
                                                <input checked="checked" type="checkbox">
                                                <span>Aus Veranstaltung-Liste ausblenden</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="major-publishing-actions">
                                        <div id="publishing-action">
                                            <span class="spinner"></span>
                                            <input name="save" type="submit" class="button button-primary button-large" id="publish" value="Aktualisieren">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="eventimagediv" class="postbox">
                            <button type="button" class="handlediv" aria-expanded="true">
                                <span class="screen-reader-text">Bedienfeld umschalten: Eventbild</span>
                                <span class="toggle-indicator" aria-hidden="true"></span>
                            </button>
                            <h2 class="hndle ui-sortable-handle">
                                <span>Beitragsbild</span>
                            </h2>
                            <div class="inside">
                                <p class="hide-if-no-js">
                                    <a href="" id="set-event-thumbnail" class="thickbox">Eventbild festlegen</a> <!-- needs correct href -->
                                </p>
                                <input type="hidden" id="_thumbnail_id" name="_thumbnail_id" value="-1">
                            </div>
                        </div>
                        <div id="eventtags" class="postbox">
                            <button type="button" class="handlediv" aria-expanded="true">
                                <span class="screen-reader-text">Bedienfeld umschalten: Seiten-Attribute</span>
                                <span class="toggle-indicator" aria-hidden="true"></span>
                            </button>
                            <h2 class="hndle ui-sortable-handle">
                                <span>Schlagwörter</span>
                            </h2>
                            <input type="text">
                            <button type="button">OK</button>
                            <span>Schlagwörter durch Kommas trennen.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>