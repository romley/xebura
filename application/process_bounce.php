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
require 'class/dbclass.php';
include_once 'include/config.php';
require_once("bounce_driver.class.php");
date_default_timezone_set('America/Los_Angeles');
$db = new mysqldb();
$now = date('Y-m-d G:i:s',(strtotime("now")));
$ip=$_SERVER['REMOTE_ADDR'];


$bouncehandler = new Bouncehandler();

// read the bounced email
// read from stdin
$fd = fopen("php://stdin", "r");
$email = "";
while (!feof($fd))
{
	$email .= fread($fd, 1024);
}
fclose($fd);


// the bounced message is the email we piped in
$bounce = $email;
//$bounce = file_get_contents("eml/1.eml");

// for testing purposes we'll use a static email file 
//$bounce = file_get_contents("eml/1.eml");
$bouncehandler->x_header_search_1 = "X-Xe-ID";
//$xe_data = $bouncehandler->x_header_beacon_1;
//echo $xe_data;
$multiArray = $bouncehandler->get_the_facts($bounce);
//print_r($multiArray);

//send us the email to make sure it worked
//mail('jromley@gmail.com','someone sent us an email at bounce@everient.com',"Here is the the full email:\n\n$bounce");


// we are lookin for the Xebura X header data
//$bouncehandler->x_header_search_1 = "X-Xe-ID";

//Format is like X-Xe-ID: campaignID-emailID



// get the data from the array
$multiArray = $bouncehandler->get_the_facts($bounce);

    foreach($multiArray as $the){
        switch($the['action']){
            case 'failed':
                //do something

				// more specifically, mark the email address as bounced on the campaign, then mark the email as bounced.
				$xe_data = $bouncehandler->x_header_beacon_1;
				//echo $xe_data;
				$xe_array = explode('-',$xe_data);
				//Format is campaignID-emailID
				//print_r($xe_array);
				$xe_campaign_id = $xe_array[0];
				$xe_email_id = $xe_array[1];
				$mid = $db->select_single_value("XEBURA_CAMPAIGN","XE_CAMPAIGN_MID","WHERE XE_CAMPAIGN_ID = '".$xe_campaign_id."'");
				$values = array(XE_STAT_CAMPAIGN_ID => $xe_campaign_id, XE_STAT_MID => $mid, XE_STAT_EMAIL_ID => $xe_email_id, XE_STAT_TYPE => '3', XE_STAT_IP => $ip, XE_STAT_TIMESTAMP => $now);
$stat = $db->insert("XEBURA_STATISTICS",$values);

				$fp = fopen('bounce-log.txt', 'w');
				fwrite($fp, 'Campaign Id: '.$xe_campaign_id.' -- Email Id: '.$xe_email_id);
				fclose($fp);
				//echo 'Campaign Id: '.$xe_campaign_id.'<br>';
				//echo 'Email Id: '.$xe_email_id;
				//echo 'failed-response';
				//exit;
mail('jromley@gmail.com','bounce failure notice',"XE Data:\n\n$xe_data \n\n Here is the the full email:\n\n$bounce");

				break;
            case 'transient':
                //do something else
                $num_attempts  = delivery_attempts($the['recipient']);
                if($num_attempts  > 10){
                    //kill_him($the['recipient']);
                }
                else{
                  //  insert_into_queue($the['recipient'], ($num_attempts+1));
                }
                break;
            case 'autoreply':
                //do something different
               // postpone($the['recipient'], '7 days');
                break;
            default:
                //don't do anything
                break;
        }
    }

//list($head, $body) = preg_split("/\r\n\r\n/", $bounce, 2);
//echo $body;
//$mime_sections = $bouncehandler->parse_body_into_mime_sections($body, $boundary);
//$rpt_hash = $bouncehandler->parse_machine_parsable_body_part($mime_sections['machine_parsable_body_part']);
//
//$head = $bouncehandler->get_head_from_returned_message_body_part($mime_sections);	
//$head_hash = $bouncehandler->parse_head($head);
//print_r($head_hash);
exit;
?>
