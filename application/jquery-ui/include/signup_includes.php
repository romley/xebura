<?PHP
ob_start();
session_start();
header("Cache-control:no-cache");
include 'config.php';
require_once("class/dbclass.php");
require 'Smarty.class.php';
require 'variable.php';
require 'imagefunction.php';
require 'common_functions.php';
$db = new mysqldb();
$smarty = new Smarty;

$smarty->assign("topmenu",$topmenu);
$smarty->assign("topmenu_opt",$topmenu_opt);
?>