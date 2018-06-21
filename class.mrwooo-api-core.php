<?php
class MRWOOO_API_CORE {
    private static $initiated = false;
	
	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
    }
    
    public static function init_hooks(){
        self::$initiated = true;

        // action
        add_action('admin_init', array('MRWOOO_LIBS_Gdpr_Logger', 'init'));

        // filter
        add_filter('wp_privacy_personal_data_exporters', array('MRWOOO_LIBS_Gdpr_Logger', 'loggerExporter'), 10);
        add_filter('wp_privacy_personal_data_erasers', array('MRWOOO_LIBS_Gdpr_Logger', 'loggerEraser'), 10);
    }
}
?>