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
// get the HTML contents of a campaign and display in browser

require 'class/dbclass.php';
include_once 'include/config.php';

 
function decode($decodedStr)
{
  $seed_array = array('Z','E','B','U','R','A','C','A','T');
  $decoded =  base64_decode($decodedStr);
  list($decoded,$letter) =  split("\+",$decoded);
  for($i=0;$i<count($seed_array);$i++)
  {
    if($seed_array[$i] == $letter)
    break;
  }
  for($j=1;$j<=$i;$j++)
  {
  	$decoded = base64_decode($decoded);
  }
 	return $decoded;
}

$fname = "";
$db = new mysqldb();
$cid = decode($_REQUEST['c']);
$html=$db->select_single_value("XEBURA_MESSAGE","XE_MSG_TEMPLATE_HTML","WHERE XE_MSG_CAMPAIGN_ID='".$cid."'");
$final_html = str_replace('{FNAME}',$fname,$html);
$title=$db->select_single_value("XEBURA_MESSAGE","XE_MSG_SUBJECT","WHERE XE_MSG_CAMPAIGN_ID='".$cid."'");


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?PHP echo $title;?></title>
</head>
<body>
<?PHP echo $final_html;?>
</body>
</html>
