<?php
class MRWOOO_ADMIN_Menu {
    public static function init() {
        if ( ! function_exists( 'add_menu_page' ) ) {
            return false;
        }

        $page_title = __('Mr. Wooo API', 'mrwooo');
        $menu_title = __('MrWooo', 'mrwooo');
        $capability = 'manage_options';
        $menu_slug = 'mrwooo';
        //$callback_menu = 'settingsHtmlMrWooo';
        $callback_menu = 'tabsMrWooo';        
        $icon_url = 'dashicons-smiley';
        $position = 59;
    
        add_menu_page (
            $page_title,
            $menu_title,
            $capability,
            $menu_slug,
            $callback_menu,
            $icon_url,
            $position
        );
    
        // vars submenu
        // settings
        $parent_slug = $menu_slug;
        $page_title = __('Settings', 'mrwooo');
        $menu_title = __('Settings', 'mrwooo');
        $menu_slug = 'mrwooo-settings';
        $callback = 'testSettingPage';
    
        add_submenu_page(
            $parent_slug,
            $page_title,
            $menu_title,
            $capability,
            $menu_slug,
            $callback
        );

        // API
        $page_title = __('API', 'mrwooo');
        $menu_title = __('API', 'mrwooo');
        $menu_slug = 'mrwooo-api';
        $callback = 'settingsHtmlMrWoooApi';
    
        add_submenu_page(
            $parent_slug,
            $page_title,
            $menu_title,
            $capability,
            $menu_slug,
            $callback
        );

        if(is_plugin_active('mrwooo-gdpr/mrwooo-gdpr.php')){
            $page_title = __('Users data-register', 'mrwooo');
            $menu_title = __('Users data-register', 'mrwooo');
            $menu_slug = 'mrwooo-users-data-register';
            $callback = 'tabsMrWoooGdpr';
        
            add_submenu_page(
                $parent_slug,
                $page_title,
                $menu_title,
                $capability,
                $menu_slug,
                $callback
            );
        }

        // Logger
        $page_title = __('Logger', 'mrwooo');
        $menu_title = __('Logger', 'mrwooo');
        $menu_slug = 'mrwooo-logger';
        $callback = 'loggerHtmlMrWoooApi';
    
        add_submenu_page(
            $parent_slug,
            $page_title,
            $menu_title,
            $capability,
            $menu_slug,
            $callback
        );
    }
}

