<?php
/**************************/
/* PHP by gonchuki - 2008 */
/**************************/

/**
 * smarty plugin to prepend http:// to a must-be external url, while sanitizing bad protocols
 *
 * @package Smarty
 * @subpackage plugins
 */
function smarty_modifier_sanitizeurl($string, $prepend = true) {
  $sanitized_url = preg_replace('/^[a-z]+:\/\//i', '', strtolower($string));
  return ($prepend ? 'http://' : '') . $sanitized_url;
}
?>