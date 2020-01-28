<?php
    wp_enqueue_style( 'admin_CSS', plugins_url('../assets/css/style.css', __FILE__ ) );
    wp_enqueue_style( 'admin_grid_CSS', plugins_url('../assets/css/grid.min.css', __FILE__ ) );
	wp_enqueue_style( 'fontawesome-5_CSS', plugins_url('../../assets/css/ext/fontawesome.min.css', __FILE__ ) );
	wp_enqueue_style( 'datetimepicker_CSS', plugins_url('../assets/css/ext/bootstrap-datetimepicker.css', __FILE__ ) );
    
    wp_enqueue_script( 'moment_JS', plugins_url('../assets/js/ext/moment-with-locales.js', __FILE__ ) );
    wp_enqueue_script( 'bootstrap4_JS', plugins_url('../assets/js/ext/bootstrap.min.js', __FILE__ ) );
    wp_enqueue_script( 'datetimepicker_JS', plugins_url('../assets/js/ext/bootstrap-datetimepicker.min.js', __FILE__ ) );
    wp_enqueue_script( 'taginput_JS', plugins_url('../assets/js/ext/bootstrap-tag.min.js', __FILE__ ) );
?>