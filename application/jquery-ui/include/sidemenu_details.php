<?PHP
// number unread message
$num_unread_msg = $db->query_to_array("SELECT count(*) AS cnt from xebura_MESSAGE 
	INNER JOIN xebura_MEMBERS AS MEM ON AF_MESSAGE_SENDER = MEM.MID
	WHERE AF_MESSAGE_RECEIVER='".$_SESSION['Member_Id']."' and  AF_MESSAGE_OPENED='0' and AF_MESSAGE_TRASH=0");
$smarty->assign("number_unread_message",$num_unread_msg[0]['cnt']);

$num_unread_tr_msg = $db->query_to_array("SELECT count(*) AS cnt FROM xebura_MESSAGE AS MES, xebura_MEMBERS AS MEM 
	WHERE ((MES.AF_MESSAGE_RECEIVER ='".$_SESSION['Member_Id']."' AND MES.AF_MESSAGE_TRASH = '1' AND MES.AF_MESSAGE_RECEIVER = MEM.MID )
	OR ( MES.AF_MESSAGE_SENDER = '".$_SESSION['Member_Id']."' AND MES.AF_MESSAGE_SENTITEM_DELETE = '1' AND MES.AF_MESSAGE_SENDER = MEM.MID )
	OR ( MES.AF_MESSAGE_SENDER = '".$_SESSION['Member_Id']."' AND MES.AF_MESSAGE_DRAFTITEM_DELETE = '1' AND MES.AF_MESSAGE_SENDER = MEM.MID )	)
	AND MES.AF_MESSAGE_TRASH_DELETE != '1' AND MES.AF_MESSAGE_OPENED='0'");

$smarty->assign("number_tr_unread_message",$num_unread_tr_msg[0]['cnt']);
//$smarty->assign("number_unread_message",$db->query_affected_rows("select * from xebura_MESSAGE where AF_MESSAGE_RECEIVER='".$_SESSION['Member_Id']."' and  AF_MESSAGE_OPENED='0' and AF_MESSAGE_TRASH=0 "));

/* Force feed details. */
$has_con_news = false;

$sql_news = "SELECT AM.FIRSTNAME, AM.LASTNAME, AN.AF_NEWS_TITLE, AN.AF_NEWS_DESC, AN.AF_NEWS_DATE,
	AN.AF_NEWS_TIME, AN.AF_NEWS_POSTEDTO_MID, AN.AF_NEWS_POSTER_AID, AN.AF_NEWS_POSTER_ID
	FROM xebura_CONTACTS AS AC
	INNER JOIN xebura_NEWS AS AN ON AC.AF_CONTACTS_RECEIVER_MID = AN.AF_NEWS_POSTEDTO_MID
	INNER JOIN xebura_MEMBERS AS AM ON AN.AF_NEWS_POSTER_MID = AM.MID
	WHERE AF_CONTACTS_MID='".$_SESSION['Member_Id']."' AND AF_CONTACTS_ACTIVATED='1'	
	ORDER BY AN.AF_NEWS_ID  DESC LIMIT 0,5";

$news_result = $db->query($sql_news);
$NUM_CON_NEWS = $db->getNumRows($news_result);
$i = 0;
$my_con_news = array();
$news_last_update = '';

if($NUM_CON_NEWS > 0)
{
	$has_con_news = true;
	
	while($news_rows = $db->fetchQueryArray($news_result))
	{
		$poster_aid = $news_rows['AF_NEWS_POSTER_AID'];	
		$postername = $news_rows['FIRSTNAME'].' '.$news_rows['LASTNAME'];
		
		switch($poster_aid)
		{
			case 1:
				$postername = $db->select_single_value("xebura_ARTIST","AF_ARTIST_BANDNAME"," WHERE AF_ARTIST_ID='".$news_rows['AF_NEWS_POSTER_ID']."'");
				break;			
			case 2:
				$postername = $db->select_single_value("xebura_MANAGER","AF_MANAGER_CONTACTNAME"," WHERE AF_MANAGER_ID='$news_rows[AF_NEWS_POSTER_ID]' ");
				break;
			case 3:
				$postername = $db->select_single_value("xebura_AGENT","AF_AGENT_CONTACTNAME"," WHERE AF_AGENT_ID='$news_rows[AF_NEWS_POSTER_ID]' ");
				break;
			case 4:
				$postername = $db->select_single_value("xebura_AGENCY","AF_AGENCY_COMPANYNAME"," WHERE AF_AGENCY_ID='$news_rows[AF_NEWS_POSTER_ID]' ");
				break;
			case 6:
				$postername = $db->select_single_value("xebura_PROMOTER","AF_PROMOTER_COMPANYNAME"," WHERE AF_PROMOTER_ID='".$news_rows['AF_NEWS_POSTER_ID']."'");				
				break;
			case 7:
				$postername = $db->select_single_value("xebura_VENUE","AF_VENUE_NAME"," WHERE AF_VENUE_ID='".$news_rows['AF_NEWS_POSTER_ID']."'");
				break;
			case 8:
				$postername = $db->select_single_value("xebura_BUYER","AF_BUYER_COMPANYNAME"," WHERE AF_BUYER_ID='".$news_rows['AF_NEWS_POSTER_ID']."'");				
				break;
			default:
				$postername="Unknown";
				break;
		}
		$temp['title'] = ucwords(html_script_escape($news_rows['AF_NEWS_TITLE']));
		$temp['news'] = truncate(ucfirst(html_script_escape(nl2br($news_rows['AF_NEWS_DESC']))),50);
		$temp['poster'] = member_type($poster_aid);
		$temp['postername'] = ucwords(html_script_escape($postername));
		$postername = '';				
		$temp['date'] = get_date_format($news_rows['AF_NEWS_DATE'])	;
		$temp['time'] = get_time_format($news_rows['AF_NEWS_TIME']);
		$posted_to_mid = $news_rows['AF_NEWS_POSTEDTO_MID'];
		$temp['posted_to_cid'] = encode($posted_to_mid);
		$posted_to_mid = '';	
		
		if($i==0)
		{
			$news_last_update = $temp['date'];
		}
		$my_con_news[$i++]=$temp;
	}
}
$smarty->assign("NEWS_LASTUPDATED",$news_last_update);
$smarty->assign("NUM_CON_NEWS",$NUM_CON_NEWS);
$smarty->assign("my_con_news",$my_con_news);
$smarty->assign("has_con_news",$has_con_news);
/* End of force feed details. */

/* Get total booking YTD */
$where_booking_ytd = '';
$group_by_id = '';
switch($_SESSION['Account_Id'])
{
	case 1:
		$where_booking_ytd = "bd.AF_ARTIST_ID = '".$_SESSION['User_Account_Id']."'";
		//$group_by_id = 
		break;
	case 2:
		$where_booking_ytd = "bd.AF_MANAGER_ID = '".$_SESSION['User_Account_Id']."'";
		break;
	case 3:
		$where_booking_ytd = "bd.AF_AGENT_ID = '".$_SESSION['User_Account_Id']."'";
		break;
	case 4:
		$where_booking_ytd = "bd.AF_AGENCY_ID = '".$_SESSION['User_Account_Id']."'";
		break;
	case 6:
		$where_booking_ytd = "bd.AF_PROMOTER_ID = '".$_SESSION['User_Account_Id']."'";
		break;
	case 7:
		$where_booking_ytd = "bd.AF_VENUE_ID = '".$_SESSION['User_Account_Id']."'";
		break;
	case 8:
		$where_booking_ytd = "bd.AF_MAIN_BUYER_ID = '".$_SESSION['User_Account_Id']."'";
		break;
		
}
$sql_booking_ytd = "SELECT COUNT(*) AS BOOKING_DATE, bd.AF_TERM_AMOUNT_TYPE, SUM(bd.AF_TERM_BOOKING_AMOUNT) AS TOT_AMOUNT
	FROM xebura_BOOKING_DETAILS as bd
	INNER JOIN xebura_ARTIST as a ON bd.AF_ARTIST_ID = a.AF_ARTIST_ID 
	INNER JOIN xebura_VENUE as v ON  bd.AF_VENUE_ID = v.AF_VENUE_ID 
	INNER JOIN xebura_BOOKING_STATUS as bs 
	ON (bd.AF_BOOKING_ID=bs.AF_BOOKING_ID AND bs.AF_BOOKING_STATUS_NAME='Contract Status' AND bs.AF_BOOKING_STATUS_STATE = 'Confirmed') 
	WHERE bd.AF_BOOKING_DETAILS_ID IN 
	(SELECT max(AF_BOOKING_DETAILS_ID) FROM xebura_BOOKING_DETAILS group by AF_BOOKING_ID) 
	AND bd.AF_BOOKING_ACTIVE = 1	
	AND $where_booking_ytd 
	GROUP BY bd.AF_TERM_AMOUNT_TYPE
	ORDER BY bd.AF_BOOKING_ADDED_DATE desc, bd.AF_BOOKING_ID desc";

$rs_booking_ytd = $db->query($sql_booking_ytd);
$has_booking_ytd = false;

if($db->getNumRows($rs_booking_ytd) > 0)
{
	$tot_booking_ytd = array();
	$ytd_cnt = 0;
	$has_booking_ytd = true;
	
	while($row_booking_ytd = $db->fetchQueryArray($rs_booking_ytd))
	{
		$temp = array();
		$temp['amt'] = $row_booking_ytd['TOT_AMOUNT'];
		$temp['date'] = $row_booking_ytd['BOOKING_DATE'];
		$temp['type'] = convert_amounttype($row_booking_ytd['AF_TERM_AMOUNT_TYPE']);
		$tot_booking_ytd[$ytd_cnt++] = $temp;
	}
	$smarty->assign("tot_booking_ytd",$tot_booking_ytd);
}
$smarty->assign("has_booking_ytd",$has_booking_ytd);
/* End of Getting Booking YTD. */

// featured oppurtunity details
/*
$sql_featured_oppurtunity="select * from xebura_OPPORTUNITY ";
$smarty->assign("featured_oppurtunity",$db->query_to_array($sql_featured_oppurtunity));
$smarty->assign("number_featured_oppurtunity",$db->query_affected_rows($sql_featured_oppurtunity));
*/

// details
switch($_SESSION['Account_Id'])
{

	//artist
	case 1:
		$side_menu_arr = $db->query_to_array("SELECT AF_ARTIST_PRIMARYPHOTO, AF_ARTIST_BANDNAME FROM xebura_ARTIST WHERE AF_ARTIST_MID='".$_SESSION['Member_Id']."'");
		$artist_primary_photo = $side_menu_arr[0]['AF_ARTIST_PRIMARYPHOTO'];
		$artist_band_name = $side_menu_arr[0]['AF_ARTIST_BANDNAME'];	
		
		if(file_exists("uploads/artist/$_SESSION[User_Account_Id]/medium_$artist_primary_photo"))
		{
			$smarty->assign("SIDEMENU_ARTIST_PHOTO",'medium_'.$artist_primary_photo);
		}
		else if(file_exists("uploads/artist/$_SESSION[User_Account_Id]/$artist_primary_photo"))
		{
			$smarty->assign("SIDEMENU_ARTIST_PHOTO",$artist_primary_photo);
		}
		else 
		{
			$smarty->assign("SIDEMENU_ARTIST_PHOTO",$artist_primary_photo);
		}
		$_SESSION['BAND_NAME'] = $artist_band_name;
		$smarty->assign("SIDEMENU_ARTIST_BANDNAME",ucwords($artist_band_name));
		$smarty->assign("SIDEMENU_MASTER_REQUEST",$db->query_affected_rows(" select * from xebura_SELECTION where AF_SELECTION_SLAVE_ID='".$_SESSION['User_Account_Id']."' and AF_SELECTION_READ=0 "));
	break ;
	
	case 2:
		$side_menu_arr = $db->query_to_array("SELECT AF_MANAGER_PHOTO, AF_MANAGER_COMPANYNAME FROM xebura_MANAGER WHERE AF_MANAGER_MID='".$_SESSION['Member_Id']."'");
		$man_primary_photo = $side_menu_arr[0]['AF_MANAGER_PHOTO'];
		$man_comp_name = $side_menu_arr[0]['AF_MANAGER_COMPANYNAME'];		
	
		if(file_exists("uploads/manager/$_SESSION[User_Account_Id]/medium_$man_primary_photo"))
		{
			$smarty->assign("SIDEMENU_MANAGER_PHOTO",'medium_'.$man_primary_photo);
		}
		else if(file_exists("uploads/manager/$_SESSION[User_Account_Id]/$man_primary_photo"))
		{
			$smarty->assign("SIDEMENU_MANAGER_PHOTO",$man_primary_photo);
		}
		else 
		{
			$smarty->assign("SIDEMENU_MANAGER_PHOTO",$man_primary_photo);
		}
		$smarty->assign("SIDEMENU_MANAGER_COMPANYNAME", $man_comp_name);
		break ;
	
	case 3:
		$side_menu_arr = $db->query_to_array("SELECT AG.AF_AGENT_PHOTO, AGC.AF_AGENCY_COMPANYNAME 
			FROM xebura_AGENT AS AG LEFT JOIN xebura_AGENCY AS AGC ON AG.AF_AGENCY_ID=AGC.AF_AGENCY_ID
			WHERE AG.AF_AGENT_MID='".$_SESSION['Member_Id']."'");
		
		$agent_primary_photo = $side_menu_arr[0]['AF_AGENT_PHOTO'];
		$agent_comp_name = $side_menu_arr[0]['AF_AGENCY_COMPANYNAME'];	
		
		if(file_exists("uploads/agent/$_SESSION[User_Account_Id]/medium_$agent_primary_photo"))
		{
			$smarty->assign("SIDEMENU_AGENT_PHOTO",'medium_'.$agent_primary_photo);
		}
		else if(file_exists("uploads/agent/$_SESSION[User_Account_Id]/$agent_primary_photo"))
		{
			$smarty->assign("SIDEMENU_AGENT_PHOTO",$agent_primary_photo);
		}
		else 
		{
			$smarty->assign("SIDEMENU_AGENT_PHOTO",$agent_primary_photo);
		}			
		$smarty->assign("SIDEMENU_AGENCY_COMPANY",$agent_comp_name);
		break ;
	
	case 4:
		$side_menu_arr = $db->query_to_array("SELECT AF_AGENCY_LOGO, AF_AGENCY_COMPANYNAME 
			FROM xebura_AGENCY 
			WHERE AF_AGENCY_MID='".$_SESSION['Member_Id']."'");
					
		$agency_logo = $side_menu_arr[0]['AF_AGENCY_LOGO'];		
		$agency_comp_name = $side_menu_arr[0]['AF_AGENCY_COMPANYNAME'];			
		
		if(file_exists("uploads/agency/$_SESSION[User_Account_Id]/medium_$agency_logo"))
		{
			$smarty->assign("SIDEMENU_AGENCY_PHOTO",'medium_'.$agency_logo);
		}
		else if(file_exists("uploads/agency/$_SESSION[User_Account_Id]/$agency_logo"))
		{
			$smarty->assign("SIDEMENU_AGENCY_PHOTO",$agency_logo);
		}
		else 
		{
			$smarty->assign("SIDEMENU_AGENCY_PHOTO",$agency_logo);
		}
		$smarty->assign("SIDEMENU_AGENCY_COMPANYNAME", $agency_comp_name);
		break ;
	
	case 5:
	break;
	
	case 6:
		$side_menu_arr = $db->query_to_array("SELECT AF_PROMOTER_PHOTO, AF_PROMOTER_COMPANYNAME 
			FROM xebura_PROMOTER 
			WHERE AF_PROMOTER_MID='".$_SESSION['Member_Id']."'");
					
		$promo_primary_photo = $side_menu_arr[0]['AF_PROMOTER_PHOTO'];		
		$promo_comp_name = $side_menu_arr[0]['AF_PROMOTER_COMPANYNAME'];				
		
		if(file_exists("uploads/promoter/$_SESSION[User_Account_Id]/medium_$promo_primary_photo"))
		{
			$smarty->assign("SIDEMENU_PROMOTER_PHOTO",'medium_'.$promo_primary_photo);
		}
		else if(file_exists("uploads/promoter/$_SESSION[User_Account_Id]/$promo_primary_photo"))
		{
			$smarty->assign("SIDEMENU_PROMOTER_PHOTO",$promo_primary_photo);
		}		
		else 
		{
			$smarty->assign("SIDEMENU_PROMOTER_PHOTO", $promo_primary_photo);
		}
		$_SESSION['PROMOTER_COMP_NAME'] = $promo_comp_name;
		$smarty->assign("SIDEMENU_PROMOTER_COMPANY", $promo_comp_name);
	break ;
	
	case 7:
		$side_menu_arr = $db->query_to_array("SELECT AF_VENUE_PHOTO1, AF_VENUE_NAME, AF_VENUE_CITY 
			FROM xebura_VENUE 
			WHERE AF_VENUE_MID='".$_SESSION['Member_Id']."'");
					
		$ven_primary_photo = $side_menu_arr[0]['AF_VENUE_PHOTO1'];		
		$_SESSION['VENUE_NAME'] = $side_menu_arr[0]['AF_VENUE_NAME'];	
		$_SESSION['VENUE_CITY'] = $side_menu_arr[0]['AF_VENUE_CITY'];	
	
		if(file_exists("uploads/venue/$_SESSION[User_Account_Id]/medium_$ven_primary_photo"))
		{
			$smarty->assign("SIDEMENU_VENUE_PHOTO",'medium_'.$ven_primary_photo);
		}
		else if(file_exists("uploads/venue/$_SESSION[User_Account_Id]/$ven_primary_photo"))
		{
			$smarty->assign("SIDEMENU_VENUE_PHOTO",$ven_primary_photo);
		}
		else 
		{
			$smarty->assign("SIDEMENU_VENUE_PHOTO",$ven_primary_photo);
		}	
		break;
	
	//buyer
	case 8:
		$side_menu_arr = $db->query_to_array("SELECT AF_BUYER_PHOTO, AF_BUYER_LOGO,AF_BUYER_COMPANYNAME 
			FROM xebura_BUYER 
			WHERE AF_BUYER_MID='".$_SESSION['Member_Id']."'");
					
		$buyer_primary_photo = $side_menu_arr[0]['AF_BUYER_PHOTO'];	
		$buyer_logo = $side_menu_arr[0]['AF_BUYER_LOGO'];		
		$buyer_comp_name = $side_menu_arr[0]['AF_BUYER_COMPANYNAME'];			
		
		if(file_exists("uploads/buyer/$_SESSION[User_Account_Id]/medium_$buyer_primary_photo"))
		{
			$smarty->assign("SIDEMENU_BUYER_PHOTO",'medium_'.$buyer_primary_photo);
		}
		else if(file_exists("uploads/buyer/$_SESSION[User_Account_Id]/$buyer_primary_photo"))
		{
			$smarty->assign("SIDEMENU_BUYER_PHOTO",$buyer_primary_photo);
		}
		else 
		{
			$smarty->assign("SIDEMENU_BUYER_PHOTO",$buyer_primary_photo);
		}		
		
		if(file_exists("uploads/buyer/$_SESSION[User_Account_Id]/medium_$buyer_logo"))
		{
			$smarty->assign("SIDEMENU_BUYER_LOGO",'medium_'.$buyer_logo);
		}
		else if(file_exists("uploads/buyer/$_SESSION[User_Account_Id]/$buyer_logo"))
		{
			$smarty->assign("SIDEMENU_BUYER_LOGO",$buyer_logo);
		}
		else 
		{
			$smarty->assign("SIDEMENU_BUYER_LOGO",$buyer_logo);
		}
		$_SESSION['BUYER_COMP_NAME'] = $buyer_comp_name;
		$smarty->assign("SIDEMENU_BUYER_COMPANY", $buyer_comp_name);
		break;

}

$smarty->assign("new_opt_name",$create_new_opt);
$smarty->assign("new_opt_code",$create_new_opt_code);
if(isset($_REQUEST['createnew']))
	$smarty->assign("createstate",$_REQUEST['createnew']);
?>