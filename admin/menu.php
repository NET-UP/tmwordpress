<?php
	include( plugin_dir_path( __FILE__ ) . 'pages/events.php');
	include( plugin_dir_path( __FILE__ ) . 'pages/categories.php');

	function tm_admin_menu () {
		global $globals, $api;
		
		add_menu_page(
			'TicketMachine',
			"TicketMachine",
			'manage_options',
			'tm_event_manager',
			'tm_plugin_menu',
			null,
			96
		);

		if($globals->activated) {
			add_submenu_page(
				'tm_event_manager',
				__('Events', 'ticketmachine'),
				__('Events', 'ticketmachine'),
				'manage_options',
				'tm_event_manager',
				'tt_overview_page',
				null,
				97
			);	
			add_submenu_page(
				'tm_event_manager',
				__('Events', 'ticketmachine'),
				__('Events', 'ticketmachine'),
				'manage_options',
				'tm_events',
				'tt_render_list_page',
				null,
				97
			);

			add_submenu_page(
				'tm_event_manager',
				__('Settings', 'ticketmachine'),
				__('Settings', 'ticketmachine'),
				'manage_options',
				'tm_settings',
				'tm_settings_page',
				null,
				98
			);

			#add_submenu_page(
			#	'tm_events',
			#	__('Categories', 'ticketmachine'),
			#	__('Categories', 'ticketmachine'),
			#	'manage_options',
			#	'tm_categories',
			#	'tm_render_categories_page',
			#	null,
			#	99
			#);
		}else{
			add_submenu_page(
				'tm_event_manager',
				__('Install', 'ticketmachine'),
				__('Install', 'ticketmachine'),
				'manage_options',
				'tm_event_manager',
				'tm_installation_page',
				null,
				100
			);
		}
	}

	add_filter( 'submenu_file', function($submenu_file){
		$screen = get_current_screen();
		if($screen->id === 'tm_event_manager'){
			$submenu_file = 'tm_event_manager';
		}
		return $submenu_file;
	});
	
	add_action('admin_menu', 'tm_admin_menu');

	function tm_overview_page(){
		include( plugin_dir_path( __FILE__) . 'pages/overview.php');
	}
	function tm_settings_page(){
		include( plugin_dir_path( __FILE__ ) . 'pages/settings.php');
	}
	function tm_installation_page(){
		include( plugin_dir_path( __FILE__) . 'pages/installation.php');
	}
?>