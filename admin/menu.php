<?php
	include( plugin_dir_path( __FILE__ ) . 'pages/events.php');
	include( plugin_dir_path( __FILE__ ) . 'pages/categories.php');

	function tm_admin_menu () {
		global $globals, $api;
		
		add_menu_page(
			'TicketMachine',
			"TicketMachine",
			'manage_options',
			'tm_events',
			'tm_plugin_menu',
			null,
			96
		);

		if(!$globals->activated || $_GET['code']) {	
			add_submenu_page(
				'tm_events',
				__('Install', 'ticketmachine'),
				__('Install', 'ticketmachine'),
				'manage_options',
				'tm_events',
				'tm_installation_page',
				null,
				100
			);
		}else{
			add_submenu_page(
				'tm_events',
				__('Events', 'ticketmachine'),
				__('Events', 'ticketmachine'),
				'manage_options',
				'tm_events',
				'tt_render_list_page',
				null,
				97
			);

			add_submenu_page(
				'tm_events',
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
		}
	}

	add_filter( 'submenu_file', function($submenu_file){
		$screen = get_current_screen();
		if($screen->id === 'tm_events'){
			$submenu_file = 'tm_events';
		}
		return $submenu_file;
	});
	
	add_action('admin_menu', 'tm_admin_menu');

	function tm_settings_page(){
		include( plugin_dir_path( __FILE__ ) . 'pages/settings.php');
	}
	function tm_installation_page(){
		include( plugin_dir_path( __FILE__) . 'pages/installation.php');
	}
?>