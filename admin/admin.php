<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    if ( current_user_can('edit_posts') ) {
        include( plugin_dir_path( __FILE__ ) . 'menu.php');
    }

?>