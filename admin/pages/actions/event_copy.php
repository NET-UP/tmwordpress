<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }

    global $ticketmachine_globals, $ticketmachine_api;

    $errors             = array();
    $response           = null;
    $ticketmachine_json = null;

    // Check user capabilities
    if ( ! current_user_can( 'edit_posts' ) ) {
        $errors[] = __( 'You do not have sufficient permissions to access this page.', 'ticketmachine-event-manager' );
    } else {
        // Verify nonce
        if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'ticketmachine_action_copy_event' ) ) {
            $errors[] = __( 'Cheatin&#8217; huh?', 'ticketmachine-event-manager' );
        } else {
            // Validate Event ID and Organizer ID
            $event_id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
            if ( ! $event_id ) {
                $errors[] = __( 'No event id was set', 'ticketmachine-event-manager' );
            }

            if ( empty( $ticketmachine_globals->organizer_id ) || ! is_int( $ticketmachine_globals->organizer_id ) ) {
                $errors[] = __( 'No organizer id could be found', 'ticketmachine-event-manager' );
            }

            // Proceed with API call if no errors yet
            if ( empty( $errors ) ) {
                $ticketmachine_post = array(
                    'id'           => $event_id,
                    'organizer_id' => absint( $ticketmachine_globals->organizer_id ),
                );

                $ticketmachine_json = ticketmachine_tmapi_event_copy( $ticketmachine_post );
                $response           = json_decode( json_encode( $ticketmachine_json ) );

                // Handle API Errors (consistent with your API's error structures)
                if ( isset( $response->error->error_message ) ) {
                    $errors[] = $response->error->error_message;
                } elseif ( isset( $response->model_error[0]['error_message'] ) ) {
                    $errors[] = $response->model_error[0]['error_message'];
                } elseif ( empty( $ticketmachine_json ) || ( isset( $response->result ) && $response->result === 'failure' ) ) {
                    $errors[] = isset( $response->reason ) ? $response->reason : __( 'Something went wrong', 'ticketmachine-event-manager' );
                }
            }
        }
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
                'action' => 'copied',
                'id'     => $event_id,
            ),
            admin_url( 'admin.php' )
        );

        wp_safe_redirect( $redirect_url );
        exit;
    }
?>