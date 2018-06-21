<?php
/*
Plugin Name:  MrWooo API Core
Plugin URI:   https://api.mrwooo.com
Description:  RESTFull API. This plugin is required for all MrWooo Plugins. Thanks for that you can connect to your wordpress in safe mode and use WordPress as framework for your mobile app or webapp.
Version:      0.1.0
Author:       Domenico Leone
Author URI:   https://github.com/dleone81
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  mrwooop-api
Domain Path:  /languages
*/

/*
naming convention for developers
libs/ contains class named MRWOOO_LIB_ClassName
rest/ contains class named MRWOOO_API_ClassName

use the same file name to relationing of rest/ and libs/ methods
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// define
define( 'MRWOOOAPICORE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MRWOOOAPI_NAMESPACE', 'mrwooo' );
define( 'MRWOOOJWTKEY', 'Hwk8EIK6ZOHEQKz/4a0N2hZNJCXxP2Ie55e+NV3oyhA');

// i18n
load_plugin_textdomain('mrwooo', false, basename( dirname( __FILE__ ) ) . '/languages' );

// composer libs
require_once(MRWOOOAPICORE_PLUGIN_DIR.'vendor/autoload.php');

// admin
require_once(MRWOOOAPICORE_PLUGIN_DIR.'admin/menu.php');

// DB
require_once(MRWOOOAPICORE_PLUGIN_DIR.'db/logger.php');

// libs
require_once(MRWOOOAPICORE_PLUGIN_DIR.'libs/ajax.php');
require_once(MRWOOOAPICORE_PLUGIN_DIR.'libs/auth.php');
require_once(MRWOOOAPICORE_PLUGIN_DIR.'libs/check.php');
require_once(MRWOOOAPICORE_PLUGIN_DIR.'libs/gdpr.php');
require_once(MRWOOOAPICORE_PLUGIN_DIR.'libs/settings.php');
require_once(MRWOOOAPICORE_PLUGIN_DIR.'libs/utilities.php');

// rest
// legacy API https://developer.wordpress.org/rest-api/
// codex https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/

require_once(MRWOOOAPICORE_PLUGIN_DIR.'rest/auth.php');
require_once(MRWOOOAPICORE_PLUGIN_DIR.'rest/check.php');

// sql
require_once(MRWOOOAPICORE_PLUGIN_DIR.'sql/schema.php');

// update db
register_activation_hook( __FILE__, 'update' );

// init
require_once(MRWOOOAPICORE_PLUGIN_DIR.'class.mrwooo-api-core.php');
add_action('init', array('MRWOOO_API_CORE', 'init'));

require_once(MRWOOOAPICORE_PLUGIN_DIR.'class.mrwooo-api-core-rest.php');
add_action('init', array('MRWOOO_API_CORE_REST', 'init'));


// action
/* add_action('admin_menu',    array('MRWOOO_API_ADMIN_Menu','init'));
// add_action('admin_init', array('MRWOOO_LIBS_Gdpr_Logger', 'init'));
add_action('rest_api_init', array('MRWOOO_API_LIBS_Auth','init'));
add_action('rest_api_init', array('MRWOOO_API_LIBS_Check','init'));
add_action('mrwooo_api_core_ajax', array('MRWOOO_API_LIBS_Ajax', 'loader')); */

// filter
//add_filter('wp_privacy_personal_data_exporters', array('MRWOOO_LIBS_Gdpr_Logger', 'loggerExporter'), 10);
//add_filter('wp_privacy_personal_data_erasers', array('MRWOOO_LIBS_Gdpr_Logger', 'loggerEraser'), 10);

// ref: https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
function mrwooo_api_core_script($hook){
    
    $toplevel = array(  
        'toplevel_page_mrwooo',
        'mrwooo_page_mrwooo-settings',
        'mrwooo_page_mrwooo-api',
        'mrwooo_page_mrwooo-users-data-register',
        'mrwooo_page_mrwooo-logger'
    );

    if($hook != in_array($hook, $toplevel)) {
        return;
    }

    // adding CSS
    wp_register_style('fontawesome', 'https://use.fontawesome.com/releases/v5.0.13/css/all.css');
    wp_enqueue_style('fontawesome');
    wp_register_style('mrwooo_api_core', plugins_url('admin/css/style.css', __FILE__) );
    wp_enqueue_style('mrwooo_api_core');

    // adding JS
    // adding js overlay image
    wp_register_script( 'code', plugins_url('admin/js/code.js', __FILE__), array('jquery') );
    wp_enqueue_script('code');
    wp_register_script( 'notice', plugins_url('admin/js/notice.js', __FILE__), array('jquery') );
    wp_enqueue_script('notice');

}
add_action( 'admin_enqueue_scripts', 'mrwooo_api_core_script' );
?>