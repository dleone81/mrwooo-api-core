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

// DB
require_once(MRWOOOAPICORE_PLUGIN_DIR.'db/logger.php');

// libs
require_once(MRWOOOAPICORE_PLUGIN_DIR.'libs/ajax.php');
require_once(MRWOOOAPICORE_PLUGIN_DIR.'libs/auth.php');
require_once(MRWOOOAPICORE_PLUGIN_DIR.'libs/check.php');
require_once(MRWOOOAPICORE_PLUGIN_DIR.'libs/gdpr.php');
require_once(MRWOOOAPICORE_PLUGIN_DIR.'libs/menu.php');
require_once(MRWOOOAPICORE_PLUGIN_DIR.'libs/sanitize.php');
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
        'mrwooo-plugins_page_mrwooo-settings',
        'mrwooo-plugins_page_mrwooo-api',
        'mrwooo-plugins_page_mrwooo-users-data-register',
        'mrwooo-plugins_page_mrwooo-logger'
    );

    if($hook != in_array($hook, $toplevel)) {
        return;
    }

    // adding CSS
    # ref: https://developer.wordpress.org/themes/basics/including-css-javascript/
    wp_register_style('fontawesome', 'https://use.fontawesome.com/releases/v5.0.13/css/all.css');
    wp_enqueue_style('fontawesome');
    wp_register_style('style', plugins_url('admin/css/style.css', __FILE__) );
    wp_enqueue_style('style');

    // adding JS
    // adding js overlay image
    wp_register_script( 'code', plugins_url('admin/js/code.js', __FILE__), array('jquery') );
    wp_enqueue_script('code');
    wp_register_script( 'notice', plugins_url('admin/js/notice.js', __FILE__), array('jquery') );
    wp_enqueue_script('notice');
}
add_action( 'admin_enqueue_scripts', 'mrwooo_api_core_script' );

/* TEST */
/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */
 
/**
 * custom option and settings
 */
function wporg_settings_init() {
    // register a new setting for "wporg" page
    register_setting( 'wporg', 'wporg_options' );
    
    // register a new section in the "wporg" page
    add_settings_section(
        'wporg_section_developers',
        __( 'The Matrix has you.', 'wporg' ),
        'wporg_section_developers_cb',
        'wporg'
    );
    
    // register a new field in the "wporg_section_developers" section, inside the "wporg" page
    add_settings_field(
        'wporg_field_pill', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __( 'Pill', 'wporg' ),
        'wporg_field_pill_cb',
        'wporg',
        'wporg_section_developers',
        [
            'label_for' => 'wporg_field_pill',
            'class' => 'wporg_row',
            'wporg_custom_data' => 'custom',
        ]
    );
   }
    
   /**
    * register our wporg_settings_init to the admin_init action hook
    */
   add_action( 'admin_init', 'wporg_settings_init' );
    
   /**
    * custom option and settings:
    * callback functions
    */
    
   // developers section cb
    
   // section callbacks can accept an $args parameter, which is an array.
   // $args have the following keys defined: title, id, callback.
   // the values are defined at the add_settings_section() function.
   function wporg_section_developers_cb( $args ) {
    ?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Follow the white rabbit.', 'wporg' ); ?></p>
    <?php
   }
    
   function wporg_field_pill_cb( $args ) {
    $options = get_option( 'wporg_options' );
    ?>
    <select id="<?php echo esc_attr( $args['label_for'] ); ?>"
    data-custom="<?php echo esc_attr( $args['wporg_custom_data'] ); ?>"
    name="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
    >
    <option value="red" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'red', false ) ) : ( '' ); ?>>
    <?php esc_html_e( 'red pill', 'wporg' ); ?>
    </option>
    <option value="blue" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'blue', false ) ) : ( '' ); ?>>
    <?php esc_html_e( 'blue pill', 'wporg' ); ?>
    </option>
    </select>
    <p class="description">
    <?php esc_html_e( 'You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'wporg' ); ?>
    </p>
    <p class="description">
    <?php esc_html_e( 'You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'wporg' ); ?>
    </p>
    <?php
   }
    
   /**
    * top level menu
    */
   function wporg_options_page() {
    // add top level menu page
    add_menu_page(
    'WPOrg',
    'WPOrg Options',
    'manage_options',
    'wporg',
    'wporg_options_page_html'
    );
   }
    
   /**
    * register our wporg_options_page to the admin_menu action hook
    */
   add_action( 'admin_menu', 'wporg_options_page' );
    
   /**
    * top level menu:
    * callback functions
    */
   function wporg_options_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
    return;
    }

    if ( isset( $_GET['settings-updated'] ) ) {
    add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'wporg' ), 'updated' );
    }
    
    // show error/update messages
    settings_errors( 'wporg_messages' );
    ?>
    <div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <form action="options.php" method="POST">
    <?php
    settings_fields( 'wporg' );
    do_settings_sections( 'wporg' );
    submit_button( 'Save Settings' );
    ?>
    </form>
    </div>
    <?php
   }
?>