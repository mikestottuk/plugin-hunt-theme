<?php 
/*!
 * Plugin Hunt Invites List
 * http://www.epicplugins.com
 * V1.0
 *
 * Copyright 2015, Epic Plugins
 *
 * Date: 26/10/15
 */


if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class pluginhunt_List_Table extends WP_List_Table {

    function __construct(){
    global $status, $page;

        parent::__construct( array(
            'singular'  => __( 'invite', 'pluginhunt' ),     //singular name of the listed records
            'plural'    => __( 'invites', 'pluginhunt' ),   //plural name of the listed records
            'ajax'      => false        //does this table support ajax?

    ) );

    add_action( 'admin_head', array( &$this, 'ph_admin_header' ) );            

    }

  function ph_admin_header() {
    $page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
    if( 'my_list_test' != $page )
    return;
    echo '<style type="text/css">';
    echo '.wp-list-table .column-id { width: 5%; }';
    echo '.wp-list-table .column-booktitle { width: 40%; }';
    echo '.wp-list-table .column-author { width: 35%; }';
    echo '.wp-list-table .column-isbn { width: 20%;}';
    echo '.wp-list-table .btn { margin-right:5px; }';
    echo '#wpcontent { background_color: white; }';
    echo '</style>';
  }

  function no_items() {
    _e( 'No one waiting yet.' , 'pluginhunt' );
  }

  function column_default( $item, $column_name ) {
    switch( $column_name ) { 
        case 'ID':
            return $item->ID;
        case 'avatar':
            return get_avatar( $item->ID , 30 );
        case 'username':
            return $item->data->display_name;
        case 'userlevel':
            return ucfirst($item->roles[0]);
        case 'actions':
            return '<div class="btn btn-xs btn-primary upgrade" data-level = "1" data-id="'.$item->ID.'">' . __('Author','pluginhunt') . '</div> <div class="btn btn-xs btn-success upgrade" data-level = "2"  data-id="'.$item->ID.'">'. __('Contributor','pluginhunt') . '</div> <div class="btn btn-xs btn-danger upgrade" data-level = "0"  data-id="'.$item->ID.'">' . __('Decline','pluginhunt') . '</div>';
        default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
    }
  }


function ph_show_actios($ID){
    $user = $ID;
    $html = "<span class='author'>" . __('Upgrade to Author') . "</span><span class='contributor'>" . __('Upgrade to Contributer') . "</span>";
    echo $html;
}

function get_columns(){
        $columns = array(
            'cb'            => '<input type="checkbox" />',
            'ID'            => __( 'ID', 'pluginhunt' ),
            'avatar'        => __( 'Photo','pluginhunt'),
            'username'      => __( 'Username', 'pluginhunt' ),
            'userlevel'     => __( 'User Level', 'pluginhunt' ),
            'actions'       => __('Actions','pluginhunt')
        );
         return $columns;
    }

function usort_reorder( $a, $b ) {
  // If no sort, default to title
  $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'ID';
  // If no order, default to asc
  $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
  // Determine sort order
  $result = strcmp( $a[$orderby], $b[$orderby] );
  // Send final sort direction to usort
  return ( $order === 'asc' ) ? $result : -$result;
}


function get_bulk_actions() {
  $actions = array(
    'ph_author'      => __("Make Author","pluginhunt"),
    'ph_contributor' => __("Make Contributor", "pluginhunt")
  );
  return $actions;
}

function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="user[]" value="%s" />', $item->ID
        );    
    }

function prepare_items() {
  $columns  = $this->get_columns();
  $hidden   = array();
  $this->_column_headers = array( $columns, $hidden, $sortable );
  $current_page = $this->get_pagenum();
  $wp_user_query = new WP_User_Query( array( 'meta_key' => 'ph_access_request', 'meta_value' => '1', 'meta_compare' => '=' ) );
  $this->items = $wp_user_query->get_results();
  $total_items = count( $this->items );

  $this->set_pagination_args( array(
    'total_items' => $total_items                //WE have to determine how many items to show on a page
  ) );
}

} //class

function pluginhunt_render_list_page(){
  $pluginhunt = new pluginhunt_List_Table();
  echo '</pre><div class="wrap"><h2>Waiting List</h2>'; 
  $pluginhunt->prepare_items(); 
?>
  <form method="post">
    <input type="hidden" name="page" value="ttest_list_table">
    <?php
    $pluginhunt->search_box( 'search', 'search_id' );

  $pluginhunt->display(); 
  echo '</form></div>'; 
}