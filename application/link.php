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
// this script tracks link clicks and redirects the user to the link

require 'class/dbclass.php';
include_once 'include/config.php';
date_default_timezone_set('America/Los_Angeles');
$db = new mysqldb();
$now = date('Y-m-d G:i:s',(strtotime("now")));
$ip=$_SERVER['REMOTE_ADDR'];

$eid = $_REQUEST['i'];
$lid = $_REQUEST['l'];

$sql = "SELECT XE_LINK_CAMPAIGN_ID, XE_LINK_MID, XE_LINK_URL FROM XEBURA_LINK WHERE XE_LINK_ID = '".$lid."'";
$result = $db->query($sql);

if($db->getNumRows($result)>0)
{
while ( list ($cid,$mid,$url,$amount) = $db->fetchQueryRow($result) )
  {

// count every click
// log the event to the statistics table 
$values = array(XE_STAT_CAMPAIGN_ID => $cid, XE_STAT_MID => $mid, XE_STAT_EMAIL_ID => $eid, XE_STAT_LINK_ID => $lid, XE_STAT_TYPE => '2', XE_STAT_IP => $ip, XE_STAT_TIMESTAMP => $now);
$stat = $db->insert("XEBURA_STATISTICS",$values);
//echo 'update - open';
header("Location:".$url);
exit;

	}
	
}else{
	echo 'invalid url';
	exit;
}




?>