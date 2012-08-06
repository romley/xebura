<?php
define('DB_SERVER','xebura.ch0ppoubu49s.us-east-1.rds.amazonaws.com');
define('DB_SERVER_USERNAME','jonathan');
define('DB_SERVER_PASSWORD','robertson256');
define('DB_DATABASE','xe_xebura');
define('SITE_TITLE','http://s1.xebura.com/');

define('LANG','ENGLISH');
/* This will keep the user online for 30 seconds if no action is done. */
define('MIN_ONLINE','91');
define('SITE_TITLE','http://'.$_SERVER['HTTP_HOST'].'/');
define('FILE_UPLOAD_PATH','http://'.$_SERVER['HTTP_HOST'].'/uploads/');
$conn=mysql_connect(DB_SERVER,DB_SERVER_USERNAME,DB_SERVER_PASSWORD) or die ('Unable to Connect to xebura, Please Try Again');
//$conn=dbx_connect(DBX_MYSQL,DB_SERVER,DB_DATABASE,DB_SERVER_USERNAME,DB_SERVER_PASSWORD) or die ('Unable to Connect to Database, Try Again');
mysql_select_db(DB_DATABASE,$conn) or die ('Unable to Select Database, Try Again');

if($_SERVER['HTTP_HOST'] == 'app.tourintel.com')
{	
  define('GoogleMapsKey', 'ABQIAAAAn1adbQGK7a3oU1d9RrLkyRRK5sniErddLvynwEQI93Gv7VJRQRRmA482GYQ48mGRmMIJK_rvOJGS0w'); 
}
elseif($_SERVER['HTTP_HOST'] == 'af2.xebura.com')
{	
  define('GoogleMapsKey', 'ABQIAAAAn1adbQGK7a3oU1d9RrLkyRSuUCWXyEJq9IrPJnlmiqJJ90sokBTdTcgXAC-MTxd-m4BFoyiGecLTog'); 
} elseif ($_SERVER['HTTP_HOST'] == 'af') {
  define(GoogleMapsKey, 'ABQIAAAAXLf_YOUDq5xQVyVtAdQvFxR-ehqn7t_JW3x3AH_tDeNGiWJTOBT4EU9B8C6vz2VpHNg-ciuKp8XMZg'); 
} else {	
  define(GoogleMapsKey, 'ABQIAAAAn1adbQGK7a3oU1d9RrLkyRTvnk_rv3GviJ3CH_4eCphy8zwDSRSwmfqNPe4XCuqKYPKPZFT9jNZEqA'); 
}
// Default Booking xml path.
$bookingxml_path = "bookingxml/BookingStatus.xml";
define('BOOKING_URL','http://stagejava.xebura.com/xebura2/LoginAction.do');
define('DOCUMENTS_URL','http://stagejava.xebura.com/xebura2/LoginAction.do');
//define('BOOKING_URL','https://af1.xebura.com/xebura2/LoginAction.do');
//define('DOCUMENTS_URL','https://af1.xebura.com/xebura2/LoginAction.do');

/* Set the error log path. */
define('AF_ERROR_LOG','af_error.log');

/* Set the where clause for active users. */
$active_user_where_clause = " AND ACCOUNT_STATUS ='1' AND LOGIN_STATUS ='1' ";
$active_user_where_clause_am = " AND AM.ACCOUNT_STATUS ='1' AND AM.LOGIN_STATUS ='1' ";

/* Set lower fee and higher fee for artist. */
define('ARTIST_LOWER_FEE',250);
define('ARTIST_HIGHER_FEE',2000);

/* Set the smtp. */
ini_set('SMTP', 'localhost');

/* Set the response url for help and support. */
define('SUPPORT_RESPONSE_URL','http://'.$_SERVER['HTTP_HOST'].'/help_support_response.php');

/* Set image magics command path. */
define('IMAGEMAGICKS_COMMAND_PATH', '/usr/bin/convert ');
?>