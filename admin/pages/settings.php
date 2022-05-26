<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	defined("ABSPATH") or die("Permission denied");
	
	global $wpdb;
    $ticketmachine_design = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_design LIMIT 0,1");
    $ticketmachine_design = $ticketmachine_design[0];
    $ticketmachine_config = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_config LIMIT 0,1");
    $ticketmachine_config = $ticketmachine_config[0];
	
	$active_tab = isset( $_GET[ 'tab' ] ) ? esc_html(sanitize_text_field($_GET[ 'tab' ])) : 'design';

	if( current_user_can( 'manage_options' ) ) {	
?>

	<div class="wrap tm-admin-page">
		<h1 class="dont-display"></h1>

		<div class="row">
			<div class="col-xl-9">
				
				<h1 class="wp-heading-inline me-3 mb-3">TicketMachine <i class="fas fa-angle-right mx-1"></i> <?php esc_html_e('Settings', 'ticketmachine-event-manager') ?></h1>
			
				<h2 class="nav-tab-wrapper">
					<a href="?page=ticketmachine_settings&tab=design" class="nav-tab <?php echo $active_tab == 'design' ? 'nav-tab-active' : ''; ?>">
						<?php esc_html_e('Design', 'ticketmachine-event-manager'); ?>
					</a>
					<a href="?page=ticketmachine_settings&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">
						<?php esc_html_e('General', 'ticketmachine-event-manager'); ?>
					</a>
					<a href="?page=ticketmachine_settings&tab=boxes" class="nav-tab <?php echo $active_tab == 'boxes' ? 'nav-tab-active' : ''; ?>">
						<?php esc_html_e('Box View', 'ticketmachine-event-manager'); ?>
					</a>
					<a href="?page=ticketmachine_settings&tab=list" class="nav-tab <?php echo $active_tab == 'list' ? 'nav-tab-active' : ''; ?>">
						<?php esc_html_e('List View', 'ticketmachine-event-manager'); ?>
					</a>
					<a href="?page=ticketmachine_settings&tab=calendar" class="nav-tab <?php echo $active_tab == 'calendar' ? 'nav-tab-active' : ''; ?>">
						<?php esc_html_e('Calendar View', 'ticketmachine-event-manager'); ?>
					</a>
					<a href="?page=ticketmachine_settings&tab=detail" class="nav-tab <?php echo $active_tab == 'detail' ? 'nav-tab-active' : ''; ?>">
						<?php esc_html_e('Details Page', 'ticketmachine-event-manager'); ?>
					</a>
					<a href="?page=ticketmachine_settings&tab=cloud" class="nav-tab <?php echo $active_tab == 'cloud' ? 'nav-tab-active' : ''; ?>">
						<?php esc_html_e('Cloud & Support', 'ticketmachine-event-manager'); ?>
					</a>
				</h2>

				<form method="post" action="#">
					<?php 
						wp_nonce_field( 'ticketmachine_action_save_settings', 'ticketmachine_settings_page_form_nonce' );

						if( $active_tab == 'list' ) {
							include( plugin_dir_path( __FILE__ ) . 'settings_list.php');
						}if( $active_tab == 'boxes' ) {
							include( plugin_dir_path( __FILE__ ) . 'settings_boxes.php');
						}elseif( $active_tab == 'calendar' ) {
							include( plugin_dir_path( __FILE__ ) . 'settings_calendar.php');
						}elseif( $active_tab == 'design' ) {
							include( plugin_dir_path( __FILE__ ) . 'settings_design.php');
						}elseif( $active_tab == 'detail' ) {
							include( plugin_dir_path( __FILE__ ) . 'settings_detail.php');
						}elseif( $active_tab == 'general' ) {
							include( plugin_dir_path( __FILE__ ) . 'settings_general.php');
						}elseif( $active_tab == 'cloud' ) {
							include( plugin_dir_path( __FILE__ ) . 'settings_cloud.php');
						}

							submit_button(); 
					?>
					
				</form>

			</div>

			<?php 
				include( str_replace("/admin/pages", "", plugin_dir_path(__FILE__)) . 'admin/partials/sidebar.php');
			?>
		</div>
	</div>

<?php 
	} 
?>