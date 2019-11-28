<?php
	include( plugin_dir_path( __FILE__ ) . 'pages/events.php');

	function tm_admin_menu () {
		
		add_menu_page(
			'TicketMachine',
			"TicketMachine",
			'manage_options',
			'tm_settings',
			'tm_plugin_menu',
			null,
			98
		);
		
		add_submenu_page(
			'tm_settings',
			__('Veranstaltungen', 'ticketmachine'),
			__('Veranstaltungen', 'ticketmachine'),
			'manage_options',
			'tm_events',
			'tt_render_list_page',
			null,
			97
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

	function tm_settings_page(){
		include( plugin_dir_path( __FILE__ ) . 'pages/settings.php');
	}
?>