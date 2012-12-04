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
// this script tracks email opens
// we should use some htaccess magic so that we can rewrite a url like xebuura.com/images/123campaignId/123emailId/logo.jpg
echo file_get_contents('x.gif');

require 'class/dbclass.php';
include_once 'include/config.php';
date_default_timezone_set('America/Los_Angeles');
$db = new mysqldb();
$now = date('Y-m-d G:i:s',(strtotime("now")));
$ip=$_SERVER['REMOTE_ADDR'];

$eid = $_REQUEST['i'];
$cid = $_REQUEST['s'];
$mid = $db->select_single_value("XEBURA_CAMPAIGN","XE_CAMPAIGN_MID","WHERE XE_CAMPAIGN_ID = '".$cid."'");


// we should only count the initial open ONCE, after that it's just a view

$sql = "SELECT * FROM XEBURA_STATISTICS WHERE XE_STAT_CAMPAIGN_ID = '".$cid."' AND XE_STAT_EMAIL_ID = '".$eid."' AND XE_STAT_TYPE = '1'";
$db->query($sql);
$result = $db->query($sql);

if($db->getNumRows($result)>0)
{
$values = array(XE_STAT_CAMPAIGN_ID => $cid, XE_STAT_MID => $mid, XE_STAT_EMAIL_ID => $eid, XE_STAT_TYPE => '7', XE_STAT_IP => $ip, XE_STAT_TIMESTAMP => $now);
$stat = $db->insert("XEBURA_STATISTICS",$values);
//echo 'update - view';
}else{
// log the event to the statistics table 
$values = array(XE_STAT_CAMPAIGN_ID => $cid, XE_STAT_MID => $mid, XE_STAT_EMAIL_ID => $eid, XE_STAT_TYPE => '1', XE_STAT_IP => $ip, XE_STAT_TIMESTAMP => $now);
$stat = $db->insert("XEBURA_STATISTICS",$values);
//echo 'update - open';
}



?>