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
error_reporting (E_ALL ^ E_NOTICE);
// This is a PHP demo for collecting SMS jobs from XEBURA and sending them out via TWILIO
require 'class/dbclass.php';
include_once 'include/config.php';

    // Include the PHP TwilioRest library 
    require "twilio.php";
    
    // Twilio REST API version 
    $ApiVersion = "2010-04-01";

date_default_timezone_set('America/Los_Angeles');

$db = new mysqldb();

// First lets check for any jobs which are a) Schedule for any time NOW or before AND b) Status is PENDING (0) AND Job Type is SMS	

$now = date('Y-m-d G:i:s',(strtotime("now")));

$sql = "SELECT 
XJ.XE_JOB_ID AS JID,
XJ.XE_JOB_CAMPAIGN_ID AS CID,
XC.XE_CAMPAIGN_MID AS MID,
XC.XE_CAMPAIGN_LIST_ID AS GID,
XM.XE_MSG_FROM_PHONE AS FROM_PHONE,
XM.XE_MSG_TEMPLATE_TEXT AS MSG_TXT,
FROM XEBURA_JOBS AS XJ
JOIN XEBURA_CAMPAIGN AS XC ON XJ.XE_JOB_CAMPAIGN_ID = XC.XE_CAMPAIGN_ID
JOIN XEBURA_MESSAGE  AS XM ON XJ.XE_JOB_CAMPAIGN_ID = XM.XE_MSG_CAMPAIGN_ID
WHERE XE_JOB_LAUNCH <= '".$now."'
AND XE_JOB_TYPE = '1'
AND XE_JOB_STATUS = '0'";//job status 0 = pending

// now that we've got the campaigns, we need to loop over them.. 

$db->query($sql);
$result = $db->query($sql);

$total_items = $db->getNumRows($result);	
	if($db->getNumRows($result)>0)
{
  while ( list ($jid,$cid,$mid,$gid,$from_phone,$msg_txt) = $db->fetchQueryRow($result) )
  {

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

    // Outgoing Caller ID you have previously validated with Twilio 
	
	//$CallerID = $from_phone;
    
	// THIS IS STUCK AS TWILIO 415 CALLER ID IN SANDBOX
	$CallerID = '415-599-2671';

	$sms_message = $msg_txt.' *Reply STOP to unsubscribe';
// now for our inner loop, let's find out who we're supposed to send this campaign out to, and loop over them.
// need to add check for sending allowed status on email record to this query later
// remove LIMIT

// update campaign as launched
$values = array(XE_CAMPAIGN_STATUS => '2'); // status 2 = launched
$db->update("XEBURA_CAMPAIGN",$values,"WHERE XE_CAMPAIGN_ID = '".$cid."'");
////update job as running
$start_time = date('Y-m-d G:i:s',(strtotime("now")));
$values = array(XE_JOB_STATUS => '2', XE_JOB_STARTED_TIME => $start_time); //status 2 = running
$db->update("XEBURA_JOBS",$values,"WHERE XE_JOB_ID = '".$jid."'");


// First we got the comma separated values from XE_CAMPAIGN_LIST_ID in the top query
// Now we'll construct a sql query that includes the list

$sql = "SELECT 
XE_ADDRESS_ID,
XE_ADDRESS_PHONE,
XE_ADDRESS_FNAME,
XE_ADDRESS_LNAME
FROM XEBURA_MASTER_LIST
WHERE XE_ADDRESS_MID = '".$mid."'
AND XE_ADDRESS_GROUP_ID IN(".$gid.")
AND XE_ADDRESS_OPT_STATUS IN(0,1)";

$send_count = 0;
$db->query($sql);
$result = $db->query($sql);

$total_items = $db->getNumRows($result);	
	if($db->getNumRows($result)>0)
{
  while ( list ($eid,$phone,$fname,$lname) = $db->fetchQueryRow($result) )
  {

    // ========================================================================
    // 1. Initiate a new outbound SMS
    //    uses a HTTP POST
    $data = array(
    	"From" => $CallerID, 	      // Outgoing Caller ID
    	"To" => $phone,	  // The phone number you wish to SMS
    	"Body" => $sms_message;
    );
    
    $response = $client->request("/$ApiVersion/Accounts/$AccountSid/SMS/Messages", 
       "POST", $data); 
    
//	print_r($response);
    // check response for success or error
    if($response->IsError)
    	echo "Error sending text: {$response->ErrorMessage}\n";
    else
    	echo "Sent text: {$response->ResponseXml->SMSMessage->Sid}\n";
    
$send_count++;

  }
}

////update job as completed
$end_time = date('Y-m-d G:i:s',(strtotime("now")));
//// update message job as completed
$values = array(XE_JOB_STATUS => '5', XE_JOB_SEND_COUNT => $send_count, XE_JOB_ENDED_TIME => $end_time);//status 5 = finished
$db->update("XEBURA_JOBS",$values,"WHERE XE_JOB_ID = '".$jid."'");

//$values = array(XE_GROUP_LAST_LAUNCH_DATE => $end_time);
$db->query("UPDATE XEBURA_EMAIL_GROUP SET XE_GROUP_LAST_LAUNCH_DATE = '".$end_time."' WHERE XE_ADDRESS_GROUP_ID IN(".$gid.")");

 }
}

echo 'Xebura sent all SMS messages successfully';


?>