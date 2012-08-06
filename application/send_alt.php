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
// This is a PHP demo for collecting messaging jobs from XEBURA and sending them out via SES
require 'class/dbclass.php';
include_once 'include/config.php';
require 'include/ses.php';

date_default_timezone_set('America/Los_Angeles');

$db = new mysqldb();

//this should be dynamic...
$ses = new SimpleEmailService('AKIAIQQE2DZLPIWS26QQ', 'j40HKPqSSs/5HDD3gKqLovDwnxbdF0aW113pQIie'); 


// First lets check for any jobs which are a) Schedule for any time NOW or before AND b) Status is PENDING (0)	
// Now we need member id of campaign...  later we'll need list id... we can probably do this as part of the same query with a join
// while we're at it, let's get the message details?

$now = date('Y-m-d G:i:s',(strtotime("now")));

$sql = "SELECT 
XJ.XE_JOB_ID AS JID,
XJ.XE_JOB_CAMPAIGN_ID AS CID,
XC.XE_CAMPAIGN_MID AS MID,
XM.XE_MSG_FROM_LABEL AS FROM_LABEL,
XM.XE_MSG_FROM_EMAIL AS FROM_EMAIL,
XM.XE_MSG_SUBJECT AS SUBJECT,
XM.XE_MSG_REPLY_TO AS REPLYTO,
XM.XE_MSG_UNSUBSCRIBE AS UNSUBSCRIBE,
XM.XE_MSG_POSTAL_ADDRESS AS ADDRESS,
XM.XE_MSG_TEMPLATE_TEXT AS MSG_TXT,
XM.XE_MSG_TEMPLATE_HTML AS MSG_HTML
FROM XEBURA_JOBS AS XJ
JOIN XEBURA_CAMPAIGN AS XC ON XJ.XE_JOB_CAMPAIGN_ID = XC.XE_CAMPAIGN_ID
JOIN XEBURA_MESSAGE  AS XM ON XJ.XE_JOB_CAMPAIGN_ID = XM.XE_MSG_CAMPAIGN_ID
WHERE XE_JOB_LAUNCH <= '".$now."'
AND XE_JOB_STATUS = '0'";//job status 0 = pending

// now that we've got the campaigns, we need to loop over them.. this is our outer loop

$db->query($sql);
$result = $db->query($sql);

$total_items = $db->getNumRows($result);	
	if($db->getNumRows($result)>0)
{
  while ( list ($jid,$cid,$mid,$from_label,$from_email,$subject,$replyto,$unsubscribe,$address,$msg_txt,$msg_html) = $db->fetchQueryRow($result) )
  {

// now for our inner loop, let's find out who we're supposed to send this campaign out to, and loop over them.
// need to add check for sending allowed status to this query later
// remove LIMIT

// update campaign as launched
$values = array(XE_CAMPAIGN_STATUS => '2'); // status 2 = launched
$db->update("XEBURA_CAMPAIGN",$values,"WHERE XE_CAMPAIGN_ID = '".$cid."'");
////update job as running
$start_time = date('Y-m-d G:i:s',(strtotime("now")));
$values = array(XE_JOB_STATUS => '2', XE_JOB_STARTED_TIME => $start_time); //status 2 = running
$db->update("XEBURA_JOBS",$values,"WHERE XE_JOB_ID = '".$jid."'");

$sql = "SELECT 
XE_ADDRESS_ID,
XE_ADDRESS_EMAIL,
XE_ADDRESS_FNAME
FROM XEBURA_MASTER_LIST
WHERE XE_ADDRESS_MID = '".$mid."'
LIMIT 5";

$db->query($sql);
$result = $db->query($sql);

$total_items = $db->getNumRows($result);	
	if($db->getNumRows($result)>0)
{
  while ( list ($eid,$email,$fname) = $db->fetchQueryRow($result) )
  {

//SEND THE EMAIL

$m = new SimpleEmailServiceMessage(); 

$m->addTo($email); 
$m->setFrom($from_email); 
$m->setSubject($subject); 
$m->setMessageFromString('text'.$msg_txt,$msg_html);

print_r($ses->sendEmail($m)); 


  }
}

////update job as completed
$end_time = date('Y-m-d G:i:s',(strtotime("now")));
//// update message job as completed
$values = array(XE_JOB_STATUS => '5', XE_JOB_ENDED_TIME => $end_time);//status 5 = finished
$db->update("XEBURA_JOBS",$values,"WHERE XE_JOB_ID = '".$jid."'");


 }
}




?>