<?php
	defined("ABSPATH") or die("Permission denied");
	include( str_replace("/admin/pages", "", plugin_dir_path(__FILE__)) . 'admin/includes/scriptstyles.php');
	
	global $wpdb;
    $ticketmachine_design = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_design LIMIT 0,1");
    $ticketmachine_design = $ticketmachine_design[0];
    $ticketmachine_config = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_config LIMIT 0,1");
    $ticketmachine_config = $ticketmachine_config[0];
	
	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'design';
	
?>

<div class="wrap tm-admin-page">
	<h1 class="dont-display"></h1>

	<div class="row">
		<div class="col-xl-9">
			
			<h1 class="wp-heading-inline mr-3 mb-3">TicketMachine <i class="fas fa-angle-right mx-1"></i> <?php echo __('Settings', 'ticketmachine') ?></h1>
		
			<h2 class="nav-tab-wrapper">
				<a href="?page=ticketmachine_settings&tab=design" class="nav-tab <?php echo $active_tab == 'design' ? 'nav-tab-active' : ''; ?>">
					<?php echo __('Design', 'ticketmachine'); ?>
				</a>
				<a href="?page=ticketmachine_settings&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">
					<?php echo __('General', 'ticketmachine'); ?>
				</a>
				<a href="?page=ticketmachine_settings&tab=boxes" class="nav-tab <?php echo $active_tab == 'boxes' ? 'nav-tab-active' : ''; ?>">
					<?php echo __('Box View', 'ticketmachine'); ?>
				</a>
				<a href="?page=ticketmachine_settings&tab=list" class="nav-tab <?php echo $active_tab == 'list' ? 'nav-tab-active' : ''; ?>">
					<?php echo __('List View', 'ticketmachine'); ?>
				</a>
				<a href="?page=ticketmachine_settings&tab=calendar" class="nav-tab <?php echo $active_tab == 'calendar' ? 'nav-tab-active' : ''; ?>">
					<?php echo __('Calendar View', 'ticketmachine'); ?>
				</a>
				<a href="?page=ticketmachine_settings&tab=detail" class="nav-tab <?php echo $active_tab == 'detail' ? 'nav-tab-active' : ''; ?>">
					<?php echo __('Details Page', 'ticketmachine'); ?>
				</a>
				<a href="?page=ticketmachine_settings&tab=cloud" class="nav-tab <?php echo $active_tab == 'cloud' ? 'nav-tab-active' : ''; ?>">
					<?php echo __('Cloud', 'ticketmachine'); ?>
				</a>
			</h2>

			<form method="post" action="#">

				<?php
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

					if( $active_tab != 'cloud' ) {
						submit_button(); 
					}
				?>
				
			</form>

		</div>

		<?php 
			include( str_replace("/admin/pages", "", plugin_dir_path(__FILE__)) . 'admin/partials/sidebar.php');
		?>
	</div>
</div>