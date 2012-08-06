<?PHP
ob_start();
@session_start();

//if($_SESSION['Member_Id']=="") {
//	header("Cache-control:no-cache");
//	header("location:index.php");
//	exit;
//}

if($_SERVER['PHP_SELF'] =='/xebura/home.php') {
	include_once 'config.php';
} else {
	include_once 'include/config.php';
}

require_once 'language/lang.php';
require_once 'class/dbclass.php';
require_once 'class/email.class.php';
require_once 'class/onlineclass.php';
require_once 'Smarty.class.php';
require_once 'variable.php';
require_once 'imagefunction.php';
require_once 'common_functions.php';
if(@!$db)
	$db = new mysqldb();
if(@!$onl)
	$onl = new onlinedb;
if(@!$smarty)
	$smarty = new Smarty;
if(@!$mailobj)
	$mailobj = new MyEmail();		

require_once 'language/smarty_assign.php';

if(!isset($hide_side_menu) || ($hide_side_menu == true))
{
	require_once 'sidemenu_details.php';
}
require_once 'validation.php';

$query = "SELECT LOGIN_STATUS, IS_TEMP_PASSWORD FROM xebura_MEMBERS WHERE MID = '".$_SESSION['Member_Id']."'";
$result=$db->query($query);

if($db->getNumRows($result) >0) 
{ 	
	$row = $db->fetchQueryArray($result);
	$smarty->assign("first_time_login",$row['LOGIN_STATUS']);
	
	if($row['IS_TEMP_PASSWORD'] == 1)
	{
		$smarty->assign("temp_pass",1);
	}
	else
	{
		$smarty->assign("temp_pass",0);
	}
	// echo $row['LOGIN_STATUS'].'-->'.$row['IS_TEMP_PASSWORD']; 
	if($row['LOGIN_STATUS'] == 0 || $row['IS_TEMP_PASSWORD'] == 1)
	{
		// Do not show menu
		$show_user_menu = 0;	
		$topmenu_opt = array("Home");
		$topmenu = array(_DASHBOARD);
	}
	else
	{
		// Show menu
		$show_user_menu = 1;
	}
	
	$script_page_name = $_SERVER['SCRIPT_NAME'];
	$last_indx_of_slash = strrpos($script_page_name, "/");
	$url_page_name = substr($script_page_name, $last_indx_of_slash+1, strlen($script_page_name));
	
	if($row['IS_TEMP_PASSWORD'] == 1 && ($url_page_name != 'newchangepassword.php'))
	{		
		$_SESSION['Nav_Submenu'] = 'ChangePassword';		
		header("Location:newchangepassword.php");
		exit;	
	}
	else if(($row['IS_TEMP_PASSWORD'] != 1) && ($row['LOGIN_STATUS'] == 0) 
	&& ($url_page_name != 'profilemanager.php') && (!isset($_POST['action']) || ($_POST['action'] != 'Update')))
	{
		$_SESSION['Nav_Submenu'] = "ProfileManager";
		header("Location:profilemanager.php");
		exit;	
	}
		
	$smarty->assign("show_user_menu",$show_user_menu);
}
$encoded_member_id = encode($_SESSION['Member_Id']);

$smarty->assign("topmenu",$topmenu);
$smarty->assign("encoded_member_id",$encoded_member_id);
$smarty->assign("view_profile_link",'view_profile.php?UID='.$encoded_member_id);
$smarty->assign("topmenu_opt",$topmenu_opt);
//include('af_search.php');

if($_SESSION['Account_Id'] == '1'){
      $freemid = $_SESSION['Member_Id'];
      $accresult2=$db->query("Select AF_SUBLEVEL from xebura_MEMBERS where MID='".$freemid."'");
      $erows = $db->fetchQueryArray($accresult2);
      $sublevel2 = $erows['AF_SUBLEVEL'];
if ($sublevel2 == 1){
$isfree = "1";
}
else
{
$isfree = "0";
}
}


$smarty->assign("isfree",$isfree);


// J on 2-3-10 modified to check for partner code
      $partnermid = $_SESSION['Member_Id'];
      $accresult2=$db->query("Select PARTNER_ID from xebura_MEMBERS where MID='".$partnermid."'");
      $erows = $db->fetchQueryArray($accresult2);
      $partnerid = $erows['PARTNER_ID'];


$smarty->assign("partnerid",$partnerid);

// GET MEMBER INFO FOR TRACKING IN REINVIGORATE ANALYTICS JLR
      $sql=$db->query("Select FIRSTNAME,LASTNAME,CONTACT_EMAIL from xebura_MEMBERS where MID='".$partnermid."'");
      $rows = $db->fetchQueryArray($sql);
      $fname = $rows['FIRSTNAME'];
	  $lname = $rows['LASTNAME'];
	  $active_member_name = $fname." ".$lname;
	  $active_member_email = $rows['CONTACT_EMAIL'];
	  $smarty->assign("active_member_name",$active_member_name);
	  $smarty->assign("active_member_email",$active_member_email);

$where_clause = "WHERE MID = '".$partnermid."'";
$available_credit = $db->select_single_value("xebura_MEMBERS","TI_CREDIT_BALANCE","$where_clause");
$smarty->assign('available_credit',$available_credit);

//echo "sublevel: $sublevel2";
//echo "is free: $isfree";
//echo "limit: $limitresults";
//echo "mid: $testingmid";
require_once 'random_quote.php';

?>