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
// list chart script
// jlr 
// This script will create an xml file to feed the chart
require 'include/includes.php';


$mid = $_SESSION['Member_Id'];
$action = $_REQUEST['list'];

if(empty($mid)){
	echo "epic #fail, call support.";
	exit;
}


$startdate = $_REQUEST['start'];
$enddate = $_REQUEST['end'];
echo '{ "aaData": [';

$sql = "SELECT  
XE_GROUP_ID,
XE_GROUP_NAME,
XE_GROUP_ADD_DATE,
XE_GROUP_LAST_LAUNCH_DATE
FROM XEBURA_EMAIL_GROUP
WHERE XE_GROUP_MID = '".$mid."'";




$offer=array();
$i=1;
$db->query($sql);
$result = $db->query($sql);
$total_items = $db->getNumRows($result);	
if($db->getNumRows($result)>0)
{
  while ( list ($id,$name,$added,$launch) = $db->fetchQueryRow($result) )
  {


	$date_clean = date('M d, Y',(strtotime($date)));

$res = $db->query("SELECT COUNT(*) AS MEMBERS FROM XEBURA_MASTER_LIST WHERE XE_ADDRESS_GROUP_ID = '".$id."'");
$row = $db->fetchQueryArray($res);
$members = $row['MEMBERS'];


if($action==select){
	echo '["'.$id.'","<input name=\'select[]\' type=\'checkbox\' value=\''.$id.'\' />","'.$name.'","'.$added.'","'.$members.'","'.$launch.'"]';
}else{
	echo '["'.$id.'","'.$name.'","'.$added.'","'.$members.'","'.$launch.'"]';	
}
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