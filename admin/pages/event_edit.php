<?php
    global $globals, $api;
    $tm_json = apiRequest($api->get_single_event);
    $event = (object)$tm_json;
    print_r($event);
?>

<div class="wrap tm-admin-page">
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
                    <div id="titlediv">
                        <div id="titlewrap">
                            <label class="screen-reader-text" id="post-name-prompt-text" for="ev_name"><?php echo __('Event Name hier eingeben', 'ticketmachine') ?></label>
                            <input type="text" name="ev_name" size="30" id="title" spellcheck="true" autocomplete="off" value="<?php echo $event->ev_name; ?>">
                        </div>
                    </div>

                    <?php 
                        $editor_id = 'wp-content-editor-container';
                        $editor_class = 'wp-editor-container';
                        wp_editor( $event->ev_description, $editor_id, $editor_class);
                    ?>
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
                                                <select style="float: right; margin-top: -2px;">
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
                        <div id="tagsdiv-post_tag" class="postbox">
                            <h2 class="hndle">
                                <span><?php echo __('Schlagwörter', 'ticketmachine') ?></span>
                            </h2>
                            <div class="inside">
                                <div class="tagsdiv" id="post_tag">
                                    <div class="jaxtag">
                                        <div class="ajaxtag hide-if-no-js">
                                            <label class="screen-reader-text" for="new-tag-post_tag">Neues Schlagwort erstellen</label>
                                            <input type="text" class="form-control" 
                                                value="<?php foreach($event->tags as $tag) { echo $tag.","; }?>" 
                                                name="tags" data-role="tagsinput" >
                                        </div>
                                        <p class="howto" id="new-tag-post_tag-desc">Schlagwörter durch Kommas trennen.</p>
                                    </div>
                                    <ul class="tagchecklist" role="list"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="postbox-container-2" class="postbox-container">
                    <div class="postbox">
                        <h2 class="hndle">
                            <span>Standort</span>
                        </h2>
                        <div class="inside inside-pad">
                            <div class="row">
                                <div class="col-12 form-group">
                                    <label for="event_edit_locationname"><?php echo __('Veranstaltungsort', 'ticketmachine') ?></label>
                                    <input id="event_location_name" type="text" class="form-control" value="<?php echo $event->ev_location_name; ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8 form-group">
                                    <label for="event_edit_strasse"><?php echo __('Straße', 'ticketmachine') ?></label>
                                    <input id="event_edit_strasse" type="text" class="form-control" value="<?php echo $event->event_location['street']; ?>">
                                </div>
                                <div class="col-sm-4 form-group">
                                    <label for="house_number"><?php echo __('Haus-Nr.', 'ticketmachine') ?></label>
                                    <input id="event_edit_hausnr" type="text" class="form-control" value="<?php echo $event->event_location['house_number']; ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 form-group">
                                    <label for="event_edit_plz"><?php echo __('PLZ', 'ticketmachine') ?></label>
                                    <input id="event_edit_plz" type="text" class="form-control" value="<?php echo $event->event_location['zip']; ?>">
                                </div>
                                <div class="col-sm-8 form-group">
                                    <label for="event_edit_ort"><?php echo __('Ort', 'ticketmachine') ?></label>
                                    <input id="event_edit_ort" type="text" class="form-control" value="<?php echo $event->event_location['city']; ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 form-group">
                                    <label for="event_edit_land"><?php echo __('Land', 'ticketmachine') ?></label>
                                    <input id="event_edit_land" type="text" class="form-control" value="<?php echo $event->event_location['country']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                

                    <div class="postbox">
                        <h2 class="hndle">
                            <span>Termine</span>
                        </h2>
                        <div class="inside inside-pad">
                            <div>
                                <label><?php echo __('Einlasszeit', 'ticketmachine') ?></label>
                            </div>
                            <div class="row">
                                <div class="input-group col-8">
                                    <input type="text" class="form-control date entrytime" value="<?php echo date("d.m.Y", strtotime($event->entrytime)); ?>">
                                    <label for="event_time_entry_date" class="input-group-addon w50">
                                        <i class="fa fa-calendar"></i>
                                    </label>
                                </div>
                                <div class="input-group col-4">
                                    <input type="text" class="form-control time" value="<?php echo date("H:i", strtotime($event->entrytime)); ?>">
                                    <label for="event_time_entry_time" class="input-group-addon w50">
                                        <i class="fa fa-clock-o"></i>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label><?php echo __('Veranstaltungsbeginn', 'ticketmachine') ?></label>
                            </div>
                            <div class="row">
                                <div class="input-group col-8">
                                    <input type="text" class="form-control date starttime" value="<?php echo date("d.m.Y", strtotime($event->ev_date)); ?>">
                                    <label for="event_time_start_date" class="input-group-addon w50">
                                        <i class="fa fa-calendar"></i>
                                    </label>
                                </div>
                                <div class="input-group col-4">
                                    <input type="text" class="form-control time" value="<?php echo date("H:i", strtotime($event->ev_date)); ?>">
                                    <label for="event_time_start_date" class="input-group-addon w50">
                                        <i class="fa fa-clock-o"></i>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label><?php echo __('Veranstaltungsende', 'ticketmachine') ?></label>
                            </div>
                            <div class="row">
                                <div class="input-group col-8">
                                    <input type="text" class="form-control date endtime" value="<?php echo date("d.m.Y", strtotime($event->endtime)); ?>">
                                    <label for="event_edit_end_date" class="input-group-addon w50">
                                        <i class="fa fa-calendar"></i>
                                    </label>
                                </div>
                                <div class="input-group col-4">
                                    <input type="text" class="form-control time" value="<?php echo date("H:i", strtotime($event->endtime)); ?>">
                                    <label for="event_edit_end_date" class="input-group-addon w50">
                                        <i class="fa fa-clock-o"></i>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    jQuery(document).ready(function($) {
        $('input.date').datetimepicker({
            format: 'DD.MM.YYYY'
        });
        $('input.time').datetimepicker({
            format: 'HH:mm'   
        });
    });
</script>