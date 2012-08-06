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
/* If the session exists for a user and it accidently comes to the index page
then first it unsets the message on session. */
if(isset($_SESSION['SHOWMESSAGE']))
{
	unset($_SESSION['SHOWMESSAGE']);
}
include "include/unsecure_includes.php";

if(isset($_REQUEST['username']) && trim($_REQUEST['username'])!="") 
{
	$db = new mysqldb();
	$sql="select  * from  xebura_MEMBERS where USERNAME='".trim($_REQUEST['username'])."' and PASSWORD='". base64_encode(trim($_REQUEST['password']))."' AND ACCOUNT_STATUS ='1'";
	$result=$db->query($sql);
	$num=$db->getNumRows($result);
	
	if($num >0) { 
		$row=$db->fetchQueryArray($result);
		$aid=$row["AID"]; 
		$mid=$row["MID"];
		
		/*include "online.php";
		$row=$db->fetchQueryArray($result);
		$aid=$row["AID"]; echo $aid;
		$mid=$row["MID"];echo $mid;
		//exit;
		if($_POST['username']!="")
		{
		$users_online_read = fopen("$log_file", "r");
		$users_online = fread($users_online_read, filesize("$log_file"));
		fclose($users_online_read);
		$users_online=explode("\n",$users_online);
		while (list ($key, $val) = each ($users_online)) 
		{
		$user_details=explode("|",$val);
		if($row['USERNAME']==trim($user_details[3]))
		 {
		header("location:signout.php?signedin=1&user=$row[USERNAME]");
		exit;
		 }
		}
		}*/
		$_SESSION['Username']=$row['USERNAME'];
		$_SESSION['Password']=base64_decode($row['PASSWORD']);
		$_SESSION['First_Name']=ucwords($row['FIRSTNAME'])."&nbsp;".ucwords($row['LASTNAME']);
		
		$_SESSION['Member_Id']=$mid;
		$_SESSION['Account_Id']=$aid;  
		$lastlogin=array(LAST_LOGIN =>date('Y-m-d'),LOGIN_TIME =>date('H:i:s'));
		$db->update("xebura_MEMBERS",$lastlogin," where MID='".$mid."' ");
		
		
		if($aid=='1')	{ 
		$_SESSION['User_Account_Id']=$db->select_single_value("xebura_ARTIST","AF_ARTIST_ID"," where AF_ARTIST_MID='".$mid."' ");		
		}
		else if($aid=='2')	{
			$_SESSION['User_Account_Id']=$db->select_single_value("xebura_MANAGER","AF_MANAGER_ID"," where AF_MANAGER_MID='".$mid."' ");				
		}
		else if($aid=='3')	{
			$_SESSION['User_Account_Id']=$db->select_single_value("xebura_AGENT","AF_AGENT_ID"," where AF_AGENT_MID='".$mid."' ");						
		}
		else if($aid=='4')	{
			$_SESSION['User_Account_Id']=$db->select_single_value("xebura_AGENCY","AF_AGENCY_ID"," where AF_AGENCY_MID='".$mid."'");						
		}
		else if($aid=='5')	{
			header("location:pageunderconst.php"); 		
		}
		else if($aid=='6')	{
			$_SESSION['User_Account_Id']=$db->select_single_value("xebura_PROMOTER","AF_PROMOTER_ID"," where AF_PROMOTER_MID='".$mid."'");						
		}
		else if($aid=='7')	{   
			$_SESSION['User_Account_Id']=$db->select_single_value("xebura_VENUE","AF_VENUE_ID"," where AF_VENUE_MID='".$mid."' ");	
		}
		else if($aid=='8')	{
			$_SESSION['User_Account_Id']=$db->select_single_value("xebura_BUYER","AF_BUYER_ID"," where AF_BUYER_MID='".$mid."'");						
		}
		include "online.php";
		$_SESSION['Nav_Menu']="Home";
	        $encoded_id = encode($mid);	
		if($row['NEED_UPDATE'] == 1)
		{
			$page = "https://af1.xebura.com/sf_response_handler.php?MID=$encoded_id&sid=atele";
                }
		else if($row['IS_TEMP_PASSWORD'] == 1)
		{
			$_SESSION['Nav_Submenu'] = "ChangePassword";
			//unset($_SESSION['Nav_Submenu']);	
			//$_SESSION['Redirected_To_Ch'] = 1;		
			$page = 'newchangepassword.php';
		}
		else if($row['LOGIN_STATUS'] == 0)
		{
			$_SESSION['Nav_Submenu'] = "ProfileManager";
			//unset($_SESSION['Nav_Submenu']);
			//$_SESSION['Redirected_To_PF'] = 1;
			$page = 'profilemanager.php';
		}
		else
		{	
			$_SESSION['Nav_Submenu']="Dashboard";
			$page = 'home';
		}
		 
		header("Location:$page");
		exit;
  }
  else {
   $msg="Invalid Username and Password or Inactive Account. Try again...";
  }
}
else if(isset($_GET['m']) && isset($_GET['id']))
{
	$mid = decode($_GET['id']);
	
	if($mid > 0)
	{
		$sql = "select  * from  xebura_MEMBERS where MID='".$mid."' AND ACCOUNT_STATUS ='1'";
		
		$result=$db->query($sql);
		$num=$db->getNumRows($result);
		
		if($num >0) 
		{ 
		
			$row=$db->fetchQueryArray($result);
			$aid=$row["AID"]; 
			$mid=$row["MID"];
			
			$Mes_Id = decode($_REQUEST['m']);
			
			if($Mes_Id > 0)
			{				
				$Mes_Id = $db->select_single_value("xebura_MESSAGE","AF_MESSAGE_ID", " WHERE AF_MESSAGE_ID='".$Mes_Id."' and AF_MESSAGE_RECEIVER='".$mid."' ");		
				
				if($Mes_Id > 0)
				{
					$_SESSION['Username']=$row['USERNAME'];
					$_SESSION['Password']=base64_decode($row['PASSWORD']);
					$_SESSION['First_Name']=ucwords($row['FIRSTNAME'])."&nbsp;".ucwords($row['LASTNAME']);
					
					$_SESSION['Member_Id']=$mid;
					$_SESSION['Account_Id']=$aid;  
					$lastlogin=array('LAST_LOGIN' =>date('Y-m-d'),'LOGIN_TIME' =>date('H:i:s'));
					$db->update("xebura_MEMBERS",$lastlogin," where MID='".$mid."' ");
					
					
					if($aid=='1')	
					{ 
						$_SESSION['User_Account_Id']=$db->select_single_value("xebura_ARTIST","AF_ARTIST_ID"," where AF_ARTIST_MID='".$mid."' ");		
					}
					else if($aid=='2')	
					{
						$_SESSION['User_Account_Id']=$db->select_single_value("xebura_MANAGER","AF_MANAGER_ID"," where AF_MANAGER_MID='".$mid."' ");				
					}
					else if($aid=='3')	
					{
						$_SESSION['User_Account_Id']=$db->select_single_value("xebura_AGENT","AF_AGENT_ID"," where AF_AGENT_MID='".$mid."' ");						
					}
					else if($aid=='4')	
					{
						$_SESSION['User_Account_Id']=$db->select_single_value("xebura_AGENCY","AF_AGENCY_ID"," where AF_AGENCY_MID='".$mid."'");						
					}
					else if($aid=='5')	
					{
						header("location:pageunderconst.php"); 		
					}
					else if($aid=='6')	
					{
						$_SESSION['User_Account_Id']=$db->select_single_value("xebura_PROMOTER","AF_PROMOTER_ID"," where AF_PROMOTER_MID='".$mid."'");						
					}
					else if($aid=='7')	
					{   
						$_SESSION['User_Account_Id']=$db->select_single_value("xebura_VENUE","AF_VENUE_ID"," where AF_VENUE_MID='".$mid."' ");	
					}
					else if($aid=='8')	
					{
						$_SESSION['User_Account_Id']=$db->select_single_value("xebura_BUYER","AF_BUYER_ID"," where AF_BUYER_MID='".$mid."'");						
					}
					include "online.php";
					$_SESSION['Nav_Menu']="Mail";			
				
					$page = 'view_message.php?Mes_Id='.$_REQUEST['m'];					 
					header("Location:$page");
					exit;
				}
				else
				{
					$msg="Invalid Link.Try again..";
				}
			}
			else
			{
				$msg="Invalid Link.Try again..";
			}
	  }
	  else 
	  {
	   $msg="Invalid Link.Try again..";
	  }
	}
	else
	{
		$msg="Invalid Link.Try again..";
	}
}

