<?php
    global $globals, $api;
    if($_GET['id'] > 0){
        $tm_json = apiRequest($api->get_single_event_no_categories);
        $event = (object)$tm_json;
    }else{
        $event = new stdClass();
        $event->state['shown'] = 1;

        $timestamp = new DateTime();
        $event->entrytime = date(DATE_ISO8601, strtotime("today 10:00"));
        $event->ev_date =  date(DATE_ISO8601, strtotime("today 11:00"));
        $event->endtime =  date(DATE_ISO8601, strtotime("today 23:59"));
    }
?>


<div class="wrap tm-admin-page">
    <?php 
        if($event->id > 0){
            echo "<h1 class='wp-heading-inline'>TicketMachine > " . __('Veranstaltung bearbeiten', 'ticketmachine') . "</h1>";
        } else {
            echo "<h1 class='wp-heading-inline'>TicketMachine > " . __('Veranstaltung erstellen', 'ticketmachine') . "</h1>";
        }
    ?>
    <form name="event" action="?page=tm_events&action=save&id=<?php echo $_GET['id']; ?>" method="post" id="event">
        <input type="hidden" name="organizer_id" value="<?php echo $globals->organizer_id; ?>">
        <input type="hidden" name="rules[sale_active]" value="0">
        <input type="hidden" name="rules[prices_shown]" value="0">
        <input type="hidden" name="vat_id" value="1">
        
        <?php if($event->id > 0){ ?>
            <input type="hidden" name="id" value="<?php echo $event->id; ?>">
        <?php } ?>

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
                        $editor_id = 'ev_description';
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
                                                <select style="float: right; margin-top: -2px;" name="approved">
                                                    <option value="1" <?php if($event->approved == 1){ echo "selected"; } ?>><?php echo __('Veröffentlicht', 'ticketmachine') ?></option>
                                                    <option value="0" <?php if($event->approved != 1){ echo "selected"; } ?>><?php echo __('Entwurf', 'ticketmachine') ?></option>
                                                </select>
                                            </div>
                                            <div class="misc-pub-section misc-pub-section misc-pub-visibility">
                                                <input value="1" name="shown" type="checkbox" <?php if($event->state['shown'] == 1){ echo "checked"; } ?>>
                                                <span><?php echo __('In Veranstaltungs-Liste anzeigen', 'ticketmachine') ?></span>
                                            </div>
                                            <div class="misc-pub-section misc-pub-section misc-pub-visibility">
                                                <label for="event_edit_locationname"><?php echo __('Hinweistext','ticketmachine'); ?></label>
                                                <input type="text" name="rules[badge]" class="fullw-input" value="<?php echo $event->rules['badge']; ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="major-publishing-actions">
                                        <div id="publishing-action">
                                            <span class="spinner"></span>
                                            <input type="submit" class="button button-primary button-large" id="publish" value="<?php echo __('Aktualisieren', 'ticketmachine') ?>">
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
                                <img src="<?php echo $event->event_img_url; ?>"/>
                                <p class="hide-if-no-js">
                                    <a href="" id="set-post-thumbnail" class="thickbox"><?php echo __('Eventbild festlegen', 'ticketmachine') ?></a> <!-- needs correct href -->
                                </p>
                                <input type="hidden" name="event_img_url" id="_thumbnail_id" value="<?php echo $event->event_img_url; ?>"> <!-- #event_img_url -->
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
                                            <label class="screen-reader-text" for="new-tag-post_tag"><?php echo __('Neues Schlagwort erstellen', 'ticketmachine') ?></label>
                                            <input type="text" class="form-control" 
                                                value="<?php foreach($event->tags as $tag) { echo $tag.","; }?>" 
                                                name="tags" data-role="tagsinput" >
                                        </div>
                                        <p class="howto" id="new-tag-post_tag-desc"><?php echo __('Schlagwörter mit Enter-Taste hinzufügen.', 'ticketmachine') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="postbox-container-2" class="postbox-container">
                    <div class="postbox">
                        <h2 class="hndle">
                            <span><?php echo __('Standort', 'ticketmachine') ?></span>
                        </h2>
                        <div class="inside inside-pad">
                            <div class="row">
                                <div class="col-12 form-group">
                                    <label for="event_edit_locationname"><?php echo __('Veranstaltungsort', 'ticketmachine') ?></label>
                                    <input id="event_location_name" name="ev_location_name" type="text" class="form-control" value="<?php echo $event->ev_location_name; ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8 form-group">
                                    <label for="event_edit_strasse"><?php echo __('Straße', 'ticketmachine') ?></label>
                                    <input id="event_edit_strasse" name="event_location[street]" type="text" class="form-control" value="<?php echo $event->event_location['street']; ?>">
                                </div>
                                <div class="col-sm-4 form-group">
                                    <label for="house_number"><?php echo __('Haus-Nr.', 'ticketmachine') ?></label>
                                    <input id="event_edit_hausnr" name="event_location[house_number]" type="text" class="form-control" value="<?php echo $event->event_location['house_number']; ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 form-group">
                                    <label for="event_edit_plz"><?php echo __('PLZ', 'ticketmachine') ?></label>
                                    <input id="event_edit_plz" name="event_location[zip]" type="text" class="form-control" value="<?php echo $event->event_location['zip']; ?>">
                                </div>
                                <div class="col-sm-8 form-group">
                                    <label for="event_edit_ort"><?php echo __('Ort', 'ticketmachine') ?></label>
                                    <input id="event_edit_ort" name="event_location[city]" type="text" class="form-control" value="<?php echo $event->event_location['city']; ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 form-group">
                                    <label for="event_edit_land"><?php echo __('Land', 'ticketmachine') ?></label>
                                    <input id="event_edit_land" name="event_location[country]" type="text" class="form-control" value="<?php echo $event->event_location['country']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                

                    <div class="postbox">
                        <h2 class="hndle">
                            <span><?php echo __('Termine', 'ticketmachine') ?></span>
                        </h2>
                        <div class="inside inside-pad">
                            <div>
                                <label><?php echo __('Einlasszeit', 'ticketmachine') ?></label>
                            </div>
                            <div class="row">
                                <div class="input-group col-8">
                                    <input type="text" name="entrytime[date]" class="form-control date entrytime" value="<?php echo date("d.m.Y", strtotime($event->entrytime)); ?>">
                                    <label for="event_time_entry_date" class="input-group-addon w50">
                                        <i class="fa fa-calendar"></i>
                                    </label>
                                </div>
                                <div class="input-group col-4">
                                    <input type="text" name="entrytime[time]" class="form-control time" value="<?php echo date("H:i", strtotime($event->entrytime)); ?>">
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
                                    <input type="text" name="ev_date[date]" class="form-control date starttime" value="<?php echo date("d.m.Y", strtotime($event->ev_date)); ?>">
                                    <label for="event_time_start_date" class="input-group-addon w50">
                                        <i class="fa fa-calendar"></i>
                                    </label>
                                </div>
                                <div class="input-group col-4">
                                    <input type="text" name="ev_date[time]" class="form-control time" value="<?php echo date("H:i", strtotime($event->ev_date)); ?>">
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
                                    <input type="text" name="endtime[date]" class="form-control date endtime" value="<?php echo date("d.m.Y", strtotime($event->endtime)); ?>">
                                    <label for="event_edit_end_date" class="input-group-addon w50">
                                        <i class="fa fa-calendar"></i>
                                    </label>
                                </div>
                                <div class="input-group col-4">
                                    <input type="text" name="endtime[time]" class="form-control time" value="<?php echo date("H:i", strtotime($event->endtime)); ?>">
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
        
        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                $('form').submit(false)
            }
        });
    });
</script>