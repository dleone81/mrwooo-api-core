<?php
class MRWOOO_LIBS_Gdpr_Logger {
  public static function init() {
    if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
        return false;
    }
    # ref https://developer.wordpress.org/plugins/privacy/suggesting-text-for-the-site-privacy-policy/
    $content = sprintf(
        __( 'This plugin stores user_id, request, status code and IP from user made that request for security purpose only. This data are stored time-limited (30 days) on local database and will not be share out to thirdy part services. If user requests to delete our personal datas is possibile to delete them throught \'Erase personal data\' WordPress feature by admin only. For additional infos <a href="%s" target="_blank">visit our dedicated page</a>.',
        'mrwooo' ),
        'http://dev-wp.local:8080/privacy-policy'
    );
  
    wp_add_privacy_policy_content(
        'MrWooo API Core',
        wp_kses_post( wpautop( $content, false ) )
    );
  }
  /*
  * This method add personal data stored by plugin
  * to exporter
  * ref: https://developer.wordpress.org/plugins/privacy/adding-the-personal-data-exporter-to-your-plugin/
  */
  public static function exporter( $email_address ){
    $number = 500;

    $group_id = 'logger';
    $group_label = __('API Core Logger', 'mrwooo');
    $item_id = 'exporter-'.$email_address;
    
    $user = get_user_by('email', $email_address);
    $id = $user->ID;

    $logs = MRWOOO_DB_Logger::getSingle($id);
  
    $export_items = array();

    $i = 0;
    foreach ($logs as $log) {
      $event = $log->event;
      $code = $log->code;
      $remote = $log->remote;
      $tstamp = $log->tstamp;

      $data = array(
        array(
          'name' => __( 'Event', 'mrwooo' ),
          'value' => $event
        ),
        array(
          'name' => __( 'Status code', 'mrwooo' ),
          'value' => $code
        ),
        array(
          'name' => __( 'Remote', 'mrwooo' ),
          'value' => $remote
        ),
        array(
          'name' => __( 'Timestamp', 'mrwooo' ),
          'value' => $tstamp
        )
      );
      $export_items[] = array(
        'group_id' => $group_id,
        'group_label' => $group_label,
        'item_id' => $item_id,
        'data' => $data,
      ); 
      $i++;
    }
    $done = count( $i ) < $number;
    return array(
      'data' => $export_items,
      'done' => $done,
    );
  }
  public static function loggerExporter( $exporters ) {
    $exporters['mrwooo-api-core'] = array(
      'exporter_friendly_name' => __( 'MrWooo API Core', 'mrwooo' ),
      'callback' => array('MRWOOO_LIBS_Gdpr', 'exporter'),
    );
    return $exporters;
  }
  /*
  * This method delete personal data
  * ref: https://developer.wordpress.org/plugins/privacy/adding-the-personal-data-eraser-to-your-plugin/
  */
  public static function eraser( $email_address ){
    $number = 500;
    $user = get_user_by('email', $email_address);
    $id = $user->ID;
    $i = 0;    
    $items_removed = false;

    $logs = MRWOOO_DB_Logger::getSingle($id);
    $value = wp_privacy_anonymize_data('text', $id);

    $data = array(
      'user_id' => $value
    );
    
    $where = array(
      'user_id' => $id
    );
    $format = array(
      '%s'
    );
    $update = MRWOOO_DB_Logger::anonimyze($data, $where, $format);
    if($update > 0){
      $items_removed = true;
      $done = count( $update ) < $number;
      return array(
        'items_removed' => $items_removed,
        'items_retained' => false,
        'messages' => array(),
        'done' => $done,
      );
    }
  }
  public static function loggerEraser( $erasers ) {
    $erasers['mrwooo-api-core'] = array(
        'eraser_friendly_name' => __( 'MrWooo API Core', 'mrwooo' ),
        'callback' => array('MRWOOO_LIBS_Gdpr_Logger', 'eraser'),
      );
    return $erasers;
  }
}
?>