<?php
/**************************/
/* PHP by gonchuki - 2008 */
/**************************/

/**
 * smarty plugin to adjust the size of text on large strings
 *
 * @package Smarty
 * @subpackage plugins
 */
function smarty_modifier_adjustsize($string) {
  $len = strlen($string);
  
  if ($len < 20) {
    $classname = '';
  } elseif ($len < 24) {
    $classname = "smaller_1";
  } elseif ($len < 30) {
    $classname = "smaller_2";
  } elseif ($len < 36) {
    $classname = "smaller_3";
  } else {
    $classname = "smaller_4";
  }
  
  return ('<span class="'.$classname.'">'.$string.'</span>');
}
?>