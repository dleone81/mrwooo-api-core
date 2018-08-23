<?php
class MRWOOO_API_Sanitize {
    /**
    * This class contains all sanitize methods
    * all using add_filter sanitize_option_$option
    * refs: https://developer.wordpress.org/reference/hooks/sanitize_option_option/
    */
    public static function init() {
        if ( ! function_exists( 'sanitize_option' ) ) {
            return false;
        }
    }
    /**
     * This method sanitize ip addresses after submit
    */
    public static function sanitize_ip($value, $option){

        // backup data before
        $_value = get_option('mrwooo_api_allowed', false);

        // value to sanitize
        $arr = array_filter(explode(',', $value['allowed_ips']));

        foreach($arr as $ip){
            $check = filter_var($ip, FILTER_VALIDATE_IP);
            if($check == false){
                add_settings_error( 'mrwooo_messages', 'mrwooo_messages', __( 'Unable to update, check IP format', 'mrwooo' ), 'error' );
                return $_value;
            }
        }
        return $value;
    }
}

?>