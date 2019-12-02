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
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content" style="position: relative;">
                    <div id="postdiv">
                        <div id="postwrap">
                            <label class="screen-reader-text" id="post-name-prompt-text" for="ev_name"><?php echo __('Event Name hier eingeben', 'ticketmachine') ?></label>
                            <input type="text" name="ev_name" size="30" id="ev_name" spellcheck="true" autocomplete="off" value="<?php echo $event->ev_name; ?>">
                        </div>
                    </div>

                    <?php 
                        $editor_id = 'wp-content-editor-container';
                        $editor_class = 'wp-editor-container';
                        wp_editor( $event->ev_description, $editor_id, $editor_class);
                    ?>

                    <div id="test">
                        <div class="postbox">
                            <h2 class="hndle">
                                <span>Standort</span>
                            </h2>
                            <div class="inside">
                                <label for="event_edit_locationname"><?php echo __('Veranstaltungsort', 'ticketmachine') ?></label>
                                <input id="event_location_name" type="text" class="form-control" value="<?php echo $event->ev_location_name; ?>">
                                <div>
                                    <div>
                                        <label for="event_edit_strasse"><?php echo __('Straße', 'ticketmachine') ?></label>
                                        <input id="event_edit_strasse" type="text" class="form-control" value="<?php echo $event->street; ?>">
                                    </div>
                                    <div>
                                        <label for="house_number"><?php echo __('Haus-Nr.', 'ticketmachine') ?></label>
                                        <input id="event_edit_hausnr" type="text" class="form-control" value="<?php echo $event->house_number; ?>">
                                    </div>
                                </div>
                                <div>
                                    <div>
                                        <label for="event_edit_plz"><?php echo __('PLZ', 'ticketmachine') ?></label>
                                        <input id="event_edit_plz" type="text" class="form-control" value="<?php echo $event->zip; ?>">
                                    </div>
                                    <div>
                                        <label for="event_edit_ort"><?php echo __('Ort', 'ticketmachine') ?></label>
                                        <input id="event_edit_ort" type="text" class="form-control" value="<?php echo $event->city; ?>">
                                    </div>
                                </div>
                                <div>
                                    <label for="event_edit_land"><?php echo __('Land', 'ticketmachine') ?></label>
                                    <input id="event_edit_land" type="text" class="form-control" value="<?php echo $event->country; ?>">
                                </div>
                            </div>
                        </div>
                    

                        <div class="postbox">
                            <h2 class="hndle">
                                <span>Termine</span>
                            </h2>
                            <div class="inside">
                                <div>
                                    <label><?php echo __('Einlasszeit', 'ticketmachine') ?></label>
                                </div>
                                <div>
                                    <div class="input-group">
                                        <input type="text" class="form-control event_date hasDatepicker" value="<?php echo $event->entrytime; ?>">
                                        <label for="event_time_entry_date" class="input-group-addon w50">
                                            <i class="fa fa-calendar"></i>
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <div class="input-group time">
                                        <input type="text" class="form-control">
                                        <label for="event_time_entry_time" class="input-group-addon w50">
                                            <i class="fa fa-clock-o"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <label><?php echo __('Veranstaltungsbeginn', 'ticketmachine') ?></label>
                                </div>
                                <div>
                                    <div class="input-group">
                                        <input type="text" class="form-control event_date hasDatepicker" value="<?php echo $event->ev_date; ?>">
                                        <label for="event_time_start_date" class="input-group-addon w50">
                                            <i class="fa fa-calendar"></i>
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <div class="input-group time">
                                        <input type="text" class="form-control">
                                        <label for="event_time_start_date" class="input-group-addon w50">
                                            <i class="fa fa-clock-o"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <label><?php echo __('Veranstaltungsende', 'ticketmachine') ?></label>
                                </div>
                                <div>
                                    <div class="input-group">
                                        <input type="text" class="form-control event_date hasDatepicker" value="<?php echo $event->endtime; ?>">
                                        <label for="event_edit_end_date" class="input-group-addon w50">
                                            <i class="fa fa-calendar"></i>
                                        </label>
                                    </div>
                                </div>
                                <div>
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
                </div>

                <div id="postbox-container-1" class="postbox-container">
                    <div id="side-sortables" class="meta-box-sortables ui-sortable">
                        <div id="submitdiv" class="postbox">
                            <h2 class="hndle">
                                <span><?php echo __('Veröffentlichen', 'ticketmachine') ?></span>
                            </h2>
                            <div class="inside">
                                <div class="submitbox" id="submitpost">
                                    <div id="minor-publishing">
                                        <div id="misc-publishing-actions">
                                            <div class="misc-pub-section misc-pub-post-status">
                                                <span>Status: </span>
                                                <select>
                                                    <option value="Published"><?php echo __('Veröffentlicht', 'ticketmachine') ?></option>
                                                    <option value="Draft"><?php echo __('Entwurf', 'ticketmachine') ?></option>
                                                </select>
                                            </div>
                                            <div class="misc-pub-section misc-pub-section misc-pub-visibility">
                                                <input checked="checked" type="checkbox">
                                                <span><?php echo __('Aus Veranstaltungs-Liste ausblenden', 'ticketmachine') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="major-publishing-actions">
                                        <div id="publishing-action">
                                            <span class="spinner"></span>
                                            <input name="save" type="submit" class="button button-primary button-large" id="publish" value="Aktualisieren">
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="postimagediv" class="postbox">
                            <h2 class="hndle">
                                <span><?php echo __('Eventbild', 'ticketmachine') ?></span>
                            </h2>
                            <div class="inside">
                                <p class="hide-if-no-js">
                                    <a href="" id="set-post-thumbnail" class="thickbox"><?php echo __('Eventbild festlegen', 'ticketmachine') ?></a> <!-- needs correct href -->
                                </p>
                                <input type="hidden" id="_thumbnail_id" name="_thumbnail_id" value="-1">
                            </div>
                        </div>
                        <div id="posttags" class="postbox">
                            <h2 class="hndle">
                                <span><?php echo __('Schlagwörter', 'ticketmachine') ?></span>
                            </h2>
                            <input type="text">
                            <button type="button">OK</button>
                            <span><?php echo __('Schlagwörter durch Kommas trennen.', 'ticketmachine') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>