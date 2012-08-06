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
error_reporting (E_ALL ^ E_NOTICE);
// This is a PHP demo for collecting messaging jobs from XEBURA and sending them out via SES
require 'class/dbclass.php';
include_once 'include/config.php';
require 'include/ses.php';
function encode($originalStr)
{
  $encodedStr = $originalStr;
  $num = mt_rand(1,6);
  for($i=1;$i<=$num;$i++)
  {
  	$encodedStr = base64_encode($encodedStr);
  }
  $seed_array = array('A','R','T','I','S','F','O','C','E');
 
  $encodedStr = $encodedStr . "+" . $seed_array[$num];
  $encodedStr = base64_encode($encodedStr);
  return $encodedStr;
}
 


date_default_timezone_set('America/Los_Angeles');

$db = new mysqldb();

// First lets check for any jobs which are a) Schedule for any time NOW or before AND b) Status is PENDING (0)	
// Now we need member id of campaign...  later we'll need list id... we can probably do this as part of the same query with a join
// while we're at it, let's get the message details?

$now = date('Y-m-d G:i:s',(strtotime("now")));

$sql = "SELECT 
XJ.XE_JOB_ID AS JID,
XJ.XE_JOB_CAMPAIGN_ID AS CID,
XC.XE_CAMPAIGN_MID AS MID,
XC.XE_CAMPAIGN_LIST_ID AS GID,
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

// now that we've got the campaigns, we need to loop over them.. 

$db->query($sql);
$result = $db->query($sql);

$total_items = $db->getNumRows($result);	
	if($db->getNumRows($result)>0)
{
  while ( list ($jid,$cid,$mid,$gid,$from_label,$from_email,$subject,$replyto,$unsubscribe,$address,$msg_txt,$msg_html) = $db->fetchQueryRow($result) )
  {

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

// START URL REPLACEMENT PART 1
//extract all a tag href= urls to an array
$var = $msg_html;
            
    preg_match_all ("/a[\s]+[^>]*?href[\s]?=[\s\"\']+".
                    "(.*?)[\"\']+.*?>"."([^<]+|.*?)?<\/a>/", 
                    $var, &$matches);
        
    $matches = $matches[1];
	$list = array();
	$newlinks=array();
	
//loop over array to insert in to redirect table and use regex to replace URLs
    foreach($matches as $var)
    {    
     
		// insert var to db and get id number
		$values = array(XE_LINK_CAMPAIGN_ID => $cid, XE_LINK_MID => $mid, XE_LINK_URL => $var, XE_LINK_ADDED => $now);
		$urlid = $db->insert("XEBURA_LINK",$values);
		$rlink = "http://s1.xebura.com/link?l=".$urlid;
		
		// let's store these all in an array we can call later
							$tem[] = $rlink;								  
		$newlinks=$tem;
		
    }
// END URL REPLACEMENT PART 1

//need to modify now to handle pulling addresses from the groups

// First we got the comma separated values from XE_CAMPAIGN_LIST_ID in the top query
// Now we'll construct a sql query that includes the list

$sql = "SELECT 
XE_ADDRESS_ID,
XE_ADDRESS_EMAIL,
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
  while ( list ($eid,$email,$fname,$lname) = $db->fetchQueryRow($result) )
  {

//SEND THE EMAIL

$html = "<html>
	<head>
		<title></title>
	</head>
	<body>";

// NOW LET'S ADD EMAIL ID TO URLS
//  START URL REPLACEMENT PART 2 - APPEND EMAIL IDS WITHOUT DB QUERY

//loop over array to append the Email ID
    foreach($newlinks as $var)
    {    

		$rlink = $var."&i=".$eid;
		//echo $rlink;
		
		// let's put these all in to an array
			$etem[] = $rlink;								  
		$elinks=$etem;
		//print_r($elinks);
    }
	$pre_html = str_replace($matches,$elinks,$msg_html);
	$final_html = str_replace('{FNAME}',$fname,$pre_html);
// END URL REPLACEMENT PART 2
	
$html.=$final_html;
unset($etem);

$encoded_cid = encode($cid);
$html.='
<p>
		<a href="http://s1.xebura.com/browser?c='.$encoded_cid.'">Click here to view this message in your browser.</a></p>
<hr />
<table border="0" cellpadding="1" cellspacing="1" style="width: 100%; ">
	<tbody>
		<tr>
			<td style="text-align: left; vertical-align: top; width: 75%; ">
				<span style="font-size:10px;"><span style="font-family: arial, helvetica, sans-serif; ">'.$unsubscribe.'&nbsp;You can&nbsp;<a href="http://s1.xebura.com/unsubscribe?i='.$eid.'&s='.$cid.'">unsubscribe at any time by clicking this link</a></span></span>
				<p>
					<span style="font-size:10px;"><strong><span style="font-family: arial, helvetica, sans-serif; ">'.$address.'</span></strong></span></p>
			</td>
			<td style="text-align: center; vertical-align: top; ">
				<strong><span style="font-size: 12px; color: rgb(0, 100, 0);"><span style="font-family: arial, helvetica, sans-serif; "><a href="http://www.xebura.com"><img src="http://s1.xebura.com/logo.gif?i='.$eid.'&s='.$cid.'" border="0"/></a><br />
				Sent with Xebura</span></span></strong></td>
		</tr>
	</tbody>
</table>
</body>
</html>';


$final_subject = str_replace('{LNAME}',$lname,$subject);
$m = new SimpleEmailServiceMessage(); 


$m->addTo($email); 
$m->setFrom($from_label." <".$from_email.">");
//$m->setReturnPath($eid."_".$cid."_bounce@s1.xebura.com");
//$m->setReturnPath('jromley@gmail.com');
$m->setSubject($final_subject); 
$m->setMessageFromString('text'.$msg_txt,$html);

$ses->sendEmail($m); 
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

echo 'Xebura sent all messages successfully';


?>