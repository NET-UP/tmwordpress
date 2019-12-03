<?php
    wp_enqueue_style( 'admin_CSS', plugins_url('admin/assets/css/style.css', __FILE__ ) );
    wp_enqueue_style( 'admin_grid_CSS', plugins_url('admin/assets/css/grid.min.css', __FILE__ ) );
    wp_enqueue_style( 'jquery-ui-datepicker-style' , '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');
    
    wp_enqueue_script( 'jquery-ui-datepicker' );

    include( plugin_dir_path( __FILE__ ) . 'menu.php');

?>