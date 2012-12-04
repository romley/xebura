<?PHP
//============================================================+
// Version     : 1.0
// License     : GNU GPL (http://www.gnu.org/licenses/gpl-3.0.html)
// 	----------------------------------------------------------------------------
//  Copyright (C) 2010-2012  Xebura Corporation
//
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, either version 3 of the License, or
//  (at your option) any later version.
//
// 	This program is distributed in the hope that it will be useful,
// 	but WITHOUT ANY WARRANTY; without even the implied warranty of
// 	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// 	GNU Lesser General Public License for more details.
//
// 	You should have received a copy of the GNU Lesser General Public License
// 	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 	See LICENSE.TXT file for more information.
//  ----------------------------------------------------------------------------
//
// Description : Cloud marketing automation
//               with Amazon Simple Email Service and Twilio
//
// Author: Xebura Corporation
//
// (c) Copyright:
//               Xebura Corporation
//               256 South Robertson Blvd
//               Beverly Hills, CA 90211
//               USA
//               www.xebura.com
//               j@xebura.com
//============================================================+
ob_start();
@session_start();

if($_SESSION['Member_Id']=="") {
	header("Cache-control:no-cache");
	header("location:index.php");
	exit;
}

if($_SERVER['PHP_SELF'] =='/xebura/home.php') {
	include_once 'config.php';
} else {
	include_once 'include/config.php';
}

//echo 'howdy no problems';
date_default_timezone_set('America/Los_Angeles');
require_once 'language/lang.php';
require_once 'class/dbclass.php';
require_once 'class/email.class.php';
require_once 'class/onlineclass.php';
require_once 'Smarty.class.php';
require_once 'variable.php';
require_once 'imagefunction.php';
require_once 'common_functions.php';
require_once 'ses.php';
if(@!$db)
	$db = new mysqldb();
if(@!$onl)
	$onl = new onlinedb;
if(@!$smarty)
	$smarty = new Smarty;
if(@!$mailobj)
	$mailobj = new MyEmail();		

require_once 'language/smarty_assign.php';

if(!isset($hide_side_menu) || ($hide_side_menu == true))
{
	require_once 'sidemenu_details.php';
}
require_once 'validation.php';

$query = "SELECT LOGIN_STATUS, IS_TEMP_PASSWORD FROM xebura_MEMBERS WHERE MID = '".$_SESSION['Member_Id']."'";
$result=$db->query($query);

if($db->getNumRows($result) >0) 
{ 	
	$row = $db->fetchQueryArray($result);
	$smarty->assign("first_time_login",$row['LOGIN_STATUS']);
	
	if($row['IS_TEMP_PASSWORD'] == 1)
	{
		$smarty->assign("temp_pass",1);
	}
	else
	{
		$smarty->assign("temp_pass",0);
	}
	// echo $row['LOGIN_STATUS'].'-->'.$row['IS_TEMP_PASSWORD']; 
	if($row['LOGIN_STATUS'] == 0 || $row['IS_TEMP_PASSWORD'] == 1)
	{
		// Do not show menu
		$show_user_menu = 0;	
		$topmenu_opt = array("Home");
		$topmenu = array(_DASHBOARD);
	}
	else
	{
		// Show menu
		$show_user_menu = 1;
	}
	
	$script_page_name = $_SERVER['SCRIPT_NAME'];
	$last_indx_of_slash = strrpos($script_page_name, "/");
	$url_page_name = substr($script_page_name, $last_indx_of_slash+1, strlen($script_page_name));
	
	if($row['IS_TEMP_PASSWORD'] == 1 && ($url_page_name != 'newchangepassword.php'))
	{		
		$_SESSION['Nav_Submenu'] = 'ChangePassword';		
		header("Location:newchangepassword.php");
		exit;	
	}
		
	$smarty->assign("show_user_menu",$show_user_menu);
}
$encoded_member_id = encode($_SESSION['Member_Id']);

$smarty->assign("topmenu",$topmenu);
$smarty->assign("encoded_member_id",$encoded_member_id);
$smarty->assign("view_profile_link",'view_profile.php?UID='.$encoded_member_id);
$smarty->assign("topmenu_opt",$topmenu_opt);


$where_clause = "WHERE MID = '".$partnermid."'";
$available_credit = $db->select_single_value("xebura_MEMBERS","TI_CREDIT_BALANCE","$where_clause");
$smarty->assign('available_credit',$available_credit);

function ShortenText($text) { 

        // Change to the number of characters you want to display 
		$chars = 40; 
		$charslen = strlen($text);
if($charslen > 40){
        $text = $text." "; 
        $text = substr($text,0,$chars); 
        $text = substr($text,0,strrpos($text,' ')); 
        $text = $text."..."; 
}
        return $text; 

    } 
	
	function LongShortText($text) { 

        // Change to the number of characters you want to display 
		$chars = 55; 
		$charslen = strlen($text);
if($charslen > 55){
        $text = $text." "; 
        $text = substr($text,0,$chars); 
        $text = substr($text,0,strrpos($text,' ')); 
        $text = $text."..."; 
}
        return $text; 

    } 
	
	function ShortReportText($text) { 

        // Change to the number of characters you want to display 
		$chars = 35; 
		$charslen = strlen($text);
if($charslen > 35){
        $text = $text." "; 
        $text = substr($text,0,$chars); 
        $text = substr($text,0,strrpos($text,' ')); 
        $text = $text."..."; 
}
        return $text; 

    } 

?>