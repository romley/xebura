<?php
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
$m->setMessageFromString($msg_html);

print_r($ses->sendEmail($m)); 


  }
}


 }
}




?>