<?php
/*************************** LOAD THE BASE CLASS *******************************
 *******************************************************************************
 * The WP_List_Table class isn't automatically available to plugins, so we need
 * to check if it's available and load it if necessary. In this tutorial, we are
 * going to use the WP_List_Table class directly from WordPress core.
 *
 * IMPORTANT:
 * Please note that the WP_List_Table class technically isn't an official API,
 * and it could change at some point in the distant future. Should that happen,
 * I will update this plugin with the most current techniques for your reference
 * immediately.
 *
 * If you are really worried about future compatibility, you can make a copy of
 * the WP_List_Table class (file path is shown just below) to use and distribute
 * with your plugins. If you do that, just remember to change the name of the
 * class to avoid conflicts with core.
 *
 * Since I will be keeping this tutorial up-to-date for the foreseeable future,
 * I am going to work with the copy of the class provided in WordPress core.
 */
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
include_once(str_replace("/admin/pages", "", plugin_dir_path( __FILE__ )) . 'globals.php');



/************************** CREATE A PACKAGE CLASS *****************************
 *******************************************************************************
 * Create a new list table package that extends the core WP_List_Table class.
 * WP_List_Table contains most of the framework for generating the table, but we
 * need to define and override some methods so that our data can be displayed
 * exactly the way we need it to be.
 * 
 * To display this example on a page, you will first need to instantiate the class,
 * then call $yourInstance->prepare_items() to handle any data manipulation, then
 * finally call $yourInstance->display() to render the table to the page.
 * 
 * Our theme for this list table is going to be movies.
 */
class Event_List_Table extends WP_List_Table {

    /** ************************************************************************
     * REQUIRED. Set up a constructor that references the parent constructor. We 
     * use the parent reference to set some default configs.
     ***************************************************************************/
    function __construct(){
        global $status, $page;
    
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'event',     //singular name of the listed records
            'plural'    => 'events',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }

    function get_events(){
        
        include( str_replace("/admin/pages", "", plugin_dir_path(__FILE__)) . 'admin/includes/scriptstyles.php');
        global $globals, $api;

		$params = array();
		if(isset($_GET['s'])){
			$params = array_push_assoc($params, "query", $_GET['s']);
		}
        if(isset($_GET['status']) && $_GET['status'] == "upcoming"){
            $params = array_push_assoc($params, "show_old", 0);
        }else{
            $params = array_push_assoc($params, "show_old", 1);
        }
        $params = array_push_assoc($params, "per_page", 100);
        if(isset($_GET['status']) && $_GET['status'] == "published"){
            $params = array_push_assoc($params, "approved", 1);
        }elseif(isset($_GET['status']) && $_GET['status'] == "drafts") {
            $params = array_push_assoc($params, "approved", 0);
        }
        $params = array_push_assoc($params, "per_page", 100);
        $events = tmapi_events($params)->result;
        return $events;
    }


