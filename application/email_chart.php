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
// email office chart script
// This script will create an xml file to feed the chart
require 'include/includes.php';


$mid = $_SESSION['Member_Id'];

if(empty($mid)){
	echo "epic #fail, call support.";
	exit;
}


$startdate = $_REQUEST['start'];
$enddate = $_REQUEST['end'];

$gid = $_REQUEST['i'];

echo '{ "aaData": [';

$sql = "SELECT  
XE_ADDRESS_ID AS ID,
XE_ADDRESS_EMAIL AS EMAIL,
XE_ADDRESS_FNAME AS FNAME,
XE_ADDRESS_LNAME AS LNAME,
XE_ADDRESS_COMPANY AS COMPANY,
XE_ADDRESS_OPT_STATUS AS STATUS,
XE_ADDRESS_ADD_DATE AS ADD_DATE
FROM XEBURA_MASTER_LIST ";

if(empty($gid)){
	$where_clause = " WHERE XE_ADDRESS_MID = '".$mid."'";
}else{
$where_clause = " WHERE XE_ADDRESS_GROUP_ID = '".$gid."' AND XE_ADDRESS_MID = '".$mid."'";
}


$offer=array();
$i=1;
$db->query($sql.$where_clause);
$result = $db->query($sql.$where_clause);
$total_items = $db->getNumRows($result);	
if($db->getNumRows($result)>0)
{
  while ( list ($id,$email,$fname,$lname,$company,$status,$date) = $db->fetchQueryRow($result) )
  {


	$date_clean = date('M d, Y',(strtotime($date)));
switch($status){
	case 0: $status_txt = 'Single-Opt';
	break;
	case 1: $status_txt = 'Double-Opt';
	break;
	case 2: $status_txt = 'Unsubcribed';
	break;
	case 3: $status_txt = 'Bounced';
	break;
}
	
	
	echo '["'.$id.'","'.$fname.'","'.$lname.'","'.$company.'","'.$email.'","'.$status_txt.'","'.$date_clean.'"]';
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