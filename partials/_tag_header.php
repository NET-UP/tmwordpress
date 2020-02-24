<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    function ticketmachine_tag_header ( $globals ) {
        

        $url = htmlentities(urldecode($_SERVER['REQUEST_URI']));
        $tag = "tag=" . $globals->tag;

        $ticketmachine_output = '<label class="mr-3 ml-1">' . esc_html__("Tags", "ticketmachine") . ':</label> 
                      <div class="card-meta-tag keyword">' . $globals->tag . ' 
                        <a class="ml-2" href="' . str_replace($tag, "", $url) .'">
                            <i class="fa fa-times"></i>
                        </a>
                      </div>';
        return $ticketmachine_output;
    }

?>