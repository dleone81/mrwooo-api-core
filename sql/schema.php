<?php
#ref https://codex.wordpress.org/Creating_Tables_with_Plugins

global $mrwooo_db_version;
// use the same version of that will be committed
$mrwooo_db_version = '0.1.0';

function update() {
    global $wpdb;
 
    $table_name = $wpdb->prefix . "mrwooo_logger";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        `log_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
        `user_id` VARCHAR(45) NOT NULL,
        `event` VARCHAR(45) NOT NULL,
        `code` BIGINT(20) NOT NULL,
        `remote` VARCHAR(255) NOT NULL,
        `tstamp` DATETIME NOT NULL,
        PRIMARY KEY (`log_id`)) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    add_option( 'mrwooo_db_version', $mrwooo_db_version );
}
?>