<?php
class MRWOOO_API_ADMIN_Setting {
    public static function init() {
        if ( ! function_exists( 'register_setting' ) ) {
            return false;
        }

        // register settings
        // ref: https://developer.wordpress.org/reference/functions/register_setting/
        $option_group = 'mrwooo_api';
        $option_name = 'mrwooo_api_allowed';
        $args = [
            'type' => 'string',
            'description' => 'Allowed IP, CORS',
            'sanitize_callback' => null,
            'show_in_rest' => false,
        ];

        register_setting($option_group, $option_name, $args);

        // add setting section
        // ref: https://developer.wordpress.org/reference/functions/add_settings_section/
        $id = 'mrwooo-api';
        $title = __('API Settings', 'mrwooo');
        $callback = '';
        $page = 'mrwooo-api';
        
        add_settings_section($id, $title, $callback, $page);

        // add settings field
        // ref: https://developer.wordpress.org/reference/functions/add_settings_field/
        $id = 'ips';
        $title = __('Allowed IPs', 'mrwooo');
        $callback = array('MRWOOO_API_ADMIN_Setting', 'saveIPs');
        $section = 'mrwooo-api';
        $args = [
            'label_for' => 'allowed_ips',
            'desc_ips' => __('Insert here a comma separated list of allowed IP, eg: 127.0.0.1, 192.168.1.100', 'mrwooo'),
        ];
        // $page is inherited

        add_settings_field( $id, $title, $callback, $page, $section, $args );
    }
    public static function saveIPs($args){
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        
        if ( isset( $_GET['settings-updated'] ) ) {
            add_settings_error( 'mrwooo_messages', 'mrwooo_messages', __( 'Settings Saved', 'mrwooo' ), 'updated' );
        }
            
        // show error/update messages
        settings_errors( 'mrwooo_messages' );
        
        $data = get_option('mrwooo_api_allowed', false);

        if(!$data)
            $arr = ['allowed_ips' => '0.0.0.0'];
        else
            $arr = array_filter(explode(',', $data['allowed_ips']));

        $c = count($arr);

        $ips = '';
        $i = 1;
        foreach($arr as $ip){
            $ips .= $ip;
            if($i != $c)
                $ips .= ',';
            
                $i++;
        }
    ?>

        <textarea name="mrwooo_api_allowed[<?php echo esc_attr($args["label_for"]); ?>]" id="mrwooo_api_allowed[<?php echo esc_attr($args["label_for"]); ?>]" rows="5" cols="60"><?php echo $ips; ?></textarea>
        <p><?php echo esc_attr($args["desc_ips"]); ?></p>
    <?php
    }
}
class MRWOOO_LIBS_Settings {
    public static function getMetaOptions(){
        // retrieve all metas
        // to do that check all users meta
        $users = MRWOOO_LIBS_Users::getUsers();

        $filters = array(
            'nickname',
            'first_name',
            'last_name',
            'description',
            'rich_editing',
            'syntax_highlighting',
            'comment_shortcuts',
            'admin_color',
            'use_ssl',
            'show_admin_bar_front',
            'locale',
            'wp_capabilities',
            'wp_user_level',
            'dismissed_wp_pointers',
            'session_tokens'
        );

        $headings = array();

        foreach($users as $user){
            $id = $user->ID;
            $meta = get_user_meta($id);

            foreach($meta as $key => $value){
                if(!in_array($key, $filters) && (!in_array($key, $headings))){
                    array_push($headings, $key);  
                }
            }
        }

        // retrieve settigs saved
        foreach($headings as $heading) {
            add_option( 'mrwooo_gdpr_export_'.$heading, '0' );
            register_setting('mrwooo', 'mrwooo_gdpr_export_'.$heading);
            echo settings_fields('mrwooo');
        }
        echo 'done';
        exit();
        
        // form
        $o .= '<form method="post" action="options.php">';
        $o .= settings_fields('my-plugin-settings-group');
        $o .= do_settings_sections( 'my-plugin-settings-group' );
        $o .= '<p>CIAO</p>';
        $o .= submit_button();
        $o .= '</form>';

        return $o;
    }
}

?>