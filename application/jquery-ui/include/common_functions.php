<?PHP
function dateformate($date){
	$dt=explode("/",$date);
	$date=$dt[2]."-".$dt[0]."-".$dt[1];
	return $date;
}

function Date_Db_Page($date){
	$dt=explode("-",$date);
	$date=$dt[1]."/".$dt[2]."/".$dt[0];
	return $date;
}

function condateformat($date){
	$dt=explode("-",$date);
	$date=$dt[1]."/".$dt[2]."/".$dt[0];
	return $date;
}

function getYearMonthDate($str,$expSeparator='-',$separator='/'){
	if((trim($str) != '') && ($str != null)) {
		$date=explode($expSeparator,$str);
		return $date[1].$separator.$date[2].$separator.$date[0];
	}
	else {
		return '';
	}
}

function getYearMonthDateSearch($str,$expSeparator='-',$separator='/'){
	if((trim($str) != '') && ($str != null)) {
		$date=explode($expSeparator,$str);
		return $date[2].$separator.$date[0].$separator.$date[1];
	}
	else {
		return '';
	}
}

function getGreeting() {
	$hour = date('H');	
	$min = date('i');
	
	if(($hour < 12)) {
		return 'Good Morning';
	}
	else if((($hour >= 12) && ($hour < 16)) || (($hour == 16) && ($min == 0))) {
		return 'Good Afternoon';
	}
	else {
		return 'Good Evening';
	}
}

function getMonthDateYear($str)
{
	return explode("/",$str);
}

function encode($originalStr)
{
  $encodedStr = $originalStr;
  $num = mt_rand(1,6);
  for($i=1;$i<=$num;$i++)
  {
  	$encodedStr = base64_encode($encodedStr);
  }
  $seed_array = array('A','R','T','I','S','F','O','C','E');
 
  $encodedStr = $encodedStr . "+" . $seed_array[$num];
  $encodedStr = base64_encode($encodedStr);
  return $encodedStr;
}
 
