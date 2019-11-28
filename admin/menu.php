<?php
	function tm_admin_menu () {
		
		add_menu_page(
			'TicketMachine',
			"TicketMachine",
			'manage_options',
			'tm_settings',
			'tm_settings_page',
			null,
			98
		);
		
		add_submenu_page(
			'tm_events',
			__('Veranstaltungen', 'ticketmachine'),
			__('Veranstaltungen', 'ticketmachine'),
			'manage_options',
			'tm_events',
			'tm_events_page',
			null,
			98
		);
		add_submenu_page(
			'tm_settings',
			__('Einstellungen', 'ticketmachine'),
			__('Einstellungen', 'ticketmachine'),
			'manage_options',
			'tm_settings',
			'tm_settings_page',
			null,
			98
		);
	}
	
	add_action('admin_menu', 'tm_admin_menu');
	
	function tt_add_menu_items(){
		include( plugin_dir_path( __FILE__ ) . 'pages/events.php');
		add_menu_page('Example Plugin List Table', 'List Table Example', 'activate_plugins', 'tt_list_test', 'tt_render_list_page');
	} 
	add_action('admin_menu', 'tt_add_menu_items');

	function tm_events_page(){
	}
	function tm_settings_page(){
		include( plugin_dir_path( __FILE__ ) . 'pages/settings.php');
	}
?>