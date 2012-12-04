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
		$date = date('Y-m-d G:i:s',(strtotime("now")));
$values = array(XE_CAMPAIGN_MID => $mid, XE_CAMPAIGN_NAME => $name, XE_CAMPAIGN_ADDED_DATE => $date, XE_CAMPAIGN_MODIFIED_DATE => $date);
$cid = $db->update("XEBURA_CAMPAIGN",$values,"WHERE XE_CAMPAIGN_ID = '".$campaign_id."'");

// NOW CREATE THE MESSAGE 
$values = array(XE_MSG_FROM_LABEL => $from, XE_MSG_FROM_EMAIL => $from_email, XE_MSG_SUBJECT => $subject, XE_MSG_REPLY_TO => $replyto, XE_MSG_UNSUBSCRIBE => $unsubscribe, XE_MSG_POSTAL_ADDRESS => $address, XE_MSG_MODIFIED_DATE => $date);
$db->update("XEBURA_MESSAGE",$values,"WHERE XE_MSG_ID = '".$msgid."'");
header("Location:create_message?i=".$campaign_id);
exit;
		}


$sql = "SELECT
XC.XE_CAMPAIGN_ID AS CID,
XM.XE_MSG_ID AS MSGID,
XC.XE_CAMPAIGN_NAME AS NAME,
XM.XE_MSG_FROM_LABEL AS FROM_LABEL,
XE_MSG_FROM_EMAIL AS FROM_EMAIL,
XE_MSG_SUBJECT AS SUBJECT,
XE_MSG_REPLY_TO AS REPLYTO,
XE_MSG_UNSUBSCRIBE AS UNSUB,
XE_MSG_POSTAL_ADDRESS AS ADDRESS
FROM XEBURA_CAMPAIGN AS XC
JOIN XEBURA_MESSAGE AS XM ON XC.XE_CAMPAIGN_ID = XM.XE_MSG_CAMPAIGN_ID
WHERE XC.XE_CAMPAIGN_ID = '".$campaign_id."'";

//echo $sql;
	
$db->query($sql);
$result = $db->query($sql);

$total_items = $db->getNumRows($result);	
	if($db->getNumRows($result)>0)
{
  while ( list ($cid,$msgid,$name,$from_label,$from_email,$subject,$replyto,$unsub,$address) = $db->fetchQueryRow($result) )
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
	$smarty->assign('replyto',$replyto);
	$smarty->assign('unsub',$unsub);
	$smarty->assign('address',$address);

}
}



// get the amazon credentials for the campaign creator
$res = $db->query("SELECT
XE_AMZ_ACCESS_KEY AS ACCESS_KEY,
XE_AMZ_SECRET_KEY AS SECRET_KEY
FROM XEBURA_AMAZON_CREDENTIALS
WHERE XE_AMZ_MID = '".$mid."'");
	$row = $db->fetchQueryArray($res);
	$amz_akey = $row['ACCESS_KEY'];
	$amz_skey = $row['SECRET_KEY'];

// intialize ses class
$ses = new SimpleEmailService($amz_akey, $amz_skey); 



$approved_senders = $ses->listVerifiedEmailAddresses();

$approved_list = $approved_senders['Addresses'];

$ok_emails=array();
$isa=0;

foreach($approved_list as $email) {
     //  echo $email;
		$tem['email'] = $email;									  
		$ok_emails[$isa++]=$tem;
}

$smarty->assign("ok_emails", $ok_emails);

$action = 'update';
$smarty->assign('action',$action);

//HEADER MESSAGE
$show_msg = $db->select_single_value("xebura_MEMBERS","MSG_HOME","WHERE MID='".$mid."' ");
$smarty->assign("show_msg",$show_msg);
$firstname = $_SESSION['First_Name'];
$smarty->assign("firstname",$firstname);

$smarty->display('create_campaign.tpl');
$smarty->display('search_footer.tpl');
?>
