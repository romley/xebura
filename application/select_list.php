<?PHP
//============================================================+
// Version     : 1.0
// License     : GNU GPL (http://www.gnu.org/licenses/gpl-3.0.html)
// 	----------------------------------------------------------------------------
//  Copyright (C) 2010-2012 Jonathan Romley - Xebura Corporation
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
// Description : PHP-based multitenant marketing automation software
//               using Amazon Simple Email Service and Twilio
//
// Author: Jonathan Romley
//
// (c) Copyright:
//               Jonathan Romley
//               Xebura Corporation
//               256 South Robertson Blvd
//               Beverly Hills, CA 90211
//               USA
//               www.xebura.com
//               j@xebura.com
//============================================================+ 
/* This tells not to include the sidemenu page in the includes page. */
$hide_side_menu = false;

require 'include/includes.php';
$mid = $_SESSION['Member_Id'];
$campaign_id = $_REQUEST['i'];


$_SESSION['Nav_Menu']="Home";

/* Code for search. */
foreach($_POST as $key => $value)
{
	global $$key;
	$$key = $value;	
	// print post values JLR DEBUG
	//echo "$$key = $value";
}



if($action=='select'){

// CREATE THE CAMPAIGN
$date = date('Y-m-d G:i:s',(strtotime("now")));
$launch_time = $launchdate.' '.$launchtime;
$launch_schedule = date('Y-m-d G:i:s',(strtotime("$launch_time")));
$listvalues = implode(",",$select);
$values = array(XE_CAMPAIGN_LIST_ID => $listvalues);
$db->update("XEBURA_CAMPAIGN",$values,"WHERE XE_CAMPAIGN_ID = '".$campaign_id."'");


// this would deal with a campaign list table, TODO
//foreach($select as $gid) {
     
	 // GET ALL EMAILS FROM THAT GROUP ID AND INSERT IN TO CAMPAIGN LIST ID TABLE
	 
	 //SELECT 
	 
	// FROM XEBURA_MASTER_LIST
//	 WHERE XE_ADDRESS_GROUP_ID = $gid
	 
	 
	 
//}



//print_r($select);

//echo $listvalues;

header("Location:schedule_campaign?i=".$campaign_id);
exit;
	}
	
	
	
	


//HEADER MESSAGE
$show_msg = $db->select_single_value("xebura_MEMBERS","MSG_HOME","WHERE MID='".$mid."' ");
$smarty->assign("show_msg",$show_msg);
$firstname = $_SESSION['First_Name'];
$smarty->assign("firstname",$firstname);

$smarty->display('select_list.tpl');
$smarty->display('search_footer.tpl');
?>