if(isset($_REQUEST['Msg']) && $_REQUEST['Msg']!='')
{
	$msg=$_REQUEST['Msg'];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Xebura Customer Log-in</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="author" content="" />
	<link rel="Stylesheet" href="css/reset.css" type="text/css" media="screen" />
	<link rel="Stylesheet" href="css/login.css" type="text/css" media="screen" />
	<!--[if IE 6]>
		<link rel="Stylesheet" href="css/ie6.css" type="text/css" media="screen" />
	<![endif]--> 
	<!--[if IE 7]><link rel="Stylesheet" href="css/ie7.css" type="text/css" media="screen" /><![endif]--> 
	<link rel="Stylesheet" href="css/global.css" type="text/css" media="screen" />
    <!--[if IE 6]>
        <script src="js/DD_belated_png.js"></script>
        <script>DD_belatedPNG.fix('.png-fix');</script>
    <![endif]--> 
	
	<script type="text/javascript" src="js/jquery-1.4.min.js"></script>
	<script type="text/javascript" src="js/login.js"></script>
                    <script type="text/javascript" src="colorbox/colorbox/jquery.colorbox.js"></script>
        <link type="text/css" media="screen" rel="stylesheet" href="colorbox/colorbox/colorbox.css" />

	<script type="text/javascript">function demochk(){}</script>

<script type="text/javascript" charset="utf-8">
    function forgot_password(){
	/*$("embed").attr("wmode", "opaque").wrap('<div>');*/
	/*window.open('artist_sub_scores?artist={/literal}{$artistid}{literal}&start='+dateId,'cityreport','width=1025,height=640,resizable=1');*/
	$.fn.colorbox({iframe:true,width:900,height:340,initialWidth:75,initialHeight:75,href:"forgot_password"});

}
</script>

</head>
<body>

		<script>
   if ($.browser.msie) {
/*    alert("Internet Explorer is not a fully supported browser. For the best experience, please use a modern browser such as Firefox, Safari or Chrome.");
*/
$("<div class='nocufon browser_warning'>Please use a modern browser with HTML5 support such as <a class='nocufon' href='http://www.mozilla.com' target='_new'>Firefox</a>, <a class='nocufon' href='http://www.apple.com/safari/' target='_new'>Safari</a> or <a class='nocufon' href='http://www.google.com/chrome/' target='_new'>Chrome.</a></div>").appendTo(document.body);
}
 
 </script>
		<div class="login">
        <div class="bug"><img src="images/logo.png" height="71" alt="Xebura.com | Customer Log-in" /></div>
			<form action="index.php" method="post" name="form">
		    <p class="lerror"><?PHP
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
//============================================================+ if(isset($msg)) { echo $msg; }?></p>
				<ul class="clearfix">
				  <li class="left">
					<label>User Name</label>
				    <input name="username" class="mtext" id="username" size="25" type="text" onfocus="username.className='mtexth';" onblur="username.className='mtext';" />
				  </li>
					<li class="right">
						<label>Password</label>
						<input name="password" class="mtext" id="password" size="15" type="password" onfocus="password.className='mtexth';" onblur="password.className='mtext';"/>
					</li>
					<li class="action">
					<a href="#" title="Password Reset" onclick="forgot_password();" >Forgot your password?</a>
					<button class="submit" accesskey="65" onclick="return demochk(form);" value="Log In" type="submit" >Login</button>
					</li>
				</ul>
			</form>
			<div class="footer">
<!--<ul class="site-actions clearfix">
					<span><strong>New Users:</strong> Please retrieve your user name and temporary password from your email account.</span><br />
                    <span><strong>Existing Customers:</strong> Contact Xebura support if you need help.</span>
			  </ul> -->
	    <div class="site-branding clearfix"></div>
			</div>
		</div>

</body>
</html>
