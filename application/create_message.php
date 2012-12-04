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
/* This tells not to include the sidemenu page in the includes page. */
$hide_side_menu = false;

require 'include/includes.php';
$campaign_id = $_REQUEST['i'];
$mid = $_SESSION['Member_Id'];

/* Code for search. */
foreach($_POST as $key => $value)
{
	global $$key;
	$$key = $value;	
	// print post values JLR DEBUG
	//echo "$$key = $value";
}

if($action=='update'){
	
// NOW CREATE THE MESSAGE 
$values = array(XE_MSG_TEMPLATE_HTML => $html_template);
$db->update("XEBURA_MESSAGE",$values,"WHERE XE_MSG_ID = '".$msgid."'");
//header("Location:create_message?i=".$campaign_id);
//exit;
		}



$sql = "SELECT
XC.XE_CAMPAIGN_ID AS CID,
XM.XE_MSG_ID AS MSGID,
XC.XE_CAMPAIGN_NAME AS NAME,
XM.XE_MSG_FROM_LABEL AS FROM_LABEL,
XM.XE_MSG_FROM_EMAIL AS FROM_EMAIL,
XM.XE_MSG_SUBJECT AS SUBJECT,
XM.XE_MSG_REPLY_TO AS REPLYTO,
XM.XE_MSG_UNSUBSCRIBE AS UNSUB,
XM.XE_MSG_POSTAL_ADDRESS AS ADDRESS,
XM.XE_MSG_TEMPLATE_HTML AS HTML_MSG
FROM XEBURA_CAMPAIGN AS XC
JOIN XEBURA_MESSAGE AS XM ON XC.XE_CAMPAIGN_ID = XM.XE_MSG_CAMPAIGN_ID
WHERE XC.XE_CAMPAIGN_ID = '".$campaign_id."'";
//echo $sql;
	
$db->query($sql);
$result = $db->query($sql);

$total_items = $db->getNumRows($result);	
	if($db->getNumRows($result)>0)
{
  while ( list ($cid,$msgid,$name,$from_label,$from_email,$subject,$replyto,$unsub,$address,$html_msg) = $db->fetchQueryRow($result) )
  {

	$date_clean = date('M d, Y',(strtotime($date)));

	$smarty->assign('cid',$cid);
	//echo $cid;
	$smarty->assign('msgid',$msgid);
	$smarty->assign('name',$name);
	//echo $name;
	$smarty->assign('from_label',$from_label);
	$smarty->assign('from_email',$from_email);
	$smarty->assign('subject',$subject);
		$smarty->assign('html_msg',$html_msg);
	$smarty->assign('replyto',$replyto);
	$smarty->assign('unsub',$unsub);
	$smarty->assign('address',$address);

}
}



$action = 'update';
$smarty->assign('action',$action);

//HEADER MESSAGE
$show_msg = $db->select_single_value("xebura_MEMBERS","MSG_HOME","WHERE MID='".$mid."' ");
$smarty->assign("show_msg",$show_msg);
$firstname = $_SESSION['First_Name'];
$smarty->assign("firstname",$firstname);

$smarty->display('create_message.tpl');
$smarty->display('search_footer.tpl');
?>