function decode($decodedStr)
{
  $seed_array = array('A','R','T','I','S','F','O','C','E');
  //$seed_array = array('S','H','A','F','I','Q');
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

function monthStr($val) {
	switch($val){
		case 01:
		case 1:
			$month="Jan";
			return $month;
			break;		
		case 02:
		case 2:
			$month="Feb";
			return $month;
			break;		
		case 03:
		case 3:
			$month="Mar";
			return $month;
			break;			
		case 04:
		case 4:
			$month="Apr";
			return $month;
			break;			
		case 05:
		case 5:
			$month="May";
			return $month;
			break;			
		case 06:
		case 6:
			$month="Jun";
			return $month;
			break;			
		case 07:
		case 7:
			$month="Jul";
			return $month;
			break;			
		case 08:
		case 8:
			$month="Aug";
			return $month;
			break;			
		case 09:
		case 9:
			$month="Sep";
			return $month;
			break;			
		case 10:		
			$month="Oct";
			return $month;
			break;			
		case 11:
			$month="Nov";
			return $month;
			break;
		case 12:
			$month="Dec";
			return $month;
			break;
		default:	
			break;
	}
}

function descRelease($buttons)
{
	$desc_release = '';
	if(is_array($buttons))
	{
		if($_SESSION['Account_Id'] == '2' && in_array('3', $buttons))
		{
			$desc_release = _LBL_RL_TO_ARTIST;
		}
		else if($_SESSION['Account_Id'] == '2' && in_array('AMA', $buttons))
		{
			$desc_release = _LBL_RL_TO_AGENT;
		}
		else if($_SESSION['Account_Id'] == '3' && in_array('4', $buttons))
		{
			$desc_release = _LBL_RL_TO_MANAGER;
		}
		else if($_SESSION['Account_Id'] == '3' && in_array('3', $buttons))
		{
			$desc_release = _LBL_RL_TO_ARTIST;
		}
		else if($_SESSION['Account_Id'] == '3' && in_array('6', $buttons))
		{
			$desc_release = _LBL_RL_TO_VENUE;
		}
		else if($_SESSION['Account_Id'] == '3' && in_array('15', $buttons))
		{
			$desc_release = _LBL_RL_TO_BUYER;
		}
		else if($_SESSION['Account_Id'] == '3' && in_array('17', $buttons))
		{
			$desc_release = _LBL_RL_TO_PROMOTER;
		}
		else
		{
			$desc_release = _BTN_RELEASE;
		}
	}
	return $desc_release;
}

function descMessage($buttons)
{
	$desc_message = '';
	if(is_array($buttons))
	{
		if(in_array('7', $buttons))
		{
			$desc_message = _LBL_MS_TO_VENUE;
		}
		else if(in_array('16', $buttons))
		{
			$desc_message = _LBL_MS_TO_BUYER;
		}
		else if(in_array('18', $buttons))
		{
			$desc_message = _LBL_MS_TO_PROMOTER;
		}
		else if(in_array('8', $buttons))
		{
			$desc_message = _LBL_MS_TO_AGENT;
		}
		else if(in_array('9', $buttons))
		{
			$desc_message = _LBL_MS_TO_MANAGER;
		}
		else if(in_array('10', $buttons))
		{
			$desc_message = _LBL_MS_TO_ARTIST;
		}
		else
		{
			$desc_message = _LBL_MSG;
		}
	}
	return $desc_message;
}

function member_type($mem_id)
{
$member_type=array("Artist","Manager","Agent","Agency","Record Label","Promoter","Venue","Buyer");
switch ($mem_id) {
case 1:
   return "Artist";
   break;
case 2:
   return "Manager";
   break;
case 3:
   return "Agent";
   break;
case 4:
   return "Agency";
   break;
case 5:
   return "R & L";
   break;
case 6:
   return "Promoter";
   break;
case 7:
   return "Venue";
   break;
case 8:
   return "Buyer";
   break;
   }
}

function truncate($string, $length = 80, $etc = '...', $break_words = false, $middle = false) 
{
    if ($length == 0)
        return '';

    if (strlen($string) > $length) 
    {
        $length -= strlen($etc);
        if (!$break_words && !$middle) 
        {
            $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
        }
        if(!$middle) 
        {
            return substr($string, 0, $length).$etc;
        } 
        else 
        {
            return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
        }
    } 
    else 
    {
        return $string;
    }
}

/* This function will return the time in a format. */
function get_time_format($timestamp, $format = 'h:i A')
{
	if($timestamp != '')
	{
		$time_arr = explode(':', $timestamp);
		return date($format, mktime($time_arr[0],$time_arr[1],$time_arr[2],0,0,0));
	}
}
/* This function will return the date in a format. */
function get_date_format($date)
{
	if($date != '')
	{
		$date_arr = explode('-', $date);
		return monthStr($date_arr[1]).' '.$date_arr[2].', '.$date_arr[0];
	}
}
function html_script_escape($string)
{
	return strip_tags($string);
}

// $time is in second.
function convertToHHMMSS($seconds)
{
	$hoursPerDay = 24;
	$secondsPerHour = 3600;
	$secondsPerMinute = 60;
	$minutesPerHour = 60;
	$return_str = '';
	
	$hh = intval($seconds / $secondsPerHour);
	$mm = intval($seconds / $secondsPerMinute) % $minutesPerHour;
	$ss = $seconds % $secondsPerMinute;
	if($hh == 0)
	{
		$return_str = $mm.":".$ss;
	}
	else
	{
		$return_str = $hh.":".$mm.":".$ss;
	}
	return $return_str;
}

function escapeSpecialCharInXml($str)
{
	return html_entity_decode($str,ENT_COMPAT,'ISO-8859-1');
}

function truncate_string($string, $count_words=3, $etc = '...', $separator='')
{
	if ($count_words == 0)
       return '';     
  $strarray = explode(',', $string);
  $ret_string ='';
  
  for($i =0; ($i < $count_words && $i<count($strarray)); $i++)
  {
  	if($ret_string != '')
  		$ret_string .= ', '.$strarray[$i];
  	else
  		$ret_string .= $strarray[$i];
  }
  return $ret_string;
}

function format_size($size, $round = 0) 
{
    //Size must be bytes!
    $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    for ($i=0; $size > 1024 && isset($sizes[$i+1]); $i++) $size /= 1024;
    return round($size,$round).$sizes[$i];
}

function convert_amounttype($type) 
{
	$type = trim($type);
  if($type == 'eu')
  	return '&euro;';
  else if($type == 'ye')
  	return '&yen;';
  else if($type == 'po')
  	return '&pound;';
  else
  	return $type;
}

function escape_quotes($string)
{
	$string = str_replace("'", "\\'", htmlspecialchars($string));
	//$string = str_replace('"', '\\"', $string);
	return $string;
}
?>