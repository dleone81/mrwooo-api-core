<?php
class MRWOOO_API_CORE_REST {
    private static $initiated = false;
	
	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
    }
    
    public static function init_hooks(){
        self::$initiated = true;

        add_action('admin_menu',    array('MRWOOO_API_ADMIN_Menu','init'));
        add_action('rest_api_init', array('MRWOOO_API_Auth','init'));
        add_action('rest_api_init', array('MRWOOO_API_Check','init'));
        add_action('mrwooo_api_core_ajax', array('MRWOOO_API_LIBS_Ajax', 'loader'));
    }
}
?>