function tabsMrWooo( $current = 'start' ) {
    $o .= '<div class="wrap mrwooo">';
    $o .= ' <h1>Mr. Wooo</h1>';
    // tabs  
    $tabs = array(
        'start' => __('Start now', 'mrwooo'),
        'doc' => __('Documentation', 'mrwooo'),
        'support' => __('Support', 'mrwooo')
    );
    $o .= ' <h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        $o .= '<a class="nav-tab"'.$class.'" href="?page=mrwooo&tab='.$tab.'">'.$name.'</a>';
    }
    $o .= ' </h2>';
    // panels
    $qstring = $_SERVER['argv'][0];
    switch($qstring){
        case 'page=mrwooo&tab=doc':
            $o .= ' <h3>'.__('Check out our documentation','mrwooo').'</h3>';
            $o .= ' <p>'.__('We <i class="fa fa-heart"></i> coding RestFull interface to build awesome mobile apps or connect your wordpress website to thirdy part webapps.', 'mrwooo').'</p>';
            $o .= ' <p>'.__('To make all more simple we have dedicated website for each plugin where you\'ll find methods and examples.','mrwooo').'</p>';
            $o .= ' <p><a href="https://www.mrwooo.com/?utm_source=wp-admin&utm_medium=link&utm_campaign=wooo&utm_term='.get_site_url().'" title="MrWooo website" target="_new">'.__('Discover our SaaS as email marketing and more', 'mrwooo').'</a></p>';
            $o .= ' <p><a href="https://apicore.mrwooo.com/?utm_source=wp-admin&utm_medium=link&utm_campaign=api-core&utm_term='.get_site_url().'" title="MrWooo API Core website" target="_new">'.__('Our API doc, simple and well-written', 'mrwooo').'</a></p>';
            $o .= ' <hr>';
            $o .= ' <p class="mrwooo inline"><i class="fab fa-github"></i><a href="#">MrWooo API Core plugin</a></p>';    
            $o .= ' <p class="mrwooo inline"><i class="fab fa-github"></i><a href="#">MrWooo Automation plugin</a></p>';    
            $o .= ' <p class="mrwooo inline"><i class="fab fa-github"></i><a href="#">MrWooo CRM plugin</a></p>'; 
            break;
        case 'page=mrwooo&tab=support':
            $o .= ' <h3>'.__('Support is provided for paying member only','mrwooo').'</h3>';
            $o .= ' <p>Subscription plan starts at 5,00 € per month. <a href="https://www.mrwooo.com/?utm_source=wp-admin&utm_medium=link&utm_campaign=support&utm_term='.get_site_url().'" title="MrWooo website" target="_new">Take a look what\'s included</a></p>';
            $o .= ' <hr>';
            $o .= ' <p><a href="https://github.com/dleone81/mrwooo-api-core/issues" title="Find a bug?" target="_new">'.__('Bug? Open a ticket on GitHub','mrwooo').'</a></p>';            
            break;
        default:
            $o .= ' <h3>'.__('Thanks for using <strong>Mr.Wooo plugins</strong>', 'mrwooo').'</h3>';
            $o .= ' <p>'.__('MrWooo is a bundle of free plugins that allow you to add new powerful feature to your Wordpress website.', 'mrwooo').'</p>';  
            $o .= ' <p>'.__('We offer great functionality as SaaS (software as a service) that allow you to:', 'mrwooo').'</p>';
            $o .= ' <ul>';
            $o .= '     <li><i class="fa fa-check"></i>'.__('use our API methods to connect your app or webapp to Wordpress, in safe mode :)','wooo-api').'</li>';
            $o .= '     <li><i class="fa fa-clock"></i>'.__('send newsletters from WP backend and get a simple report that show user activity','wooo-api').'</li>';
            $o .= '     <li><i class="fa fa-calendar-alt"></i>'.__('create transactional emails and trigger these to Wordpress or <strong>WooCommerce hook</strong>','wooo-api').'</li>';    
            $o .= '     <li><i class="fa fa-calendar-alt"></i>'.__('integrate this features with <strong>WooCommerce plugin</strong>','wooo-api').'</li>';    
            $o .= '     <li><i class="fa fa-calendar-alt"></i>'.__('create custom form and collect data to our CRM','wooo-api').'</li>';
            $o .= '     <li><i class="fa fa-calendar-alt"></i>'.__('track user activity and collect data to our CRM','wooo-api').'</li>';
            $o .= '     <li><i class="fa fa-calendar-alt"></i>'.__('track user cart (and abandoned cart) and collect data to our CRM','wooo-api').'</li>';
            $o .= ' </ul>';
            $o .= ' <p>'.__('All features are <strong>GDPR compliant</strong>', 'mrwooo').'</p>';            
            $o .= ' <hr>';            
            $o .= ' <p class="mrwooo inline"><i class="fa fa-check"></i><span>'.__('available feature', 'mrwooo').'</span></p>';
            $o .= ' <p class="mrwooo inline"><i class="fa fa-clock"></i><span>'.__('coming soon', 'mrwooo').'</span></p>';
            $o .= ' <p class="mrwooo inline"><i class="fa fa-calendar-alt"></i><span>'.__('scheduled for next release', 'mrwooo').'</span></p>';
    }
    $o .= '</div>'; 
    echo $o;
}

# ref: https://codex.wordpress.org/Creating_Options_Pages
function settingsHtmlMrWooo(){
    $o .= '<div class="wrap mrwooo">';
    $o .= ' <h1>Mr. Wooo</h1>';
    $o .= ' <hr>';
    $o .= ' <p>'.__('Thanks for using <strong>Mr.Wooo plugins</strong>', 'mrwooo').'</p>';
    $o .= ' <p>'.__('MrWooo is a bundle of free plugins that allow you to add new powerful feature to your Wordpress website.', 'mrwooo').'</p>';  
    $o .= ' <p>'.__('We offer great functionality as SaaS (software as a service) that allow you to:', 'mrwooo').'</p>';
    $o .= ' <ul>';
    $o .= '     <li><i class="fa fa-check"></i>'.__('use our API methods to connect your app or webapp to Wordpress, in safe mode :)','wooo-api').'</li>';
    $o .= '     <li><i class="fa fa-clock"></i>'.__('send newsletters from WP backend and get a simple report that show user activity','wooo-api').'</li>';
    $o .= '     <li><i class="fa fa-calendar-alt"></i>'.__('create transactional emails and trigger these to Wordpress or <strong>WooCommerce hook</strong>','wooo-api').'</li>';    
    $o .= '     <li><i class="fa fa-calendar-alt"></i>'.__('integrate this features with <strong>WooCommerce plugin</strong>','wooo-api').'</li>';    
    $o .= '     <li><i class="fa fa-calendar-alt"></i>'.__('create custom form and collect data to our CRM','wooo-api').'</li>';
    $o .= '     <li><i class="fa fa-calendar-alt"></i>'.__('track user activity and collect data to our CRM','wooo-api').'</li>';
    $o .= '     <li><i class="fa fa-calendar-alt"></i>'.__('track user cart (and abandoned cart) and collect data to our CRM','wooo-api').'</li>';
    $o .= ' </ul>';
    $o .= ' <p>'.__('All features are <strong>GDPR compliant</strong>', 'mrwooo').'</p>';
    $o .= ' <hr>';   
    $o .= ' <p>'.__('For any further information take a look to:','mrwooo').'</p>';
    $o .= ' <p><a href="https://www.wooo.com/?utm_source=wp-admin&utm_medium=link&utm_campaign=wooo&utm_term='.get_site_url().'" title="MrWooo website" target="_new">'.__('Discover our SaaS as email marketing and more', 'mrwooo').'</a></p>';
    $o .= ' <p><a href="https://www.wooo.com/?utm_source=wp-admin&utm_medium=link&utm_campaign=api&utm_term='.get_site_url().'" title="MrWooo API website" target="_new">'.__('Our API doc, simple and well-written', 'mrwooo').'</a></p>';
    $o .= ' <p class="mrwooo inline"><i class="fab fa-github"></i><a href="#">MrWooo API plugin</a></p>';    
    $o .= ' <p class="mrwooo inline"><i class="fab fa-github"></i><a href="#">MrWooo Newsletter plugin</a></p>';    
    $o .= ' <p class="mrwooo inline"><i class="fab fa-github"></i><a href="#">MrWooo CRM plugin</a></p>';    
    $o .= ' <hr>';     
    $o .= ' <p class="mrwooo inline"><i class="fa fa-check"></i><span>'.__('available feature', 'mrwooo').'</span></p>';
    $o .= ' <p class="mrwooo inline"><i class="fa fa-clock"></i><span>'.__('coming soon', 'mrwooo').'</span></p>';
    $o .= ' <p class="mrwooo inline"><i class="fa fa-calendar-alt"></i><span>'.__('scheduled for next release', 'mrwooo').'</span></p>';
    $o .= '</div>';
    echo $o;
}

