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
/* This tells not to include the sidemenu page in the includes page. */
$hide_side_menu = false;

require 'include/includes.php';
$mid = $_SESSION['Member_Id'];

$_SESSION['Nav_Menu']="Home";

/* Code for search. */
foreach($_POST as $key => $value)
{
	global $$key;
	$$key = $value;	
	// print post values JLR DEBUG
	//echo "$$key = $value";
}

// gross revenue ticker

$sql = "SELECT AF_BB_BS_AVG_GROSS AS GROSS, 
AF_BB_BS_ARTIST_PRIMARY AS ARTIST,
AF_BB_BS_ARTIST_PRIMARY_ID AS ARTISTID
FROM xebura_TI_BOX_OFFICE
WHERE AF_BB_BS_AVG_GROSS > 5000
ORDER BY AF_BB_BS_PKID DESC
LIMIT 50";

$isa=0;
$db->query($sql);
$result = $db->query($sql);

if($db->getNumRows($result)>0)
{
while ( list ($gross,$artist,$artistid) = $db->fetchQueryRow($result) )
  {
							$tem['gross'] = number_format($gross);									  
							$tem['artist'] = $artist;
							$tem['artistid'] = $artistid;
	
		$revticker[$isa++]=$tem;
	}
	
}

	$smarty->assign('revticker',$revticker);
	
	/* Get Average Concert Attendance. */ // MODIFIED BY JLR! 
	$sql = "select AVG(AF_BB_BS_AVG_ATTENDANCE) as average_att, 
										 AF_BB_BS_CHART_MONTH AS chart_month,
										 AF_BB_BS_CHART_YEAR as chart_year
										from xebura_TI_BOX_OFFICE
										where AF_BB_BS_CHART_DATE_ID BETWEEN '133' AND '144'
										group by AF_BB_BS_CHART_DATE_ID";
//$attchart = array();
$isa=0;
$db->query($sql);
$result = $db->query($sql);

if($db->getNumRows($result)>0)
{
while ( list ($average,$month,$year) = $db->fetchQueryRow($result) )
  {
							$tem['month'] = date("M", mktime(0, 0, 0, $month));									  
							$tem['average'] = round($average,0);
	
		$attchart[$isa++]=$tem;
	}
	
}

	$smarty->assign('attchart',$attchart);


// get 5 year average
$sql = "SELECT  
        Y.average_att, Z.fiveyear, Y.chart_month  


 FROM 
        (select AVG(AF_BB_BS_AVG_ATTENDANCE) as average_att,AF_BB_BS_CHART_MONTH as chart_month
from xebura_TI_BOX_OFFICE
where AF_BB_BS_CHART_DATE_ID BETWEEN '133' AND '144'
group by AF_BB_BS_CHART_MONTH)
    Y INNER JOIN 
        (select AVG(AF_BB_BS_AVG_ATTENDANCE) as fiveyear,
        AF_BB_BS_CHART_MONTH as fivemonth
from xebura_TI_BOX_OFFICE
where AF_BB_BS_CHART_DATE_ID BETWEEN '71' AND '132'
group by fivemonth) 
    Z ON Z.fivemonth = Y.chart_month

GROUP BY chart_month
ORDER BY ABS(chart_month) ASC";

$isa=0;
$db->query($sql);
$result = $db->query($sql);

if($db->getNumRows($result)>0)
{
while ( list ($average,$fiveyear,$month) = $db->fetchQueryRow($result) )
  {
							$tem['month'] = date("M", mktime(0, 0, 0, $month,1));		
							
							$tem['average'] = round($average,0);
							$tem['fiveyear'] = round($fiveyear,0);
							$tem['cnt']= $isa+1;
							//echo $fiveyear;
	
		$att5chart[$isa++]=$tem;
	}
	
}

	$smarty->assign('att5chart',$att5chart);

// recently viewed reports

/// Get Report History

$sql = "SELECT RPH.AF_TI_PURCHASE_TIMESTAMP AS DATE, 
RT.AF_TI_REPORT_TYPE_NAME AS TYPE, 
RPH.AF_TI_PURCHASE_REPORT_NAME AS TITLE, 
RT.AF_TI_REPORT_TYPE_CATEGORY AS CATEOGRY, 
RPH.AF_TI_PURCHASE_REPORT_ID AS ID, 
RPH.AF_TI_PURCHASE_REPORT_ID_2 AS ID2, 
RPH.AF_TI_PURCHASE_REPORT_ID_3 AS ID3, 
RPH.AF_TI_PURCHASE_REPORT_ID_4 AS ID4 
FROM xebura_TI_PURCHASE_HISTORY AS RPH INNER JOIN xebura_TI_REPORT_TYPE AS RT ON RPH.AF_TI_PURCHASE_REPORT_TYPE_ID = RT.AF_TI_REPORT_TYPE_ID 
GROUP BY TITLE
ORDER BY AF_TI_PURCHASE_TIMESTAMP DESC
LIMIT 14";	

//echo "<br />";
$recent=array();
$isa=0;
$db->query($sql);
$result_search = $db->query($sql);

