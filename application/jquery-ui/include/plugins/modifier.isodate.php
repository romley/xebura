<?php
/**************************/
/* PHP by gonchuki - 2008 */
/**************************/

/**
 * smarty plugin to convert standard dates to ISO8601 dates
 *
 * @package Smarty
 * @subpackage plugins
 */
function smarty_modifier_isodate($date, $format = "mdy") {
  $components = explode('/', $date);
  return ($components[2] . $components[0] . $components[1]);
}
?>