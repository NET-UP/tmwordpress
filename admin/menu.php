<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	include( plugin_dir_path( __FILE__ ) . 'pages/events.php');
	include( plugin_dir_path( __FILE__ ) . 'pages/categories.php');

	function ticketmachine_admin_menu () {
		global $globals, $api;
		
		add_menu_page(
			'TicketMachine',
			"TicketMachine",
			'manage_options',
			'ticketmachine_event_manager',
			'ticketmachine_overview_page',
			null,
			96
		);

		if($globals->activated || $_GET['code']) {
			add_submenu_page(
				'ticketmachine_event_manager',
				__('Overview', 'ticketmachine'),
				__('Overview', 'ticketmachine'),
				'manage_options',
				'ticketmachine_event_manager',
				'ticketmachine_overview_page',
				null,
				97
			);	
			add_submenu_page(
				'ticketmachine_event_manager',
				__('Events', 'ticketmachine'),
				__('Events', 'ticketmachine'),
				'manage_options',
				'ticketmachine_events',
				'ticketmachine_render_list_page',
				null,
				97
			);

			add_submenu_page(
				'ticketmachine_event_manager',
				__('Settings', 'ticketmachine'),
				__('Settings', 'ticketmachine'),
				'manage_options',
				'ticketmachine_settings',
				'ticketmachine_settings_page',
				null,
				98
			);

			#add_submenu_page(
			#	'ticketmachine_events',
			#	__('Categories', 'ticketmachine'),
			#	__('Categories', 'ticketmachine'),
			#	'manage_options',
			#	'ticketmachine_categories',
			#	'ticketmachine_render_categories_page',
			#	null,
			#	99
			#);
		}else{
			add_submenu_page(
				'ticketmachine_event_manager',
				__('Install', 'ticketmachine'),
				__('Install', 'ticketmachine'),
				'manage_options',
				'ticketmachine_event_manager',
				'ticketmachine_installation_page',
				null,
				100
			);
		}
	}

	add_filter( 'submenu_file', function($submenu_file){
		$screen = get_current_screen();
		if($screen->id === 'ticketmachine_event_manager'){
			$submenu_file = 'ticketmachine_event_manager';
		}
		return $submenu_file;
	});
	
	add_action('admin_menu', 'ticketmachine_admin_menu');

	function ticketmachine_overview_page(){
		include( plugin_dir_path( __FILE__) . 'pages/overview.php');
	}
	function ticketmachine_settings_page(){
		include( plugin_dir_path( __FILE__ ) . 'pages/settings.php');
	}
	function ticketmachine_installation_page(){
		include( plugin_dir_path( __FILE__) . 'pages/installation.php');
	}
?>