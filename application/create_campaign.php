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

$_SESSION['Nav_Menu']="Home";

$campaign_type = $_REQUEST['t'];

    // Include the PHP TwilioRest library 
    require "twilio.php";
    
    // Twilio REST API version 
    $ApiVersion = "2010-04-01";


/* Code for search. */
foreach($_POST as $key => $value)
{
	global $$key;
	$$key = $value;	
	// print post values JLR DEBUG
	//echo "$$key = $value";
}



foreach($approved_list as $email) {
     //  echo $email;
		$tem['email'] = $email;									  
		$ok_emails[$isa++]=$tem;
}

$smarty->assign("ok_emails", $ok_emails);


	if($action=='create'){
// Let's create some records.
// CREATE THE CAMPAIGN
$date = date('Y-m-d G:i:s',(strtotime("now")));
$values = array(XE_CAMPAIGN_MID => $mid, XE_CAMPAIGN_NAME => $name, XE_CAMPAIGN_ADDED_DATE => $date, XE_CAMPAIGN_MODIFIED_DATE => $date);
$cid = $db->insert("XEBURA_CAMPAIGN",$values);

// NOW CREATE THE MESSAGE 
$values = array(XE_MSG_CAMPAIGN_ID => $cid, XE_MSG_FROM_LABEL => $from, XE_MSG_FROM_EMAIL => $from_email, XE_MSG_SUBJECT => $subject, XE_MSG_REPLY_TO => $replyto, XE_MSG_UNSUBSCRIBE => $unsubscribe, XE_MSG_POSTAL_ADDRESS => $address, XE_MSG_MODIFIED_DATE => $date);
$msgid = $db->insert("XEBURA_MESSAGE",$values);
header("Location:campaigns");
exit;
	}
	elseif($action=='update'){
		$date = date('Y-m-d G:i:s',(strtotime("now")));
$values = array(XE_CAMPAIGN_MID => $mid, XE_CAMPAIGN_NAME => $name, XE_CAMPAIGN_ADDED_DATE => $date, XE_CAMPAIGN_MODIFIED_DATE => $date);
$cid = $db->update("XEBURA_CAMPAIGN",$values,"WHERE XE_CAMPAIGN_ID = '".$cid."'");

// NOW CREATE THE MESSAGE 
$values = array(XE_MSG_CAMPAIGN_ID => $cid, XE_MSG_FROM_LABEL => $from, XE_MSG_FROM_EMAIL => $from_email, XE_MSG_SUBJECT => $subject, XE_MSG_REPLY_TO => $replyto, XE_MSG_UNSUBSCRIBE => $unsubscribe, XE_MSG_POSTAL_ADDRESS => $address, XE_MSG_MODIFIED_DATE => $date);
$db->update("XEBURA_MESSAGE",$values,"WHERE XE_MSG_ID = '".$msgid."'");
//header("Location:campaigns");
//exit;
		}else{
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

		}


// get the TWILIO credentials for the campaign creator
$res = $db->query("SELECT
XE_TWI_ACCOUNT_SID AS ACCOUNT_SID,
XE_TWI_AUTH_TOKEN AS AUTH_TOKEN
FROM XEBURA_TWILIO_CREDENTIALS
WHERE XE_TWI_MID = '".$mid."'");
	$row = $db->fetchQueryArray($res);
	$AccountSid = $row['ACCOUNT_SID'];
	$AuthToken = $row['AUTH_TOKEN'];

	// Instantiate a new Twilio Rest Client 
    $client = new TwilioRestClient($AccountSid, $AuthToken);

    $response = $client->request("/$ApiVersion/Accounts/$AccountSid/OutgoingCallerIds", 
       "GET", $data); 

//print_r($response);

function convertXmlObjToArr($obj, &$arr) 
{ 
    $children = $obj->children(); 
    foreach ($children as $elementName => $node) 
    { 
        $nextIdx = count($arr); 
        //$arr[$nextIdx] = array(); 
        //$arr[$nextIdx]['@name'] = strtolower((string)$elementName); 
        //$arr[$nextIdx]['@attributes'] = array(); 
        $attributes = $node->attributes(); 
        foreach ($attributes as $attributeName => $attributeValue) 
        { 
            $attribName = strtolower(trim((string)$attributeName)); 
            $attribVal = trim((string)$attributeValue); 
           // $arr[$nextIdx]['@attributes'][$attribName] = $attribVal; 
        } 
        $text = (string)$node; 
        $text = trim($text); 
        if (strlen($text) > 0) 
        { 
            $arr[$elementName] = $text; 
        } 
       // $arr[$nextIdx] = array(); 
        convertXmlObjToArr($node, $arr[$nextIdx]); 
    } 
    return; 
}  
convertXmlObjToArr($response->ResponseXml->OutgoingCallerIds,$ok_phone);

print_r($ok_phone);


$ok_phone['PhoneNumber'];
$ok_phone['FriendlyName'];


		

//HEADER MESSAGE
$show_msg = $db->select_single_value("xebura_MEMBERS","MSG_HOME","WHERE MID='".$mid."' ");
$smarty->assign("show_msg",$show_msg);
$firstname = $_SESSION['First_Name'];
$smarty->assign("firstname",$firstname);

$smarty->display('create_campaign.tpl');
$smarty->display('search_footer.tpl');
?>
