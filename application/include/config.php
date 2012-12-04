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
define('DB_SERVER','X.rds.amazonaws.com');
define('DB_SERVER_USERNAME','X');
define('DB_SERVER_PASSWORD','X');
define('DB_DATABASE','xe_xebura');
define('SITE_TITLE','http://URL_TO_INSTALL');

define('LANG','ENGLISH');
/* This will keep the user online for 30 seconds if no action is done. */
define('MIN_ONLINE','91');
define('SITE_TITLE','http://'.$_SERVER['HTTP_HOST'].'/');
define('FILE_UPLOAD_PATH','http://'.$_SERVER['HTTP_HOST'].'/uploads/');
$conn=mysql_connect(DB_SERVER,DB_SERVER_USERNAME,DB_SERVER_PASSWORD) or die ('Unable to Connect to Database, Please Try Again');
//$conn=dbx_connect(DBX_MYSQL,DB_SERVER,DB_DATABASE,DB_SERVER_USERNAME,DB_SERVER_PASSWORD) or die ('Unable to Connect to Database, Try Again');
mysql_select_db(DB_DATABASE,$conn) or die ('Unable to Select Database, Try Again');

define('GoogleMapsKey', 'XXXXXXXXXXX'); 

/* Set the error log path. */
define('AF_ERROR_LOG','af_error.log');

/* Set the where clause for active users. */
$active_user_where_clause = " AND ACCOUNT_STATUS ='1' AND LOGIN_STATUS ='1' ";
$active_user_where_clause_am = " AND AM.ACCOUNT_STATUS ='1' AND AM.LOGIN_STATUS ='1' ";

/* Set the smtp. */
ini_set('SMTP', 'localhost');

/* Set the response url for help and support. */
define('SUPPORT_RESPONSE_URL','http://'.$_SERVER['HTTP_HOST'].'/help_support_response.php');

/* Set image magics command path. */
define('IMAGEMAGICKS_COMMAND_PATH', '/usr/bin/convert ');
?>