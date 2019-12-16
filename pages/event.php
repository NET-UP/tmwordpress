<?php

	function tm_display_event ( $atts, $globals, $api ) {
		global $event;

		$params = [ "id" => $_GET['id'] ];
		$event = tmapi_event($params);

		$tm_output .= '
			<div class=	"col-12">
				<div class="row">
					<div class="col-12 col-lg-5 col-xl-6 tm_left">';
		$tm_output .= tm_event_page_information($event, $globals);
		$tm_output .= '
					</div>
					<div class="col-12 col-lg-7 col-xl-6 tm_right">';
		$tm_output .= tm_event_page_actions($event, $globals);
		$tm_output .= tm_event_page_tickets($event, $globals);			
		$tm_output .= tm_event_page_details($event, $globals);			
		$tm_output .= tm_event_page_google_map($event, $globals);
		$tm_output .= '
					</div>
				</div>
			</div>
		';
		
		return $tm_output;
		
	}

	function tm_event_metadata() {
		global $event;
		echo '<meta property="og:title" content="' . $event->ev_name . '" />';
	}

	add_action('wp_head','tm_event_metadata');

	function theme_xyz_header_metadata() {

		// Post object if needed
		// global $post;
	
		// Page conditional if needed
		// if( is_page() ){}
	
	  ?>
	
		<meta name="abc" content="xyz" />
	
	  <?php
	
	}
	add_action( 'wp_head', 'theme_xyz_header_metadata' );

?>