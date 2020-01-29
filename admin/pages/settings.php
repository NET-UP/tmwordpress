<?php
	defined("ABSPATH") or die("Permission denied");
	include( str_replace("/admin/pages", "", plugin_dir_path(__FILE__)) . 'admin/includes/scriptstyles.php');
	
	global $wpdb;
    $tm_design = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_design LIMIT 0,1");
    $tm_design = $tm_design[0];
    $tm_config = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ticketmachine_config LIMIT 0,1");
    $tm_config = $tm_config[0];
	
	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'design';
	

	if(isset($_GET['code'])){
		echo "success";
		$tm_config->api_access_token = "test";
		#TODO: Send auth code to /oauth/token to retrieve access & refresh tokens.
		# Save access & refresh tokens
		# Save last_refresh (datetime)
	  }

	#if($tm_config->api_access_token){
?>

<div class="wrap tm-admin-page">
	<h1>TicketMachine <i class="fas fa-angle-right mx-1"></i> <?php echo __('Settings', 'ticketmachine') ?></h1>
 
	<h2 class="nav-tab-wrapper">
		<a href="?page=tm_settings&tab=design" class="nav-tab <?php echo $active_tab == 'design' ? 'nav-tab-active' : ''; ?>">
			<?php echo __('Design', 'ticketmachine'); ?>
		</a>
		<a href="?page=tm_settings&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">
			<?php echo __('General', 'ticketmachine'); ?>
		</a>
		<a href="?page=tm_settings&tab=boxes" class="nav-tab <?php echo $active_tab == 'boxes' ? 'nav-tab-active' : ''; ?>">
			<?php echo __('Box View', 'ticketmachine'); ?>
		</a>
		<a href="?page=tm_settings&tab=list" class="nav-tab <?php echo $active_tab == 'list' ? 'nav-tab-active' : ''; ?>">
			<?php echo __('List View', 'ticketmachine'); ?>
		</a>
		<a href="?page=tm_settings&tab=calendar" class="nav-tab <?php echo $active_tab == 'calendar' ? 'nav-tab-active' : ''; ?>">
			<?php echo __('Calendar View', 'ticketmachine'); ?>
		</a>
		<a href="?page=tm_settings&tab=detail" class="nav-tab <?php echo $active_tab == 'detail' ? 'nav-tab-active' : ''; ?>">
			<?php echo __('Details', 'ticketmachine'); ?>
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
			}
			
			submit_button(); 
		?>
		 
	</form>
</div>

<?php	
	#}else{
	#	include( plugin_dir_path( __FILE__ ) . 'connect.php');
	#}
?>