    /** ************************************************************************
     * Recommended. This method is called when the parent class can't find a method
     * specifically build for a given column. Generally, it's recommended to include
     * one method for each column you want to render, keeping your package class
     * neat and organized. For example, if the class needs to process a column
     * named 'title', it would first see if a method named $this->column_title() 
     * exists - if it does, that method will be used. If it doesn't, this one will
     * be used. Generally, you should try to use custom column methods as much as 
     * possible. 
     * 
     * Since we have defined a column_title() method later on, this method doesn't
     * need to concern itself with any column with a name of 'title'. Instead, it
     * needs to handle everything else.
     * 
     * For more detailed insight into how columns are handled, take a look at 
     * WP_List_Table::single_row_columns()
     * 
     * @param array $item A singular item (one full row's worth of data)
     * @param array $column_name The name/slug of the column to be processed
     * @return string Text or HTML to be placed inside the column <td>
     **************************************************************************/
    function column_default($item, $column_name){
        switch($column_name){
            case 'tags':
                return implode(", ", $item[$column_name]);
            case 'ev_date':
                return date( "d.m.Y", strtotime($item[$column_name]) );
            case 'endtime':
                return date( "d.m.Y", strtotime($item[$column_name]) );
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }


    /** ************************************************************************
     * Recommended. This is a custom column method and is responsible for what
     * is rendered in any column with a name/slug of 'title'. Every time the class
     * needs to render a column, it first looks for a method named 
     * column_{$column_title} - if it exists, that method is run. If it doesn't
     * exist, column_default() is called instead.
     * 
     * This example also illustrates how to implement rollover actions. Actions
     * should be an associative array formatted as 'slug'=>'link html' - and you
     * will need to generate the URLs yourself. You could even ensure the links
     * 
     * 
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
    function column_ev_name($item){
        global $globals, $api;

        $additional_text = "";
        if($item['approved'] == 0){
            $toggle_type = "publish";
            $toggle_text = __("Publish", 'ticketmachine');
            $toggle_action = "publish";
            $additional_text .= " — <span class='post-state'>" . __('Draft' , 'ticketmachine') . "</span>";
        }else{
            $toggle_type = "delete";
            $toggle_text = __("Deactivate", 'ticketmachine');
            $toggle_action = "deactivate";
        }

        if($item['approved'] == 0){
            $view_text = __("Preview", 'ticketmachine');
        }else{
            $view_text = __("View", 'ticketmachine');
        }
        
        //Build row actions
        $actions = array(
            'edit'          => sprintf('<a href="?page=%s&action=%s&id=%s">'.__('Edit', 'ticketmachine').'</a>',$_REQUEST['page'],'edit',$item['id']),
            $toggle_type    => sprintf('<a href="?page=%s&action=%s&id=%s">'.$toggle_text.'</a>',$_REQUEST['page'],$toggle_action,$item['id']),
            'copy'          => sprintf('<a href="?page=%s&action=%s&id=%s">'.__('Copy', 'ticketmachine').'</a>',$_REQUEST['page'],'copy',$item['id']),
            'view'          => sprintf('<a target="_blank" href="/'. $globals->event_slug .'?id=%s">'.$view_text.'</a>',$item['id'])
        );
        
        //Return the title contents
        return sprintf('%1$s %2$s',
        /*$1%s*/ '<strong><a class="row-title" href="?page='.$_REQUEST['page'].'&action=edit&id='.$item['id'].'">'.$item['ev_name'].'</a> '.$additional_text.'</strong>',
        /*$2%s*/ $this->row_actions($actions)
        );
    }


    /** ************************************************************************
     * REQUIRED if displaying checkboxes or using bulk actions! The 'cb' column
     * is given special treatment when columns are processed. It ALWAYS needs to
     * have it's own method.
     * 
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['id']                //The value of the checkbox should be the record's id
        );
    }


    /** ************************************************************************
     * REQUIRED! This method dictates the table's columns and titles. This should
     * return an array where the key is the column slug (and class) and the value 
     * is the column's title text. If you need a checkbox for bulk actions, refer
     * to the $columns array below.
     * 
     * The 'cb' column is treated differently than the rest. If including a checkbox
     * column in your table you must create a column_cb() method. If you don't need
     * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
     * 
     * @see WP_List_Table::::single_row_columns()
     * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_columns(){
        $columns = array(
            //'cb'       => '<input type="checkbox" />',
            'ev_name'  => 'Name',
            'tags'     => 'Schlagwörter',
            'ev_date'  => 'Anfangsdatum',
            'endtime'  => 'Enddatum'
        );
        return $columns;
    }


    /** ************************************************************************
     * Optional. If you want one or more columns to be sortable (ASC/DESC toggle), 
     * you will need to register it here. This should return an array where the 
     * key is the column that needs to be sortable, and the value is db column to 
     * sort by. Often, the key and value will be the same, but this is not always
     * the case (as the value is a column name from the database, not the list table).
     * 
     * This method merely defines which columns should be sortable and makes them
     * clickable - it does not handle the actual sorting. You still need to detect
     * the ORDERBY and ORDER querystring variables within prepare_items() and sort
     * your data accordingly (usually by modifying your query).
     * 
     * @return array An associative array containing all the columns that should be sortable: 'slugs'=>array('data_values',bool)
     **************************************************************************/
    function get_sortable_columns() {
        $sortable_columns = array(
            'ev_name'   => array('ev_name',false), //true means it's already sorted
            'tags'      => array('tags',false),
            'ev_date'   => array('ev_date',false),
            'endtime'   => array('endtime',false)
        );
        return $sortable_columns;
    }


    /** ************************************************************************
     * Optional. If you need to include bulk actions in your list table, this is
     * the place to define them. Bulk actions are an associative array in the format
     * 'slug'=>'Visible Title'
     * 
     * If this method returns an empty value, no bulk action will be rendered. If
     * you specify any bulk actions, the bulk actions box will be rendered with
     * the table automatically on display().
     * 
     * Also note that list tables are not automatically wrapped in <form> elements,
     * so you will need to create those manually in order for bulk actions to function.
     * 
     * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Löschen'
        );
        //return $actions;
    }


    /** ************************************************************************
     * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
     * For this example package, we will handle it in the class to keep things
     * clean and organized.
     * 
     * @see $this->prepare_items()
     **************************************************************************/
    function process_bulk_action() {
        
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            include "actions/event_delete.php";
        }
        
    }

    function search_box( $text, $input_id ) { ?>
        <p class="search-box">
            <label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:</label>
            <input type="search" id="<?php echo $input_id ?>" name="s" value="<?php _admin_search_query(); ?>" />
            <?php submit_button( $text, 'button', false, false, array('id' => 'search-submit') ); ?>
        </p>
    <?php }

    /** ************************************************************************
     * REQUIRED! This is where you prepare your data for display. This method will
     * usually be used to query the database, sort and filter the data, and generally
     * get it ready to be displayed. At a minimum, we should set $this->items and
     * $this->set_pagination_args(), although the following properties and methods
     * are frequently interacted with here...
     * 
     * @global WPDB $wpdb
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     **************************************************************************/
    function prepare_items() {
        global $wpdb; //This is used only if making any database queries

        /**
         * First, lets decide how many records per page to show
         */
        $per_page = 10;
        
        
        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        
        /**
         * REQUIRED. Finally, we build an array to be used by the class for column 
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        
        /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        $this->process_bulk_action();
        
        
        /**
         * Instead of querying a database, we're going to fetch the example data
         * property we created for use in this plugin. This makes this example 
         * package slightly different than one you might build on your own. In 
         * this example, we'll be using array manipulation to sort and paginate 
         * our data. In a real-world implementation, you will probably want to 
         * use sort and pagination data to build a custom query instead, as you'll
         * be able to use your precisely-queried data immediately.
         */
        $data = $this->get_events();
                
        
        /**
         * This checks for sorting input and sorts the data in our array accordingly.
         * 
         * In a real-world situation involving a database, you would probably want 
         * to handle sorting by passing the 'orderby' and 'order' values directly 
         * to a custom query. The returned data will be pre-sorted, and this array
         * sorting technique would be unnecessary.
         */
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'ev_name'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
        usort($data, 'usort_reorder');
        
        
        /***********************************************************************
         * ---------------------------------------------------------------------
         * vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
         * 
         * In a real-world situation, this is where you would place your query.
         *
         * For information on making queries in WordPress, see this Codex entry:
         * http://codex.wordpress.org/Class_Reference/wpdb
         * 
         * ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
         * ---------------------------------------------------------------------
         **********************************************************************/
        
                
        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently 
         * looking at. We'll need this later, so you should always include it in 
         * your own package classes.
         */
        $current_page = $this->get_pagenum();
        
        /**
         * REQUIRED for pagination. Let's check how many items are in our data array. 
         * In real-world use, this would be the total number of items in your database, 
         * without filtering. We'll need this later, so you should always include it 
         * in your own package classes.
         */
        $total_events = count($data);
        
        
        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to 
         */
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        
        
        
        /**
         * REQUIRED. Now we can add our *sorted* data to the items property, where 
         * it can be used by the rest of the class.
         */
        $this->items = $data;
        
        
        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args( array(
            'total_items' => $total_events,            //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_events/$per_page)   //WE have to calculate the total number of pages
        ) );
    }


}