function loggerHtmlMrWoooApi(){
    
    $testListTable = new MRWOOO_LIBS_Utilities_List_Table();
    $testListTable->prepare_items();
    
    $o .= '<div class="wrap mrwooo">';
    $o .= '<h2>'.__('Mr. Wooo Logger', 'mrwooo').'</h2>';
    $o .= '<p>'.__('This is a simple, useful tool for admin, that shown all access via API interface.', 'mrwooo').'</p>';
    $o .= '<p>'.__('Every record will be stored for 30 days', 'mrwooo').'</p>';
    $o .= '<p>'.__('Any actions is denied. This is a log: who, what, when.', 'mrwooo').'</p>';
    echo $o;
    ?>
        
        <form id="logger-filter" method="get">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <?php $testListTable->display() ?>
        </form>
        
    </div>
    <?php
}

function tabsMrWoooGdpr( $current = 'settings' ) {
    $o .= '<div class="wrap mrwooo">';
    $o .= ' <h1>Mr. Wooo</h1>';
    // tabs  
    $tabs = array(
        'settings' => __('Settings', 'mrwooo'),
        'import-export' => __('Import / Export', 'mrwooo'),
        'support' => __('Support', 'mrwooo')
    );
    $o .= ' <h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        $o .= '<a class="nav-tab"'.$class.'" href="?page=mrwooo-users-data-register&tab='.$tab.'">'.$name.'</a>';
    }
    $o .= ' </h2>';
    // panels
    $qstring = $_SERVER['argv'][0];
    switch($qstring){
        case 'page=mrwooo-users-data-register&tab=settings':
            // echo MRWOOO_LIBS_Settings::getMetaOptions();
            
            $o .= ' <h3>'.__('Check out our documentation','mrwooo').'</h3>';
            $o .= ' <p>'.__('We <i class="fa fa-heart"></i> coding RestFull interface to build awesome mobile apps or connect your wordpress website to thirdy part webapps.', 'mrwooo').'</p>';
            $o .= ' <p>'.__('To make all more simple we have dedicated website for each plugin where you\'ll find methods and examples.','mrwooo').'</p>';
            $o .= ' <p><a href="https://www.mrwooo.com/?utm_source=wp-admin&utm_medium=link&utm_campaign=wooo&utm_term='.get_site_url().'" title="MrWooo website" target="_new">'.__('Discover our SaaS as email marketing and more', 'mrwooo').'</a></p>';
            $o .= ' <p><a href="https://apicore.mrwooo.com/?utm_source=wp-admin&utm_medium=link&utm_campaign=api-core&utm_term='.get_site_url().'" title="MrWooo API Core website" target="_new">'.__('Our API doc, simple and well-written', 'mrwooo').'</a></p>';
            $o .= ' <hr>';
            $o .= ' <p class="mrwooo inline"><i class="fab fa-github"></i><a href="#">MrWooo API Core plugin</a></p>';    
            $o .= ' <p class="mrwooo inline"><i class="fab fa-github"></i><a href="#">MrWooo Automation plugin</a></p>';    
            $o .= ' <p class="mrwooo inline"><i class="fab fa-github"></i><a href="#">MrWooo CRM plugin</a></p>'; 
            break;
        case 'page=mrwooo-users-data-register&tab=import-export':
            // created hook for import / export
            do_action('mrwooo_gdpr_import_export');

            $urlPersonalData = '<a href="'.get_site_url().'/wp-admin/tools.php?page=export_personal_data" title="Report">';
            $textPersonalData = 'If you need to know specific data use %s Export personal data feature" to do it.';

            $o .= ' <h3>'.__('Users data registry','mrwooo').'</h3>';
            $o .= ' <p>'.__('This feature permits to collects <strong>all users data</strong> organized into registry GDPR compliant.', 'mrwooo').'</p>';
            $o .= ' <p>'.__('As required by privacy GDPR, you have all infos collect in one file.', 'mrwooo').'</p>';
            $o .= ' <p>'.sprintf(__($textPersonalData, 'mrwooo'), $urlPersonalData).'</a></p>';
            $o .= ' <div class="card mrwooo">';
            $o .= '   <h3>'.__('Import data from .csv', 'mrwooo').'</h3>';
            $o .= '   <form id="importUsersData" action="#" enctype="multipart/form-data">';
            $o .= '     <input type="text" name="metakey">';
            $o .= '     <input type="hidden" name="action" value="importUsersData" >';            
            $o .= '     <input type="hidden" name="'.$metakey.'" multiple="false">';
            $o .= '     <input type="file" id="filename" name="import" multiple="false">';
            $o .= get_submit_button(__('Import Users to data-register (.csv)', 'mrwooo'), 'primary', 'import_users_data', false);            
            $o .= '   </form>';
            $o .= ' </div>';
            $o .= ' <div class="card mrwooo">';
            $o .= '  <h3>'.__('Export Users data-register', 'mrwooo').'</h3>';
            $o .= '  <form id="exportUsersData" action="#">';
            $o .= '   <input type="hidden" name="action" value="usersData">';
            $o .= get_submit_button(__('Export Users data registry (.csv)', 'mrwooo'), 'primary', 'export_users_data', false);
            $o .= '  </form>';
            $o .= ' </div>';
            $o .= ' <hr>';
            $o .= ' <p><a href="https://github.com/dleone81/mrwooo-api-core/issues" title="Find a bug?" target="_new">'.__('Bug? Open a ticket on GitHub','mrwooo').'</a></p>';            
            break;
        default:
            $o .= ' <h3>'.__('Thanks for using <strong>Mr.Wooo plugins</strong>', 'mrwooo').'</h3>';
            $o .= ' <p>'.__('MrWooo is a bundle of free plugins that allow you to add new powerful feature to your Wordpress website.', 'mrwooo').'</p>';  
            $o .= ' <p>'.__('We offer great functionality as SaaS (software as a service) that allow you to:', 'mrwooo').'</p>';
            $o .= ' <ul>';
            $o .= '     <li><i class="fa fa-check"></i>'.__('use our API methods to connect your app or webapp to Wordpress, in safe mode :)','wooo-api').'</li>';
            $o .= '     <li><i class="fa fa-clock"></i>'.__('send newsletters from WP backend and get a simple report that show user activity','wooo-api').'</li>';
            $o .= '     <li><i class="fa fa-calendar-alt"></i>'.__('create transactional emails and trigger these to Wordpress or <strong>WooCommerce hook</strong>','wooo-api').'</li>';    
            $o .= '     <li><i class="fa fa-calendar-alt"></i>'.__('integrate this features with <strong>WooCommerce plugin</strong>','wooo-api').'</li>';    
            $o .= '     <li><i class="fa fa-calendar-alt"></i>'.__('create custom form and collect data to our CRM','wooo-api').'</li>';
            $o .= '     <li><i class="fa fa-calendar-alt"></i>'.__('track user activity and collect data to our CRM','wooo-api').'</li>';
            $o .= '     <li><i class="fa fa-calendar-alt"></i>'.__('track user cart (and abandoned cart) and collect data to our CRM','wooo-api').'</li>';
            $o .= ' </ul>';
            $o .= ' <p>'.__('All features are <strong>GDPR compliant</strong>', 'mrwooo').'</p>';            
            $o .= ' <hr>';            
            $o .= ' <p class="mrwooo inline"><i class="fa fa-check"></i><span>'.__('available feature', 'mrwooo').'</span></p>';
            $o .= ' <p class="mrwooo inline"><i class="fa fa-clock"></i><span>'.__('coming soon', 'mrwooo').'</span></p>';
            $o .= ' <p class="mrwooo inline"><i class="fa fa-calendar-alt"></i><span>'.__('scheduled for next release', 'mrwooo').'</span></p>';
    }
    $o .= '</div>'; 
    echo $o;
}

?>