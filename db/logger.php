<?php
class MRWOOO_DB_Logger {
	private static $event = 'utilities';
    /*
    * This method collect activity log
    * $user int
    * $event string
    * $code int
    * $remote string
    * $time string
    */
    public static function create($user, $event, $code, $remote){
        try {
            global $wpdb;
        
            $table_name = $wpdb->prefix . 'mrwooo_logger';
            
            $i = $wpdb->insert( 
                $table_name, 
                array( 
                    'user_id' => $user,
                    'code' => $code,                  
                    'event' => $event, 
                    'remote' => $remote, 
                    'tstamp' => current_time( 'mysql' )
                ) 
            );
        } catch(PDOException $e){
            die('[MRWOOO_DB_Logger::create] PDO Error: '.$e->getMessage());
        }
    }
    public static function get($per_page){
        try {
            global $wpdb;
            
            $table_name = $wpdb->prefix.'mrwooo_logger';
            $query = "SELECT log_id, user_id, event, code, remote, tstamp FROM $table_name;";
            $logs = $wpdb->get_results($query, OBJECT, $per_page);
            return $logs;
        } catch(PDOException $e){
            die('[MRWOOO_DB_Logger::get] PDO Error: '.$e->getMessage());
        }
    }
    public static function getSingle($user_id){
        try {
            global $wpdb;

            $table_name = $wpdb->prefix.'mrwooo_logger';
            $query = "SELECT log_id, user_id, event, code, remote, tstamp FROM $table_name WHERE user_id=$user_id;";
            $logs = $wpdb->get_results($query, OBJECT);
            return $logs;
        } catch(PDOExeption $e) {
            die('[MRWOOO_DB_Logger::getSingle] PDO Error: '.$e->getMessage());
        }
    }
    /*
    * This method anonimyze data
    * $id int
    * $col string
    * value int / string
    * ref https://codex.wordpress.org/it:Riferimento_classi/wpdb#UPDATE_di_riga
    */
    public static function anonimyze($data, $where, $format = 'string'){
        try {
            global $wpdb;

            $table_name = $wpdb->prefix.'mrwooo_logger';
            $update = $wpdb->update(
                $table_name,
                $data,
                $where,
                $format
            );
            return $update;
        } catch(PDOExeption $e) {
            die('[MRWOOO_DB_Logger::anonimyze] PDO Error: '.$e->getMessage());
        }
    }
}

?>