if($db->getNumRows($result_search)>0)
{
while ( list ($date,$type,$title,$category,$id,$id2,$id3,$id4) = $db->fetchQueryRow($result_search) )
  {
							$tem['date'] = date('m/d/Y h:i A T',strtotime($date) );									  
							$tem['type'] = $type;
							$tem['title'] = $title;
							
							if($category==1){
							$tem['link'] = "report_artist?artist=".$id;
							}elseif($category==2){
							$tem['link'] = "report_venue?venue=".$id;
							}elseif($category==3){
							$tem['link'] = "compare_artist?chart=avg_gross&artist1=".$id."&artist2=".$id2."&artist3=".$id3."&artist4=".$id4."&end=136&start=1";
							}elseif($category==4){
							$tem['link'] =  "compare_venue?chart=avg_gross&venue1=".$id."&venue2=".$id2."&venue3=".$id3."&venue4=".$id4."&end=136&start=1";
							}elseif($category==5){
							$tem['link'] = "make_pdf?artist=".$id;
							}elseif($category==6){
							$tem['link'] = "make_pdf?venue=".$id;
							}
		//echo "$bandname <br>";
	
		$recent[$isa++]=$tem;
	}
	
}

$smarty->assign("recent", $recent);


// average gross top 20

$sql = "SELECT straight_join  DISTINCT AR.AF_ARTIST_ID,
AR.AF_ARTIST_BANDNAME,
COUNT(AR.AF_ARTIST_ID) AS numreps,
AVG(ABO.AF_BB_BS_AVG_GROSS) as average_gross,
AVG(ABO.AF_BB_BS_AVG_ATTENDANCE) as average_att
FROM xebura_ARTIST AS AR force index(AF_ID_BNAME)
INNER JOIN xebura_TI_BOX_OFFICE AS ABO ON AR.AF_ARTIST_ID = ABO.AF_BB_BS_ARTIST_PRIMARY_ID
WHERE AR.AF_ARTIST_BANDNAME !='' AND ISNULL(ABO.AF_BB_BS_EVENT_TITLE)
AND AR.AF_ARTIST_ID !=0  
AND (ABO.AF_BB_BS_CHART_DATE_ID>='142' AND ABO.AF_BB_BS_CHART_DATE_ID<='145') 
GROUP BY AF_ARTIST_BANDNAME,AR.AF_ARTIST_ID   
HAVING numreps >= '2'
ORDER BY average_gross DESC
LIMIT 20";

$isa=0;
$db->query($sql);
$result = $db->query($sql);

if($db->getNumRows($result)>0)
{
while ( list ($artistid,$bandname,$numreps,$average,$att) = $db->fetchQueryRow($result) )
  {
							$tem['artistid'] = $artistid;									  
							$tem['bandname'] = $bandname;
							$gross = round($average,0);
							$tem['average'] = number_format($gross);
							$tem['att'] = number_format($att);
							$tem['cnt']= $isa+1;
							//echo $fiveyear;
	
		$top20gross[$isa++]=$tem;
	}
	
}

	$smarty->assign('top20gross',$top20gross);


// popular reports

/// Get Report History

$sql = "SELECT count(*) as numviews,
RT.AF_TI_REPORT_TYPE_NAME AS TYPE, 
RPH.AF_TI_PURCHASE_REPORT_NAME AS TITLE, 
RT.AF_TI_REPORT_TYPE_CATEGORY AS CATEOGRY, 
RPH.AF_TI_PURCHASE_REPORT_ID AS ID, 
RPH.AF_TI_PURCHASE_REPORT_ID_2 AS ID2, 
RPH.AF_TI_PURCHASE_REPORT_ID_3 AS ID3, 
RPH.AF_TI_PURCHASE_REPORT_ID_4 AS ID4 
FROM xebura_TI_PURCHASE_HISTORY AS RPH INNER JOIN xebura_TI_REPORT_TYPE AS RT ON RPH.AF_TI_PURCHASE_REPORT_TYPE_ID = RT.AF_TI_REPORT_TYPE_ID 
WHERE RT.AF_TI_REPORT_TYPE_CATEGORY NOT IN(5,6)
GROUP BY ID
ORDER BY numviews DESC
LIMIT 14";	

//echo "<br />";
$popular=array();
$isa=0;
$db->query($sql);
$result_search = $db->query($sql);

if($db->getNumRows($result_search)>0)
{
while ( list ($numviews,$type,$title,$category,$id,$id2,$id3,$id4) = $db->fetchQueryRow($result_search) )
  {
							$tem['date'] = date('m/d/Y h:i A T',strtotime($date) );									  
							$tem['type'] = $type;
							$tem['title'] = $title;
							
							if($category==1){
							$tem['link'] = "report_artist?artist=".$id;
							}elseif($category==2){
							$tem['link'] = "report_venue?venue=".$id;
							}elseif($category==3){
							$tem['link'] = "compare_artist?chart=avg_gross&artist1=".$id."&artist2=".$id2."&artist3=".$id3."&artist4=".$id4."&end=136&start=1";
							}elseif($category==4){
							$tem['link'] =  "compare_venue?chart=avg_gross&venue1=".$id."&venue2=".$id2."&venue3=".$id3."&venue4=".$id4."&end=136&start=1";
							}elseif($category==5){
							$tem['link'] = "make_pdf?artist=".$id;
							}elseif($category==6){
							$tem['link'] = "make_pdf?venue=".$id;
							}
		//echo "$bandname <br>";
	
		$popular[$isa++]=$tem;
	}
	
}

$smarty->assign("popular", $popular);



$smarty->assign("random_quote",$random_quote);

//HEADER MESSAGE
$show_msg = $db->select_single_value("xebura_MEMBERS","MSG_HOME","WHERE MID='".$mid."' ");
$smarty->assign("show_msg",$show_msg);
$firstname = $_SESSION['First_Name'];
$smarty->assign("firstname",$firstname);

$smarty->display('home.tpl');
$smarty->display('search_footer.tpl');
?>
