<?PHP
ob_start();
session_start();
header("Cache-control:no-cache");
require_once("language/lang.php");
include 'include/config.php';
require_once("class/dbclass.php");
require 'include/Smarty.class.php';
require 'include/variable.php';
require 'include/imagefunction.php';
require 'common_functions.php';
$db = new mysqldb();
$smarty = new Smarty;
$smarty->assign("topmenu",$topmenu);
$smarty->assign("topmenu_opt",$topmenu_opt);
?>