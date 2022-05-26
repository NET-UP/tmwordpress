<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    if(!function_exists("ticketmachine_tag_header")){
        function ticketmachine_tag_header ( $tm_globals ) {
            $tm_url = htmlentities(urldecode($_SERVER['REQUEST_URI']));
            $tag = "tag=" . $tm_globals->tag;

            $ticketmachine_output = '<label class="me-3 ms-1">' . esc_html__("Tags", "ticketmachine-event-manager") . ':</label> 
                        <div class="card-meta-tag keyword">' . $tm_globals->tag . ' 
                            <a class="ms-2" href="' . str_replace($tag, "", esc_url($tm_url)) .'">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>';
            return $ticketmachine_output;
        }
    }

?>