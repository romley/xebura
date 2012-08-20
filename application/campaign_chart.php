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
// campaign chart script
// jlr on 24-04-2010
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
XE_CAMPAIGN_ID AS ID,
XE_CAMPAIGN_NAME AS NAME,
XE_CAMPAIGN_ADDED_DATE AS CREATED,
XE_CAMPAIGN_MODIFIED_DATE AS MODIFIED,
XE_CAMPAIGN_STATUS AS STATUS
FROM XEBURA_CAMPAIGN
WHERE XE_CAMPAIGN_MID = '".$mid."'";



$offer=array();
$i=1;
$db->query($sql);
$result = $db->query($sql);
$total_items = $db->getNumRows($result);	
if($db->getNumRows($result)>0)
{
  while ( list ($id,$name,$created,$modified,$status) = $db->fetchQueryRow($result) )
  {


	$date_clean = date('M d, Y',(strtotime($date)));

switch($status){
	case 0: $status_txt = 'Draft';
	break;
	case 1: $status_txt = 'Scheduled';
	break;
	case 2: $status_txt = 'Launched';
	break;
}
	
	echo '["'.$id.'","'.$name.'","'.$created.'","'.$modified.'","'.$status_txt.'"]';
if($db->getNumRows($result)==$i){
		echo "";
	} else {
		echo ",";
	}
$offer[$i++]=$tem;

}
}

//broken
//$json_data = json_encode($offer);
//echo $json_data;
echo '] }';
?>