<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    global $ticketmachine_globals, $ticketmachine_api, $wpdb;

    $errors   = array();
    $response = null;

    // Handle Reject Action
    if ( isset( $_POST['reject'] ) && is_plugin_active( 'ticketmachine-community-events/ticketmachine-community-events.php' ) ) {
        if ( ! isset( $_POST['ticketmachine_event_edit_form_nonce'] ) || ! wp_verify_nonce( $_POST['ticketmachine_event_edit_form_nonce'], 'ticketmachine_action_save_event' ) ) {
            $errors[] = __( 'Sorry, your nonce did not verify.', 'ticketmachine-event-manager' );
        } else {
            $ticketmachine_post = wp_unslash( $_POST );
            $table              = $wpdb->prefix . 'ticketmachine_events';
            $wpdb->delete( $table, array( 'id' => absint( $ticketmachine_post['old_id'] ) ) );

            $reject_redirect = admin_url( 'admin.php?page=ticketmachine_communityevents_events&status=review&saved=success&action=rejected' );

            // Standard redirect if headers are uncorrupted
            if ( ! headers_sent( $file, $line ) ) {
                wp_safe_redirect( $reject_redirect );
                exit;
            }

            // Fallback UI if headers were already sent
            ?>
            <div>
                <p><?php esc_html_e( 'Redirecting...', 'ticketmachine-event-manager' ); ?></p>
            </div>
            <script type="text/javascript">
                window.location.href = <?php echo wp_json_encode( esc_url_raw( $reject_redirect ) ); ?>;
            </script>
            <noscript>
                <meta http-equiv="refresh" content="0;url=<?php echo esc_url( $reject_redirect ); ?>" />
            </noscript>
            <?php
            exit;
        }
    }

    // Handle Submit/Save Action
    if ( isset( $_POST['submit'] ) ) {
        if ( ! current_user_can( 'edit_posts' ) ) {
            $errors[] = __( 'You do not have sufficient permissions to access this page.', 'ticketmachine-event-manager' );
        } elseif ( ! isset( $_POST['ticketmachine_event_edit_form_nonce'] ) || ! wp_verify_nonce( $_POST['ticketmachine_event_edit_form_nonce'], 'ticketmachine_action_save_event' ) ) {
            $errors[] = __( 'Sorry, your nonce did not verify.', 'ticketmachine-event-manager' );
        } else {
            // Strip WP magic slashes globally across all input fields
            $ticketmachine_post = wp_unslash( $_POST );

            // Normalizations & Validations
            $ticketmachine_post['rules']['shown']        = ! empty( $ticketmachine_post['rules']['shown'] ) ? 1 : 0;
            $ticketmachine_post['rules']['prices_shown'] = ! empty( $ticketmachine_post['rules']['prices_shown'] ) ? 1 : 0;
            $ticketmachine_post['rules']['sale_active']  = ! empty( $ticketmachine_post['rules']['sale_active'] ) ? 1 : 0;
            $ticketmachine_post['approved']              = ! empty( $ticketmachine_post['approved'] ) ? 1 : 0;
            $ticketmachine_post['vat_id']                = ! empty( $ticketmachine_post['vat_id'] ) ? 1 : 0;

            if ( ! empty( $ticketmachine_post['tags'] ) ) {
                $ticketmachine_post['tags'] = explode( ',', $ticketmachine_post['tags'] );
                array_walk( $ticketmachine_post['tags'], function ( &$value ) {
                    $value = sanitize_text_field( $value );
                } );
            }

            // Date validation checks
            if ( isset( $ticketmachine_post['entrytime']['date'], $ticketmachine_post['entrytime']['time'] ) ) {
                $ticketmachine_post['entrytime'] = sanitize_text_field( ticketmachine_i18n_reverse_date( $ticketmachine_post['entrytime']['date'] . $ticketmachine_post['entrytime']['time'] ) );
            } else {
                $errors[] = __( 'No entry time was set', 'ticketmachine-event-manager' );
            }

            if ( isset( $ticketmachine_post['ev_date']['date'], $ticketmachine_post['ev_date']['time'] ) ) {
                $ticketmachine_post['ev_date'] = sanitize_text_field( ticketmachine_i18n_reverse_date( $ticketmachine_post['ev_date']['date'] . $ticketmachine_post['ev_date']['time'] ) );
            } else {
                $errors[] = __( 'No start time was set', 'ticketmachine-event-manager' );
            }

            if ( isset( $ticketmachine_post['endtime']['date'], $ticketmachine_post['endtime']['time'] ) ) {
                $ticketmachine_post['endtime'] = sanitize_text_field( ticketmachine_i18n_reverse_date( $ticketmachine_post['endtime']['date'] . $ticketmachine_post['endtime']['time'] ) );
            } else {
                $errors[] = __( 'No end time was set', 'ticketmachine-event-manager' );
            }

            if ( ! empty( $ticketmachine_post['ev_name'] ) ) {
                $ticketmachine_post['ev_name'] = sanitize_text_field( $ticketmachine_post['ev_name'] );
            } else {
                $errors[] = __( 'No event title was set', 'ticketmachine-event-manager' );
            }

            if ( empty( $ticketmachine_post['artist'] ) ) {
                $ticketmachine_post['artist'] = '';
            } else {
                $ticketmachine_post['artist'] = sanitize_text_field( $ticketmachine_post['artist'] );
            }

            if ( isset( $ticketmachine_post['event_img_url'] ) && strlen( $ticketmachine_post['event_img_url'] ) > 1 ) {
                $pos = strrpos( $ticketmachine_post['event_img_url'], '/' ) + 1;
                $ticketmachine_post['event_img_url'] = substr( $ticketmachine_post['event_img_url'], 0, $pos ) . urlencode( substr( $ticketmachine_post['event_img_url'], $pos ) );
            }

            if ( isset( $ticketmachine_post['ev_description'] ) ) {
                $ticketmachine_post['ev_description'] = wp_kses_post( $ticketmachine_post['ev_description'] );
            }

            if ( ! empty( $ticketmachine_post['id'] ) ) {
                $ticketmachine_post['id'] = absint( $ticketmachine_post['id'] );
            }

            if ( empty( $ticketmachine_globals->organizer_id ) || ! is_numeric( $ticketmachine_globals->organizer_id ) ) {
                $errors[] = __( 'No organizer id could be found', 'ticketmachine-event-manager' );
            }

            if ( empty( $ticketmachine_post['organizer']['og_name'] ) ) {
                unset( $ticketmachine_post['organizer'] );
            }

            // Execute API call only if validation passes
            if ( empty( $errors ) ) {
                $organizer = null;
                if ( ! empty( $ticketmachine_post['organizer'] ) ) {
                    $organizer = $ticketmachine_post['organizer'];
                    unset( $ticketmachine_post['organizer'] );
                }

                $ticketmachine_post['organizer_id']         = absint( $ticketmachine_globals->organizer_id );
                $ticketmachine_post['approved']             = absint( $ticketmachine_post['approved'] );
                $ticketmachine_post['rules']['shown']       = absint( $ticketmachine_post['rules']['shown'] );
                $ticketmachine_post['rules']['sale_active'] = absint( $ticketmachine_post['rules']['sale_active'] );
                $ticketmachine_post['vat_id']               = absint( $ticketmachine_post['vat_id'] );

                $ticketmachine_json = ticketmachine_tmapi_event( $ticketmachine_post, 'POST' );
                
                // Safely ensure $response is an object without json_encode side effects
                $response = is_array( $ticketmachine_json ) ? (object) $ticketmachine_json : $ticketmachine_json;

                if ( isset( $response->model_error[0]['error_message'] ) ) {
                    $errors[] = $response->model_error[0]['error_message'];
                } elseif ( isset( $response->error ) ) {
                    $errors[] = is_array( $response->error ) ? $response->error['error_message'] : $response->error;
                } elseif ( empty( $ticketmachine_json ) || empty( $response->id ) ) {
                    $errors[] = __( 'Something went wrong or API response missing event ID', 'ticketmachine-event-manager' );
                } else {
                    // Database synchronization
                    if ( isset( $ticketmachine_post['old_id'] ) && is_plugin_active( 'ticketmachine-community-events/ticketmachine-community-events.php' ) ) {
                        $table = $wpdb->prefix . 'ticketmachine_events';
                        $wpdb->update( $table, array( 'approved' => 1, 'api_event_id' => $response->id ), array( 'id' => absint( $ticketmachine_post['old_id'] ) ) );
                    }

                    if ( ! empty( $organizer ) ) {
                        $table           = $wpdb->prefix . 'ticketmachine_organizers';
                        $organizer_check = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE og_name = %s", $organizer['og_name'] ) );
                        $match_table     = $wpdb->prefix . 'ticketmachine_organizers_events_match';

                        if ( ! empty( $organizer_check ) ) {
                            $wpdb->update( $table, $organizer, array( 'id' => $organizer_check->id ) );
                            if ( isset( $ticketmachine_post['old_id'] ) ) {
                                $wpdb->delete( $match_table, array( 'local_event_id' => absint( $ticketmachine_post['old_id'] ) ) );
                            }
                            $wpdb->delete( $match_table, array( 'api_event_id' => $response->id ) );
                            $wpdb->insert( $match_table, array( 'organizer_id' => $organizer_check->id, 'api_event_id' => $response->id ) );
                        } else {
                            $wpdb->insert( $table, $organizer );
                            $new_org_id = $wpdb->insert_id;
                            if ( isset( $ticketmachine_post['old_id'] ) ) {
                                $wpdb->delete( $match_table, array( 'local_event_id' => absint( $ticketmachine_post['old_id'] ) ) );
                            }
                            $wpdb->delete( $match_table, array( 'api_event_id' => $response->id ) );
                            $wpdb->insert( $match_table, array( 'organizer_id' => $new_org_id, 'api_event_id' => $response->id ) );
                        }
                    }

                    // Upload Image (Wrapped in output buffering to prevent header pollution)
                    if ( ! empty( $ticketmachine_post['event_img_url'] ) && strpos( $ticketmachine_post['event_img_url'], 'cloud.ticketmachine.de/' ) === false ) {
                        ob_start();
                        $imageResult  = ticketmachine_tmapi_update_event_image( $response->id, $ticketmachine_post['event_img_url'] );
                        $stray_output = ob_get_clean();

                        if ( ! empty( $stray_output ) ) {
                            error_log( 'TicketMachine Debug: Suppressed unexpected output during image upload: ' . $stray_output );
                        }

                        if ( is_wp_error( $imageResult ) ) {
                            $errors[] = $imageResult->get_error_message();
                        }
                    }

                    // Redirect execution
                    if ( empty( $errors ) ) {
                        $redirect_url = add_query_arg(
                            array(
                                'page'   => isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '',
                                'status' => isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : false,
                                'saved'  => 'success',
                                'action' => 'saved',
                                'id'     => absint( $response->id ),
                            ),
                            admin_url( 'admin.php' )
                        );

                        // If headers are uncorrupted, issue standard server redirect
                        if ( ! headers_sent( $file, $line ) ) {
                            wp_redirect( $redirect_url );
                            exit;
                        }

                        // Fallback UI if headers were already sent
                        ?>
                        <div>
                            <p><?php esc_html_e( 'Redirecting...', 'ticketmachine-event-manager' ); ?></p>
                        </div>
                        <script type="text/javascript">
                            window.location.href = <?php echo wp_json_encode( esc_url_raw( $redirect_url ) ); ?>;
                        </script>
                        <noscript>
                            <meta http-equiv="refresh" content="0;url=<?php echo esc_url( $redirect_url ); ?>" />
                        </noscript>
                        <?php
                        exit;
                    }
                }
            }
        }
    }

    // Render accumulated errors at top of form view
    if ( ! empty( $errors ) ) {
        foreach ( $errors as $error_message ) {
            ?>
            <div class="notice notice-error is-dismissable">
                <p><?php echo esc_html( $error_message ); ?></p>
            </div>
            <?php
        }
    }
?>