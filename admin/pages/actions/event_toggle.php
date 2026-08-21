<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }

    global $ticketmachine_globals, $ticketmachine_api;

    $errors = array();
    $response = null;
    $ticketmachine_json = null;

    // Check user capabilities
    if ( ! current_user_can( 'edit_posts' ) ) {
        $errors[] = __( 'You do not have sufficient permissions to access this page.', 'ticketmachine-event-manager' );
    } else {
        // Verify nonce
        if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'ticketmachine_action_toggle_event' ) ) {
            $errors[] = __( 'Sorry, your nonce did not verify.', 'ticketmachine-event-manager' );
        } else {
            // Validate Event ID and Organizer ID
            $event_id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
            if ( ! $event_id ) {
                $errors[] = __( 'No event id was set', 'ticketmachine-event-manager' );
            }

            if ( empty( $ticketmachine_globals->organizer_id ) || ! is_int( $ticketmachine_globals->organizer_id ) ) {
                $errors[] = __( 'No organizer id could be found', 'ticketmachine-event-manager' );
            }

            // If no basic errors yet, proceed with API calls
            if ( empty( $errors ) ) {
                $ticketmachine_json_a = ticketmachine_tmapi_event( [ 'id' => $event_id ] );
                $ticketmachine_post   = (array) $ticketmachine_json_a;

                if ( empty( $ticketmachine_post ) ) {
                    $errors[] = __( 'Could not retrieve event data.', 'ticketmachine-event-manager' );
                } else {
                    // Toggle approval / visibility state
                    $current_approved = ! empty( $ticketmachine_post['approved'] ) ? 1 : 0;
                    $new_approved     = 1 - $current_approved;

                    $ticketmachine_post['id']           = $event_id;
                    $ticketmachine_post['organizer_id'] = absint( $ticketmachine_globals->organizer_id );
                    $ticketmachine_post['approved']     = $new_approved;
                    $ticketmachine_post['rules']['shown'] = $new_approved;

                    if ( empty( $ticketmachine_post['seat_categories'] ) ) {
                        unset( $ticketmachine_post['seat_categories'] );
                    }

                    // Send update via POST
                    $ticketmachine_json = ticketmachine_tmapi_event( $ticketmachine_post, 'POST' );
                    $response           = json_decode( json_encode( $ticketmachine_json ) );

                    // Handle API Errors (matching your actual API response structure)
                    if ( isset( $response->error->error_message ) ) {
                        $errors[] = $response->error->error_message;
                    } elseif ( empty( $ticketmachine_json ) || ( isset( $response->result ) && $response->result === 'failure' ) ) {
                        $errors[] = isset( $response->reason ) ? $response->reason : __( 'Something went wrong', 'ticketmachine-event-manager' );
                    }
                }
            }
        }
    }



    if ( function_exists( 'ticketmachine_debug' ) ) {
        ticketmachine_debug( $ticketmachine_post );
        ticketmachine_debug( $response );
    }

    // Output Errors or Handle Success/Redirect
    if ( ! empty( $errors ) ) {
        foreach ( $errors as $error_message ) {
            ?>
            <div class="notice notice-error is-dismissable">
                <p><?php echo esc_html( $error_message ); ?></p>
            </div>
            <?php
        }
    } else {
        $redirect_url = add_query_arg(
            array(
                'page'   => isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '',
                'status' => isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : false,
                'saved'  => 'success',
                'action' => 'toggled',
                'state'  => ( isset( $response->approved ) && $response->approved == 1 ) ? 'published' : 'deactivated',
                'id'     => $event_id,
            ),
            admin_url( 'admin.php' )
        );

        wp_safe_redirect( $redirect_url );
        exit;
    }
?>