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
require 'include/includes.php';

$contact_id = $_REQUEST['i'];
$mid = $_SESSION['Member_Id'];

	$sql = "SELECT  
XE_ADDRESS_ID AS ID,
XE_ADDRESS_EMAIL AS EMAIL,
XE_ADDRESS_FNAME AS FNAME,
XE_ADDRESS_LNAME AS LNAME,
XE_ADDRESS_COMPANY AS COMPANY,
XE_ADDRESS_PHONE AS PHONE,
XE_ADDRESS_STREET1 AS STREET1,
XE_ADDRESS_STREET2 AS STREET2,
XE_ADDRESS_CITY AS CITY,
XE_ADDRESS_STATE AS STATE,
XE_ADDRESS_ZIP AS ZIP,
XE_ADDRESS_COUNTRY AS COUNTRY,
XE_ADDRESS_OPT_STATUS AS STATUS,
XE_ADDRESS_ADD_DATE AS ADD_DATE
FROM XEBURA_MASTER_LIST 
WHERE XE_ADDRESS_ID = '".$contact_id."'";

	
$db->query($sql);
$result = $db->query($sql);
$total_items = $db->getNumRows($result);	
	if($db->getNumRows($result)>0)
{
  while ( list ($id,$email,$fname,$lname,$company,$phone,$street1,$street2,$city,$state,$zip,$country,$status,$date) = $db->fetchQueryRow($result) )
  {


	$date_clean = date('M d, Y',(strtotime($date)));

	$smarty->assign('contact_id',$id);
	$smarty->assign('email',$email);
	$smarty->assign('fname',$fname);
	$smarty->assign('lname',$lname);
	$smarty->assign('company',$company);
	$smarty->assign('phone',$phone);
	$smarty->assign('street1',$street1);
	$smarty->assign('street2',$street2);
	$smarty->assign('city',$city);
	$smarty->assign('state',$state);
	$smarty->assign('zip',$zip);
	$smarty->assign('country',$country);
	$smarty->assign('status',$status);
	$smarty->assign('date',$date_clean);
	

}
}

$action = 'update';
$smarty->assign('action',$action);

	
	$smarty->display('contact_detail.tpl');

?>