<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    if ( current_user_can('editor') ) {
        include( plugin_dir_path( __FILE__ ) . 'menu.php');
    }

?>