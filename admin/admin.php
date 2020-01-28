<?php
    wp_enqueue_style( 'admin_CSS', plugins_url('assets/css/style.css', __FILE__ ) );
    wp_enqueue_style( 'admin_grid_CSS', plugins_url('assets/css/grid.min.css', __FILE__ ) );
	wp_enqueue_style( 'fontawesome-5_CSS', plugins_url('../assets/css/ext/fontawesome.min.css', __FILE__ ) );
	wp_enqueue_style( 'datetimepicker_CSS', plugins_url('assets/css/ext/bootstrap-datetimepicker.css', __FILE__ ) );
    
    admin_enqueue_scripts( 'moment_JS', plugins_url('assets/js/ext/moment-with-locales.js', __FILE__ ) );
    admin_enqueue_scripts( 'bootstrap3_JS', plugins_url('assets/js/ext/bootstrap.min.js', __FILE__ ) );
    admin_enqueue_scripts( 'datetimepicker_JS', plugins_url('assets/js/ext/bootstrap-datetimepicker.min.js', __FILE__ ) );
    admin_enqueue_scripts( 'taginput_JS', plugins_url('assets/js/ext/bootstrap-tag.min.js', __FILE__ ) );

    include( plugin_dir_path( __FILE__ ) . 'menu.php');

?>