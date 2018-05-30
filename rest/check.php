<?php
class MRWOOO_API_Check {
    private static $version = 'v1';
    private static $route = 'check';

    public function init() {
        if ( ! function_exists( 'register_rest_route' ) ) {
            return false;
        }

        register_rest_route( MRWOOOAPI_NAMESPACE.'/'.self::$version, '/'.self::$route, array(
            array(
                # endpoint mrwooo/v1/check
                'methods' => 'GET',
                'callback' => array( 'MRWOOO_LIB_Check', 'hello' )
            )
        ) );
    }
}
?>