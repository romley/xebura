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
// This script unsubscribes ppl.... 
// it needs a UI

require 'class/dbclass.php';
include_once 'include/config.php';
require 'include/ses.php';
date_default_timezone_set('America/Los_Angeles');
$db = new mysqldb();
$now = date('Y-m-d G:i:s',(strtotime("now")));
$ip=$_SERVER['REMOTE_ADDR'];

$eid = $_REQUEST['i'];
$cid = $_REQUEST['s'];
$mid = $db->select_single_value("XEBURA_CAMPAIGN","XE_CAMPAIGN_MID","WHERE XE_CAMPAIGN_ID = '".$cid."'");


// before we try to unsub the email id, let's check if it is already unsubscribed

$sql = "SELECT * FROM XEBURA_MASTER_LIST WHERE XE_ADDRESS_ID = '".$eid."' AND XE_ADDRESS_OPT_STATUS = '2'";
$db->query($sql);
$result = $db->query($sql);

if($db->getNumRows($result)>0)
{
echo "You are already unsubscribed from this list. If you're still receiving emails from this sender, please contact our consumer relations team at abuse@xebura.com";
}else{
// unsubscribe the email address from the master list
$values = array(XE_ADDRESS_OPT_STATUS => '2');
$db->update("XEBURA_MASTER_LIST",$values,"WHERE XE_ADDRESS_ID = '".$eid."'");
//echo "Step 1 - Unsubscribe from Master List - DONE  |";
// log the event to the statistics table 
$values = array(XE_STAT_CAMPAIGN_ID => $cid, XE_STAT_MID => $mid, XE_STAT_EMAIL_ID => $eid, XE_STAT_TYPE => '4', XE_STAT_IP => $ip, XE_STAT_TIMESTAMP => $now);
$stat = $db->insert("XEBURA_STATISTICS",$values);
echo "You have been unsubscribed from this list successfully and will no longer receive email from this sender.";
}


?>