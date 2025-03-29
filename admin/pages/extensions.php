<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	defined("ABSPATH") or die("Permission denied");

	if( current_user_can( 'edit_posts' ) ) {	
        $ticketmachine_extensions = array(
            (object) [
            'name' => 'Event Manager & Calendar',
            'desc' => 'Easily create and manage your events in seconds.',
            'link' => 'https://wordpress.org/plugins/ticketmachine-event-manager/',
            'active' => true
            ],
            (object) [
            'name' => 'Community Events',
            'desc' => 'Give your users the ability to manage their own events and add them to your community calendar!',
            'link' => 'https://ticketmachine.de/shop/wordpress/ticketmachine-community-events-erweiterung-wordpress/',
            'active' => in_array('ticketmachine-community-events/ticketmachine-community-events.php', apply_filters('active_plugins', get_option('active_plugins'))) ? true : false
            ],
        );
?>
	<div class="wrap tm-admin-page">
		<h1 class="dont-display"></h1>

		<div class="row">
			<div class="col-xl-9">
				<h1 class="wp-heading-inline me-3 mb-3">TicketMachine <i class="fas fa-angle-right mx-1"></i> <?php esc_html_e('Extensions', 'ticketmachine-event-manager') ?></h1>
			
                <div class="row">
                    <?php foreach ($ticketmachine_extensions as $ticketmachine_extension) { ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="box mb-3">
                                <div class="box-title"><?php esc_html_e($ticketmachine_extension->name, "ticketmachine-event-manager"); ?></div>
                                <div class="box-body">
                                    <p><?php esc_html_e($ticketmachine_extension->desc, "ticketmachine-event-manager"); ?></p>

                                    <?php if($ticketmachine_extension->active) { ?>
                                        <button class="button button-secondary mb-1"><?php _e("Active", "ticketmachine-event-manager"); ?></button>
                                    <?php } else { ?>
                                        <a href="<?php echo $ticketmachine_extension->link ?>" class="button button-primary mb-1"><?php _e("Install", "ticketmachine-event-manager"); ?></a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

			<?php 
				include( str_replace("/admin/pages", "", plugin_dir_path(__FILE__)) . 'admin/partials/sidebar.php');
			?>
		</div>
	</div>

<?php 
	} 
?>