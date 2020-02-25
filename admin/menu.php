<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	function ticketmachine_admin_menu () {
		global $globals, $api;

		include( plugin_dir_path( __FILE__ ) . 'pages/events.php');
		include( plugin_dir_path( __FILE__ ) . 'pages/categories.php');
		
		if( current_user_can('edit_posts') ) {
			add_menu_page(
				'TicketMachine',
				"TicketMachine",
				'manage_options',
				'ticketmachine_event_manager',
				'ticketmachine_overview_page',
				null,
				96
			);

			if($globals->activated || sanitize_text_field($_GET['code'])) {
				add_submenu_page(
					'ticketmachine_event_manager',
					esc_html__('Overview', 'ticketmachine'),
					esc_html__('Overview', 'ticketmachine'),
					'manage_options',
					'ticketmachine_event_manager',
					'ticketmachine_overview_page',
					null,
					97
				);	

				add_submenu_page(
					'ticketmachine_event_manager',
					esc_html__('Events', 'ticketmachine'),
					esc_html__('Events', 'ticketmachine'),
					'manage_options',
					'ticketmachine_events',
					'ticketmachine_render_list_page',
					null,
					97
				);
				if( current_user_can('manage_options') ) {
					add_submenu_page(
						'ticketmachine_event_manager',
						esc_html__('Settings', 'ticketmachine'),
						esc_html__('Settings', 'ticketmachine'),
						'manage_options',
						'ticketmachine_settings',
						'ticketmachine_settings_page',
						null,
						98
					);
				}
				#add_submenu_page(
				#	'ticketmachine_events',
				#	esc_html__('Categories', 'ticketmachine'),
				#	esc_html__('Categories', 'ticketmachine'),
				#	'manage_options',
				#	'ticketmachine_categories',
				#	'ticketmachine_render_categories_page',
				#	null,
				#	99
				#);
			}else{
				add_submenu_page(
					'ticketmachine_event_manager',
					esc_html__('Install', 'ticketmachine'),
					esc_html__('Install', 'ticketmachine'),
					'manage_options',
					'ticketmachine_event_manager',
					'ticketmachine_installation_page',
					null,
					100
				);
			}
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

		add_action( 'admin_enqueue_scripts', 'ticketmachine_enqueue_admin_style' );
	
    function ticketmachine_enqueue_admin_style() {
        wp_enqueue_style( 'admin_CSS', plugins_url('assets/css/style.css', __FILE__ ) );
        wp_enqueue_style( 'admin_grid_CSS', plugins_url('assets/css/grid.min.css', __FILE__ ) );
        wp_enqueue_style( 'fontawesome-5_CSS', plugins_url('../assets/css/ext/fontawesome.min.css', __FILE__ ) );
        wp_enqueue_style( 'datetimepicker_CSS', plugins_url('assets/css/ext/bootstrap-datetimepicker.css', __FILE__ ) );
        wp_enqueue_style( 'wp-color-picker' );
    
        wp_enqueue_script( 'moment_JS', plugins_url('assets/js/ext/moment-with-locales.js', __FILE__ ) );
        wp_enqueue_script( 'datetimepicker_JS', plugins_url('assets/js/ext/bootstrap-datetimepicker.min.js', __FILE__ ) );
        wp_enqueue_script( 'taginput_JS', plugins_url('assets/js/ext/bootstrap-tag.min.js', __FILE__ ) );
        wp_enqueue_script( 'admin_JS', plugins_url('assets/js/settings.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
    }
		
?>