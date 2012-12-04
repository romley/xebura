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
// script to get links, add them to the link table and replace them
require 'class/dbclass.php';
include_once 'include/config.php';
date_default_timezone_set('America/Los_Angeles');
$db = new mysqldb();
//$cid = '7';
//$mid = '1';
//$eid = '1';
$now = date('Y-m-d G:i:s',(strtotime("now")));
// should be dynamic, what happened here? jlr 19-08-12 -- needs to be fixed.
//$msg_html = '<p>Here is my test message. I am going to show you some links....</p>
//<p>Here is <a href="http://www.google.com/">Google</a></p>
//<p>and <a href="http://yahoo.com">Yahoo</a></p>
//<p>and this is an email address - <a href="mailto:hello@xebura.com">hello@xebura.com</a></p>
//<p>and a link to a <a href="http://www.seagate.com/content/pdf/whitepaper/D2c_tech_paper_intc-stx_sata_ncq.pdf">PDF</a>... </p>';


// URL REPLACEMENT PART 1
//extract all a tag href= urls to an array
$var = $msg_html;
            
    preg_match_all ("/a[\s]+[^>]*?href[\s]?=[\s\"\']+".
                    "(.*?)[\"\']+.*?>"."([^<]+|.*?)?<\/a>/", 
                    $var, &$matches);
        
    $matches = $matches[1];
	$list = array();
	$newlinks=array();
	
//loop over array to insert in to redirect table and use regex to replace URLs
    foreach($matches as $var)
    {    
     
		// insert var to db and get id number
		$values = array(XE_LINK_CAMPAIGN_ID => $cid, XE_LINK_MID => $mid, XE_LINK_URL => $var, XE_LINK_ADDED => $now);
		$urlid = $db->insert("XEBURA_LINK",$values);
		$rlink = "http://s1.xebura.com/link?l=".$urlid;
		
		// let's store these all in an array we can call later
							$tem[] = $rlink;								  
		$newlinks=$tem;
		
    }
// END URL REPLACEMENT PART 1

//  START URL REPLACEMENT PART 2 - APPEND EMAIL IDS WITHOUT DB QUERY

//loop over array to append the Email ID
    foreach($newlinks as $var)
    {    

		$rlink = $var."&i=".$eid;
		//echo $rlink;
		
		// let's put these all in to an array
			$etem[] = $rlink;								  
		$elinks=$etem;
		print_r($elinks);
    }
// this replaces links but still need to append emails
	$final_html = str_replace($matches,$elinks,$msg_html);
// END URL REPLACEMENT PART 2
	print $final_html;
		
?>