function remove_event(){
    // make api call to delete the event
}

function copy_event(){
    // make api call to copy event
}

/** *************************** RENDER TEST PAGE ********************************
 *******************************************************************************
 * This function renders the admin page and the example list table. Although it's
 * possible to call prepare_items() and display() from the constructor, there
 * are often times where you may need to include logic here between those steps,
 * so we've instead called those methods explicitly. It keeps things flexible, and
 * it's the way the list tables are used in the WordPress core.
 */
function tm_render_list_page(){

    if( isset($_GET['action']) && $_GET['action'] == "edit" ) {
        include "event_edit.php";
    } else {

        if ( isset($_GET['action']) && $_GET['action'] == "save" && isset($_GET['id']) && $_POST ) {
            include "actions/event_save.php";
        } elseif ( isset($_GET['action']) && $_GET['action'] == "publish" && isset($_GET['id']) || isset($_GET['action']) && $_GET['action'] == "deactivate" && isset($_GET['id']) ) {
            include "actions/event_toggle.php";
        } elseif ( isset($_GET['action']) && $_GET['action'] == "delete" && isset($_GET['id']) ) {
            include "actions/event_remove.php";
        } elseif ( isset($_GET['action']) && $_GET['action'] == "copy" && isset($_GET['id']) ){
            include "actions/event_copy.php";
        }

        //Create an instance of our package class...
        $EventListTable = new Event_List_Table();

        ?>
        <div class="wrap tm-admin-page">
            <h1 class="dont-display"></h1>

            <h1>TicketMachine <i class="fas fa-angle-right mx-1"></i> <?php echo __('Events', 'ticketmachine'); ?></h1>
            <a href="?page=tm_events&action=edit" class="button button-secondary ml-2"><?php echo __('Add','ticketmachine'); ?></a>
            
            <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
            <form method="get">
                <!-- For plugins, we also need to ensure that the form posts back to our current page -->
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                <!-- Now we can render the completed list table -->
                <ul class="subsubsub">
                    <li class="all">
                        <a href="<?php echo admin_url() . 'admin.php?page=tm_events'; ?>" <?php if(!isset($_GET['status'])){ ?>class="current"<?php } ?>>
                            <?php echo __('All', 'ticketmachine'); ?> 
                            <span class="count"></span>
                        </a> |
                    </li>
                    <li class="upcoming">
                        <a href="<?php echo admin_url() . 'admin.php?page=tm_events&status=upcoming'; ?>" <?php if(isset($_GET['status']) && $_GET['status'] == "upcoming"){ ?>class="current"<?php } ?>>
                            <?php echo __('Upcoming', 'ticketmachine'); ?> 
                            <span class="count"></span>
                        </a> |
                    </li>
                    <li class="publish">
                        <a href="<?php echo admin_url() . 'admin.php?page=tm_events&status=published'; ?>" <?php if(isset($_GET['status']) && $_GET['status'] == "published"){ ?>class="current"<?php } ?>>
                            <?php echo __('Published', 'ticketmachine'); ?> 
                            <span class="count"></span> <!-- TO DO add logic -->
                        </a> |
                    </li>
                    <li class="draft">
                        <a href="<?php echo admin_url() . 'admin.php?page=tm_events&status=drafts'; ?>" <?php if(isset($_GET['status']) && $_GET['status'] == "drafts"){ ?>class="current"<?php } ?>>
                            <?php echo __('Drafts', 'ticketmachine'); ?> 
                            <span class="count"></span> <!-- TO DO add logic -->
                        </a>
                    </li>
                </ul>
                <?php $EventListTable->search_box(__('Search', 'ticketmachine'), 'search'); ?>
                <!--Fetch, prepare, sort, and filter our data... -->
                <?php $EventListTable->prepare_items(); ?>
                <?php $EventListTable->display(); ?>
            </form>
        </div>
        <?php

    }

}
?>