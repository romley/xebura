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
require 'include/includes.php';

//echo 'hi hi hi';
$contact_id = $_REQUEST['i'];
$mid = $_SESSION['Member_Id'];

/* Code for search. */
foreach($_POST as $key => $value)
{
	global $$key;
	$$key = $value;	
}
//echo 'action ='.$action;
	//echo 'howdy';
	if($action=='update'){
	$values = array(XE_ADDRESS_EMAIL => $email, XE_ADDRESS_FNAME => $fname, XE_ADDRESS_LNAME => $lname, XE_ADDRESS_COMPANY => $company, XE_ADDRESS_PHONE => $phone, XE_ADDRESS_STREET1 => $street1, XE_ADDRESS_STREET2 => $street2, XE_ADDRESS_CITY => $city, XE_ADDRESS_STATE => $state, XE_ADDRESS_ZIP => $zip, XE_ADDRESS_COUNTRY => $country, XE_ADDRESS_OPT_STATUS => $status);
	//print_r($values);
	$db->update("XEBURA_MASTER_LIST",$values,"WHERE XE_ADDRESS_ID='".$contact_id."' AND XE_ADDRESS_MID='".$mid."'");
//echo "WHERE XE_ADDRESS_ID='".$contact_id."' AND XE_ADDRESS_MID='".$mid."'";
echo 'OK';
exit;
	}elseif($action=='add'){
		$date = date('Y-m-d G:i:s',(strtotime("now")));
		$values = array(XE_ADDRESS_MID => $mid, XE_ADDRESS_GROUP_ID => $gid, XE_ADDRESS_EMAIL => $email, XE_ADDRESS_FNAME => $fname, XE_ADDRESS_LNAME => $lname, XE_ADDRESS_COMPANY => $company, XE_ADDRESS_PHONE => $phone, XE_ADDRESS_STREET1 => $street1, XE_ADDRESS_STREET2 => $street2, XE_ADDRESS_CITY => $city, XE_ADDRESS_STATE => $state, XE_ADDRESS_ZIP => $zip, XE_ADDRESS_COUNTRY => $country, XE_ADDRESS_OPT_STATUS => $status, XE_ADDRESS_ADD_DATE => $date);
	//print_r($values);
	$id=$db->insert("XEBURA_MASTER_LIST",$values);
	echo 'OK-ADD';
exit;
	}


?>