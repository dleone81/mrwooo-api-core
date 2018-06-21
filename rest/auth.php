<?php
class MRWOOO_API_Auth {
    private static $version = 'v1';
    private static $route = 'auth';
    
    public static function init() {
        if ( ! function_exists( 'register_rest_route' ) ) {
            return false;
        }

        register_rest_route( MRWOOOAPI_NAMESPACE.'/'.self::$version, '/'.self::$route, array(
            array(
                # endpoint mrwooo/v1/auth
                'methods' => 'POST',
                'callback' => array( 'MRWOOO_API_LIBS_Auth', 'user' )
            )
        ) );
    }
}
?>