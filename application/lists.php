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
//               hello@xebura.com
//============================================================+ 
/* This tells not to include the sidemenu page in the includes page. */
$hide_side_menu = false;

require 'include/includes.php';
$mid = $_SESSION['Member_Id'];
$gid = $_REQUEST['i'];
$smarty->assign('gid',$gid);
$name = $db->select_single_value("XEBURA_EMAIL_GROUP","XE_GROUP_NAME","WHERE XE_GROUP_ID = '".$gid."'");
$smarty->assign('name',$name);

$_SESSION['Nav_Menu']="Home";

/* Code for search. */
foreach($_POST as $key => $value)
{
	global $$key;
	$$key = $value;	
	// print post values JLR DEBUG
	//echo "$$key = $value";
}


//HEADER MESSAGE
$show_msg = $db->select_single_value("xebura_MEMBERS","MSG_HOME","WHERE MID='".$mid."' ");
$smarty->assign("show_msg",$show_msg);
$firstname = $_SESSION['First_Name'];
$smarty->assign("firstname",$firstname);

$smarty->display('lists.tpl');
$smarty->display('search_footer.tpl');
?>
