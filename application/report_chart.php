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
// campaign report chart script
// jlr
// This script will create an xml file to feed the chart
require 'include/includes.php';


$mid = $_SESSION['Member_Id'];

if(empty($mid)){
	echo "epic #fail, call support.";
	exit;
}


$startdate = $_REQUEST['start'];
$enddate = $_REQUEST['end'];
echo '{ "aaData": [';

$sql = "SELECT 
XC.XE_CAMPAIGN_ID AS ID,
XC.XE_CAMPAIGN_NAME AS NAME,
XJ.XE_JOB_LAUNCH AS LAUNCH,
XJ.XE_JOB_SEND_COUNT AS SENT,
(SELECT COUNT(*) FROM XEBURA_STATISTICS WHERE XE_STAT_CAMPAIGN_ID = XC.XE_CAMPAIGN_ID AND XE_STAT_TYPE = '1') AS OPEN_COUNT,
(SELECT COUNT(*) FROM XEBURA_STATISTICS WHERE XE_STAT_CAMPAIGN_ID = XC.XE_CAMPAIGN_ID AND XE_STAT_TYPE = '2') AS CLICK_COUNT,
(SELECT COUNT(*) FROM XEBURA_STATISTICS WHERE XE_STAT_CAMPAIGN_ID = XC.XE_CAMPAIGN_ID AND XE_STAT_TYPE = '4') AS UNSUB_COUNT
FROM XEBURA_CAMPAIGN AS XC
JOIN XEBURA_JOBS AS XJ ON XC.XE_CAMPAIGN_ID = XJ.XE_JOB_CAMPAIGN_ID
WHERE XE_CAMPAIGN_MID = '".$mid."'
AND XE_CAMPAIGN_STATUS = '2'";



$offer=array();
$i=1;
$db->query($sql);
$result = $db->query($sql);
$total_items = $db->getNumRows($result);	
if($db->getNumRows($result)>0)
{
  while ( list ($id,$name,$date,$sent,$open,$click,$unsub) = $db->fetchQueryRow($result) )
  {


	$launch = date('M d, Y g:i T',(strtotime($date)));
	$open_per =  round((($open/$sent)*100),2);
	$click_per = round((($click/$sent)*100),2);
	$unsub_per = round((($unsub/$sent)*100),2);

	echo '["'.$id.'","'.$name.'","'.$launch.'","'.$sent.'","'.$open_per.'%","'.$click_per.'%","'.$unsub_per.'%"]';
if($db->getNumRows($result)==$i){
		echo "";
	} else {
		echo ",";
	}
$offer[$i++]=$tem;

}
}

//$json_data = json_encode($offer);
//echo $json_data;
echo '] }';
?>