<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }

    global $ticketmachine_globals, $ticketmachine_api;

    $errors             = array();
    $response           = null;
    $ticketmachine_json = null;

    // Ensure this script is only processed when an action/ID is actually requested
    if ( isset( $_GET['id'] ) || isset( $_GET['_wpnonce'] ) ) {
        // Check user capabilities
        if ( ! current_user_can( 'edit_posts' ) ) {
            $errors[] = __( 'You do not have sufficient permissions to access this page.', 'ticketmachine-event-manager' );
        } else {
            // Verify nonce
            if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'ticketmachine_action_delete_event' ) ) {
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

                // Proceed with API call if no errors yet
                if ( empty( $errors ) ) {
                    $ticketmachine_post = array(
                        'id'           => $event_id,
                        'organizer_id' => absint( $ticketmachine_globals->organizer_id ),
                    );

                    $ticketmachine_json = ticketmachine_tmapi_event_delete( $ticketmachine_post );
                    $response           = json_decode( json_encode( $ticketmachine_json ) );

                    // Handle API Errors
                    if ( isset( $response->error->error_message ) ) {
                        $errors[] = $response->error->error_message;
                    } elseif ( isset( $response->model_error[0]['error_message'] ) ) {
                        $errors[] = $response->model_error[0]['error_message'];
                    } elseif ( empty( $ticketmachine_json ) || ( isset( $response->result ) && $response->result === 'failure' ) ) {
                        $errors[] = isset( $response->reason ) ? $response->reason : __( 'Something went wrong', 'ticketmachine-event-manager' );
                    } else {
                        // Success: Redirect immediately inside the block
                        $redirect_url = add_query_arg(
                            array(
                                'page'   => isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '',
                                'status' => isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : false,
                                'saved'  => 'success',
                                'action' => 'deleted',
                            ),
                            admin_url( 'admin.php' )
                        );

                        if ( ! headers_sent( $file, $line ) ) {
                            wp_safe_redirect( $redirect_url );
                            exit;
                        }

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

    // Fallback: Output Errors if any occurred during execution
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