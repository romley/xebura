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

$action = $_REQUEST['action'];
$mid = $_SESSION['Member_Id'];

/* Code for search. */
foreach($_POST as $key => $value)
{
	global $$key;
	$$key = $value;	
	// print post values JLR DEBUG
	//echo "$$key = $value";
}



$res = $db->query("SELECT
XE_AMZ_ACCESS_KEY AS ACCESS_KEY,
XE_AMZ_SECRET_KEY AS SECRET_KEY
FROM XEBURA_AMAZON_CREDENTIALS
WHERE XE_AMZ_MID = '".$mid."'");
	$row = $db->fetchQueryArray($res);
	$amz_akey = $row['ACCESS_KEY'];
	$amz_skey = $row['SECRET_KEY'];
	$smarty->assign('akey',$amz_akey);
	$smarty->assign('skey',$amz_skey);
	
if($akey!=''){
	$values=array(XE_AMZ_ACCESS_KEY => $akey, XE_AMZ_SECRET_KEY => $skey);
	$db->update("XEBURA_AMAZON_CREDENTIALS",$values,"WHERE XE_AMZ_MID = '".$mid."'");
	$smarty->assign('akey',$akey);
	$smarty->assign('skey',$skey);
	
}



$res = $db->query("SELECT
XE_TWI_ACCOUNT_SID AS ACCOUNT_SID,
XE_TWI_AUTH_TOKEN AS AUTH_TOKEN
FROM XEBURA_TWILIO_CREDENTIALS
WHERE XE_TWI_MID = '".$mid."'");
	$row = $db->fetchQueryArray($res);
	$AccountSid = $row['ACCOUNT_SID'];
	$AuthToken = $row['AUTH_TOKEN'];
	$smarty->assign('asid',$AccountSid);
	$smarty->assign('atoken',$AuthToken);

if($asid!=''){
	$values=array(XE_TWI_ACCOUNT_SID => $asid, XE_TWI_AUTH_TOKEN => $atoken);
	$db->update("XEBURA_TWILIO_CREDENTIALS",$values,"WHERE XE_TWI_MID = '".$mid."'");
	$smarty->assign('asid',$AccountSid);
	$smarty->assign('atoken',$AuthToken);
	
}


if($action==admin){
	$cmail = $_REQUEST['cmail'];
    $member_id = $db->select_single_value("xebura_MEMBERS","MID","WHERE CONTACT_EMAIL = '".$cmail."'");
	$fname = $db->select_single_value("xebura_MEMBERS","FIRSTNAME","WHERE MID = '".$member_id."'");
	$lname = $db->select_single_value("xebura_MEMBERS","LASTNAME","WHERE MID = '".$member_id."'");
	$cust_name = $fname." ".$lname;
	$smarty->assign('cust_name',$cust_name);
	$company = $db->select_single_value("xebura_MEMBERS","COMPANY","WHERE MID = '".$member_id."'");
	$contact_email = $db->select_single_value("xebura_MEMBERS","CONTACT_EMAIL","WHERE MID = '".$member_id."'");
	$smarty->assign('company',$company);
	$smarty->assign('contact_email',$contact_email);
	$accstat = $db->select_single_value("xebura_MEMBERS","ACCOUNT_STATUS","WHERE MID = '".$member_id."'");
	$lastlogin = $db->select_single_value("xebura_MEMBERS","LAST_LOGIN","WHERE MID = '".$member_id."'");
	$smarty->assign('accstat',$accstat);
	$smarty->assign('lastlogin',$lastlogin);
	$smarty->assign('cmail',$cmail);
	$screen = $_REQUEST['screen'];
	$smarty->assign('screen',$screen);
}else{
$member_id = $_SESSION['Member_Id'];
}
//echo "Member ID: $member_id";

$smarty->assign('member_id',$member_id);

$encodedId = encode($member_id);

$smarty->assign('encodedId',$encodedId);



$msg = $_REQUEST['msg'];
$smarty->assign('msg',$msg);

if(empty($action)){
	$action='report_history';
}


$smarty->assign('action',$action);


$where_clause = "WHERE MID = '".$member_id."'";


/// Get Report History

if($action=='report_history' || $action=='admin'){
$sql = "SELECT 
XJ.XE_JOB_CAMPAIGN_ID,
XJ.XE_JOB_LAUNCH,
XC.XE_CAMPAIGN_NAME,
XJ.XE_JOB_STARTED_TIME,
XJ.XE_JOB_ENDED_TIME,
XJ.XE_JOB_SEND_COUNT,
XE_JOB_STATUS
FROM XEBURA_JOBS AS XJ
JOIN XEBURA_CAMPAIGN AS XC ON XJ.XE_JOB_CAMPAIGN_ID = XC.XE_CAMPAIGN_ID
WHERE XC.XE_CAMPAIGN_MID = '".$member_id."' 
ORDER BY XJ.XE_JOB_LAUNCH DESC";	

//echo $sql;
//echo "<br />";
$search_short=array();
$isa=0;
$db->query($sql);
$result_search = $db->query($sql);

if($db->getNumRows($result_search)>0)
{
while ( list ($id,$launch,$name,$start,$end,$sent,$status) = $db->fetchQueryRow($result_search) )
  {
							$tem['launch'] = date('m/d/Y h:i A T',strtotime($launch) );
							$tem['start'] = date('m/d/Y h:i A T',strtotime($start) );
							$tem['end'] = date('m/d/Y h:i A T',strtotime($end) );
switch($status){
	case 0: $status_txt = 'Pending';
	break;
	case 1: $status_txt = 'Started';
	break;
	case 2: $status_txt = 'Running';
	break;
	case 3: $status_txt = 'Waiting';
	break;
	case 4: $status_txt = 'Stopped';
	break;
	case 5: $status_txt = 'Complete';
	break;
	case 6: $status_txt = 'Failed';
	break;
}
							$tem['name'] = $name;		
							$tem['sent'] = $sent;
							$tem['status'] = $status_txt;
		$search_short[$isa++]=$tem;
	}
	
}
}
$smarty->assign("search_short", $search_short);

//echo "<br />";
$pur_short=array();
$isa=0;
$db->query($sql);
$result_search = $db->query($sql);

if($db->getNumRows($result_search)>0)
{
while ( list ($date,$product,$price) = $db->fetchQueryRow($result_search) )
  {
							$tem['date'] = date('m/d/Y h:i A T',strtotime($date) );									  
							$tem['product'] = $product;
							$tem['price'] = '$'.(number_format($price,2));
		//echo "$bandname <br>";
	
		$pur_short[$isa++]=$tem;
	}
	
}

//print_r($pur_short);
$smarty->assign("pur_short",$pur_short);
}



$smarty->display('account_tools.tpl');
if($action!=admin){
$smarty->display('ti_footer.tpl');
}

?>