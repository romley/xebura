<?php
/**************************/
/* PHP by gonchuki - 2008 */
/**************************/

/**
 * smarty plugin to easily create html anchors with optional parameters
 *
 * @package Smarty
 * @subpackage plugins
 */
function smarty_function_link_to($params, &$smarty) {
  require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');
  
  $url_base = '';
  $url_params = array();
  $params_str = '';
  
  foreach($params as $_key => $_val) {
    switch($_key) {
        case 'url':
          $url_base = $_val;
          break;
        default:
          $url_params[] = strval($_key) . "=" . $_val;
          break;
    }
  }
  
  if (sizeof($url_params)) {
    $params_str = "?" . implode('&amp;', $url_params);
  }
  
  return ($url_base . $params_str);
}
?>