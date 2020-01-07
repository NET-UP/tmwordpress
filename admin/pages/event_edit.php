<?php
    global $globals, $api;
    wp_enqueue_media();
    
    if($_GET['id'] > 0){
        $params = [ "id" => $_GET['id'] ];
        $event = (object)tmapi_event($params);
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
        <input type="hidden" name="rules[shown]" value="1">
        
        <?php if($event->id > 0){ ?>
            <input type="hidden" name="id" value="<?php echo $event->id; ?>">
        <?php } ?>

        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content" style="position: relative;">
                    <div id="titlediv">
                        <div id="titlewrap">
                            <label class="screen-reader-text" id="post-name-prompt-text" for="ev_name"><?php echo __('Event Name hier eingeben', 'ticketmachine') ?></label>
                            <input type="text" placeholder="<?php echo __('Name der Veranstaltung', 'ticketmachine') ?>" name="ev_name" size="30" id="title" spellcheck="true" autocomplete="off" value="<?php echo $event->ev_name; ?>">
                        </div>
                    </div>

                    <?php 
                        $editor_id = 'ev_description';
                        $settings = array( 'media_buttons' => false );
                        if ($event->ev_description == ""){
                            $content = __('Beschreibung der Veranstaltung', 'ticketmachine');
                        }
                        else{
                            $content =$event->ev_description;
                        }
                        wp_editor( $content, $editor_id, $settings);
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
                                <span><?php echo __('Veranstaltungsbild', 'ticketmachine') ?></span>
                            </h2>
                            <div class="inside">
                                <div class='image-preview-wrapper'>
                                    <img id='image-preview' src='<?php echo $event->event_img_url; ?>' width='100' height='100' style='max-height: 500px; width: 100%;'>
                                </div>
                                <input id="upload_image_button" type="button" class="button" style="display:block;width:100%;" value="<?php _e( 'Bild festlegen' ); ?>" />
                                <input type='hidden' name='event_img_url' id='image_attachment_id' value='<?php echo $event->event_img_url; ?>'>
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
                                        <p class="howto" id="new-tag-post_tag-desc"><?php echo __('Schlagwörter durch Kommas trennen.', 'ticketmachine') ?></p>
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
                                </div>
                                <div class="input-group col-4">
                                    <input type="text" name="entrytime[time]" class="form-control time" value="<?php echo date("H:i", strtotime($event->entrytime)); ?>">
                                </div>
                            </div>
                            <div>
                                <label><?php echo __('Veranstaltungsbeginn', 'ticketmachine') ?></label>
                            </div>
                            <div class="row">
                                <div class="input-group col-8">
                                    <input type="text" name="ev_date[date]" class="form-control date starttime" value="<?php echo date("d.m.Y", strtotime($event->ev_date)); ?>">
                                </div>
                                <div class="input-group col-4">
                                    <input type="text" name="ev_date[time]" class="form-control time" value="<?php echo date("H:i", strtotime($event->ev_date)); ?>">
                                </div>
                            </div>
                            <div>
                                <label><?php echo __('Veranstaltungsende', 'ticketmachine') ?></label>
                            </div>
                            <div class="row">
                                <div class="input-group col-8">
                                    <input type="text" name="endtime[date]" class="form-control date endtime" value="<?php echo date("d.m.Y", strtotime($event->endtime)); ?>">
                                </div>
                                <div class="input-group col-4">
                                    <input type="text" name="endtime[time]" class="form-control time" value="<?php echo date("H:i", strtotime($event->endtime)); ?>">
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
        jQuery('input.date').datetimepicker({
            format: 'DD.MM.YYYY'
        });
        jQuery('input.time').datetimepicker({
            format: 'HH:mm'   
        });
        
        $('.bootstrap-tagsinput').on('keypress', function(e){
            if (e.keyCode == 13){
                e.keyCode = 188;
                e.preventDefault();
            };
        });
    });
</script>


<?php $my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 );?>
    
<script type='text/javascript'>
    jQuery( document ).ready( function( $ ) {
        // Uploading files
        var file_frame;
        var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
        var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
        jQuery('#upload_image_button').on('click', function( event ){
            event.preventDefault();
            // If the media frame already exists, reopen it.
            if ( file_frame ) {
                // Set the post ID to what we want
                file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
                // Open frame
                file_frame.open();
                return;
            } else {
                // Set the wp.media post id so the uploader grabs the ID we want when initialised
                wp.media.model.settings.post.id = set_to_post_id;
            }
            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Select a image to upload',
                button: {
                    text: 'Use this image',
                },
                multiple: false	// Set to true to allow multiple files to be selected
            });
            // When an image is selected, run a callback.
            file_frame.on( 'select', function() {
                // We set multiple to false so only get one image from the uploader
                attachment = file_frame.state().get('selection').first().toJSON();
                // Do something with attachment.id and/or attachment.url here
                $( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
                $( '#image_attachment_id' ).val( attachment.url );
                // Restore the main post ID
                wp.media.model.settings.post.id = wp_media_post_id;
            });
                // Finally, open the modal
                file_frame.open();
        });
        // Restore the main ID when the add media button is pressed
        jQuery( 'a.add_media' ).on( 'click', function() {
            wp.media.model.settings.post.id = wp_media_post_id;
        });
    });
</script>