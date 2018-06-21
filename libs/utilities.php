<?php
#ref https://codex.wordpress.org/Class_Reference/WP_List_Table
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class MRWOOO_LIBS_Utilities_List_Table extends WP_List_Table {
    
    function __construct(){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'log',
            'plural'    => 'logs',
            'ajax'      => false
        ) );
        
    }
    function column_default($item, $column_name){
        switch($column_name){
            case 'user_id':
            case 'event':
            case 'code':
            case 'remote':
            case 'tstamp':
                return $item[$column_name];
            default:
                return print_r($item,true);
        }
    }
    function column_title($item){
        
        //Build row actions
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&log=%s">Edit</a>',$_REQUEST['page'],'edit',$item['log_id']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&log=%s">Delete</a>',$_REQUEST['page'],'delete',$item['log_id']),
        );
        
        //Return the title contents
        return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
            $item['log_id'],
            $item['log_id'],
            $this->row_actions($actions)
        );
    }
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            $this->_args['singular'],
            $item['log_id']
        );
    }
    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'user_id'     => 'User id',
            'code'    => 'Code',                        
            'event'    => 'Event',
            'remote'  => 'Remote',
            'tstamp' => 'Time'
        );
        return $columns;
    }
    function prepare_items() {
        global $wpdb;
        $per_page = 25;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();
        
        $logs = MRWOOO_DB_Logger::get($per_page);

        $dataTemp = array();
        $i = 0;

        foreach($logs as $log){
            $dataTemp = array(
                'log_id' => $log->log_id,
                'user_id' => $log->user_id,
                'code' => $log->code,                
                'event' => $log->event,
                'remote' => $log->remote,
                'tstamp' => $log->tstamp,
            );
            $data[$i] = $dataTemp;
            $i++;
        }

        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'tstamp';
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc';
            $result = strcmp($a[$orderby], $b[$orderby]);
            return ($order==='DESC') ? $result : -$result;
        }
        usort($data, 'usort_reorder');

        $current_page = $this->get_pagenum();
        $total_items = count($data);
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        $this->items = $data;
        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items/$per_page)
        ) );
    }
}
?>