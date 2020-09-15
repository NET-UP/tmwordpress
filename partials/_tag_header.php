<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    function ticketmachine_tag_header ( $tm_globals ) {
        

        $url = htmlentities(urldecode($_SERVER['REQUEST_URI']));
        $tag = "tag=" . $tm_globals->tag;

        $ticketmachine_output = '<label class="mr-3 ml-1">' . esc_html__("Tags", "ticketmachine-event-manager") . ':</label> 
                      <div class="card-meta-tag keyword">' . $tm_globals->tag . ' 
                        <a class="ml-2" href="' . str_replace($tag, "", esc_url($url)) .'">
                            <i class="fa fa-times"></i>
                        </a>
                      </div>';
        return $ticketmachine_output;
    }

?>