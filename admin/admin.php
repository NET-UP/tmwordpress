<?php
    wp_enqueue_style( 'admin_CSS', plugins_url('admin/assets/css/style.css', __FILE__ ) );
    wp_enqueue_style( 'admin_grid_CSS', plugins_url('admin/assets/css/grid.min.css', __FILE__ ) );

    include( plugin_dir_path( __FILE__ ) . 'menu.php');

?>