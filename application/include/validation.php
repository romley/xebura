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
/****************** Common validation functions. *********************/
/* Checks the length of a string. */
function checkLength($val,$max_len)
{
	if(strlen($val) > $max_len)
	{
		return false;
	}
	else
	{
		return true;
	}
}

/* Checks for the numeric value. */
function checkNumeric($val)
{
	if(is_numeric($val) === true)
	{
		return true;
	}
	else
	{
		return false;
	}
}

/* Checks for the numeric value. */
function checkNumericRange($val,$num,$lnum=0)
{
	if($num == 11)
	{
		if(($val <= 2147483647) && ($val > 0))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	else if($num == 5)
	{
		if(($val <= 2147483647) && ($val > 0))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	else if($num == 'double')
	{
		if($lnum == 0)
		{
			if($val <= 999999.99)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else if($lnum == 5)
		{
			if($val <= 999.99)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else if($lnum == 12)
		{
			if($val <= 9999999999.99)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}
}

/* Checks for empty string. */
function checkEmpty($val)
{
	if(!isset($val) || trim($val) == '')
	{
		return true;
	}
	return false;
}

/* Compares the date. */
function compareDate($date1, $date2, $check_equal=0)
{
	if(!checkEmpty($date1) && !checkEmpty($date2))
	{
		$date1Array = explode('/',$date1);
		$date2Array = explode('/',$date2);
		$date1 = $date1Array[2].'-'.$date1Array[0].'-'.$date1Array[1];
		$date2 = $date2Array[2].'-'.$date2Array[0].'-'.$date2Array[1];
		if($check_equal == 1)
		{
			if($date1 <= $date2)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			if($date1 < $date2)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}
}

/* Validate the date field. */
function validateDate($strdate)
{
	$dateValidator = true;
	//Check the length of the entered Date value
	if((strlen($strdate) < 10) OR (strlen($strdate) > 10))
	{
		$dateValidator = _ALRT_DT_FORMAT;
		return $dateValidator;
	}
	else
	{
		//The entered value is checked for proper Date format
		if((substr_count($strdate,"/")) <> 2)
		{
			$dateValidator = _ALRT_DT_FORMAT;
			return $dateValidator;
		}
		else
		{
			$pos=strpos($strdate, "/");
			$date = substr($strdate,($pos+1),($pos));
			$result=ereg("^[0-9]+$", $date, $trashed);

			if(!($result))
			{
				$dateValidator = _ALRT_VALID_DT;
				return $dateValidator;
			}
			else
			{
				if(($date <= 0) OR ($date > 31))
				{
					$dateValidator = _ALRT_VALID_DT;
					return $dateValidator;
				}
			}
			$month = substr($strdate, 0, ($pos));

			if(($month <= 0) OR ($month > 12))
			{
				$dateValidator = _ALRT_VALID_MONTH;
				return $dateValidator;
			}
			else
			{
				$result=ereg("^[0-9]+$", $month, $trashed);
				if(!($result))
				{
					$dateValidator = _ALRT_VALID_MONTH;
					return $dateValidator;
				}
			}
			$year = substr($strdate, ($pos+4), strlen($strdate));
			$result=ereg("^[0-9]+$", $year, $trashed);

			if(!($result))
			{
				$dateValidator = _ALRT_VALID_YEAR;
				return $dateValidator;
			}
			/*else
			{
				if(($year<1900)OR($year>2200))
				{
					echo "Enter a year between 1900-2200";
				}
			}*/
		}
	}
	return $dateValidator;
}

/* checks the hour */
function checkHour($hour)
{
	if(($hour >= 0) && ($hour <= 12))
	{
		return true;
	}
	return false;
}

/* checks the minute */
function checkMinute($min)
{
	if(($min >= 0) && ($min <= 59))
	{
		return true;
	}
	return false;
}

/* checks the merdian */
function checkMerdian($merd)
{
	if(($merd == 'AM') || ($merd == 'PM'))
	{
		return true;
	}
	return false;
}

/* validates url */
function validateURL($webUrl)
{
	$url = htmlspecialchars($webUrl);
	if (preg_match("/^(https?:\/\/+[\w\-]+\.[\w\-]+)/i",$url))
	{
		return true;
	}
	if (preg_match("/^(www?.+[\w\-]+\.[\w\-]+)/i",$url))
	{
		return true;
	}
	return false;
}

/* validates email id. */
function validateEmail($email)
{
	$email = strtolower(stripslashes($email));
	if(preg_match("/^([a-z][a-z0-9\.\_\'\-]*)@([a-z0-9\.\_\'\-]*)\.([a-z][a-z\.]*)$/", $email))
	{
	   return true;
	}
	return false;
}

/* get the mime type for a file type. */
function getMimeType($type)
{
	$allowed_extensions = array('jpg','gif','png','jpeg','JPG','GIF','PNG','JPEG');
	$allowed_file_type = array('image/jpeg','image/pjpeg','image/gif','image/png');
	$header_type = array();
	if(strtolower($type) == 'jpg')
	{
		$header_type[] = 'image/jpeg';
		$header_type[] = 'image/pjpeg';
	}
	else if(strtolower($type) == 'gif')
	{
		$header_type[] = 'image/gif';
	}
	else if(strtolower($type) == 'png')
	{
		$header_type[] = 'image/png';
	}
	else if(strtolower($type) == 'pdf')
	{
		$header_type[] = 'application/pdf';
	}
	else if(strtolower($type) == 'doc')
	{
		$header_type[] = 'application/msword';
	}
	else if(strtolower($type) == 'zip')
	{
		$header_type[] = 'application/x-compressed';
		$header_type[] = 'application/x-zip-compressed';
		$header_type[] = 'application/zip';
		$header_type[] = 'multipart/x-zip';
	}
	return $header_type;
}

/* validates the Image. */
function validateImage($file, $field_name, $allowed_extensions)
{
	//$find_characters = array('!','@','#','$','%','^','&','*','(',')','+','=','[',']','\\','\'',';','/','{','}','|','"',':','<','>','?');
	$find_characters = array('\\','/',':','*','?','"','<','>','|');
	$max_size = ini_get('upload_max_filesize');
	//print_r($file);
	//exit;

	if(is_uploaded_file($file['tmp_name']))
	{
		$filePath = $file['tmp_name'];
		$contentType = $file['type'];
	}
	elseif($file['tmp_name'] != "")
	{
		$msg = str_replace('%field%',$field_name,_ALRT_FILE_ERROR);
		return $msg;
	}

	$find_dot_pos = strrpos($file['name'],'.');
	$fname = substr($file['name'],0,$find_dot_pos);

	foreach($find_characters as $key => $val)
	{
		if (strpos($fname, $val) !== False)
		{
		   return _ALRT_FILE_NAME_ERROR;
		}
	}

	if(strtolower(substr($max_size,-1)) == 'm')
	{
		$max_size = substr($max_size,0,strlen($max_size)-1);
	}
	$max_size = $max_size * 1000 * 1024;
	if(($file['size'] == 0) || $file['size'] > $max_size)
	{
		$msg = str_replace('%field%',$field_name,_ALRT_CHECK_MAXSIZE);
		return $msg;
	}

	if(!checkLength($file['name'],150))
	{
	  $msg = str_replace('%field%',$field_name,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if (! preg_match('#\.(.+)$#', $file['name'], $matches))
	{
		return _ALRT_FILE_EXT_ERROR;
	}
	else if (! in_array(strtolower($matches[1]), $allowed_extensions))
	{
		return _ALRT_IMAGE_TYPE_ERROR;
	}
	$allowed_file_type = getMimeType($matches[1]);
	if((count($allowed_file_type) == 0) || (! in_array($contentType, $allowed_file_type)))
	{
		return _ALRT_FILE_UPLOAD_ERROR;
	}
	return true;
}

/* validates the download files. */
function validateDownloadFiles($file, $field_name, $allowed_extensions)
{
	//print_r($file1);
	//exit;

	//$find_characters = array('!','@','#','$','%','^','&','*','(',')','+','=','[',']','\\','\'',';','/','{','}','|','"',':','<','>','?');
	$find_characters = array('\\','/',':','*','?','"','<','>','|');
	$allowed_extensions = array('aac', 'aif', 'iff', 'm3u', 'mid', 'midi', 'mp3', 'mpa', 'ra', 'ram', 'wav', 'wma', '3gp'
		, 'asf', 'asx', 'avi', 'mov', 'mp4', 'mpg', 'qt', 'rm', 'swf', 'wmv');
	$max_size = ini_get('upload_max_filesize');

	/*if(is_uploaded_file($file['tmp_name']))
	{
		$filePath = $file['tmp_name'];
		$contentType = $file['type'];
	}
	else if($file['tmp_name'] != "")
	{
		$msg = str_replace('%field%',$field_name,_ALRT_FILE_ERROR);
		return $msg;
	}*/
	$filePath = $file['tmp_name'];
	$contentType = strtolower($file['type']);

	$find_dot_pos = strrpos($file['name'],'.');
	$fname = substr($file['name'],0,$find_dot_pos);

	foreach($find_characters as $key => $val)
	{
		if(strpos($fname, $val) !== False)
		{
		   return _ALRT_FILE_NAME_ERROR;
		}
	}

	if(strtolower(substr($max_size,-1)) == 'm')
	{
		$max_size = substr($max_size,0,strlen($max_size)-1);
	}
	$max_size = $max_size * 1000 * 1024;
	if(($file['size'] == 0) || $file['size'] > $max_size)
	{
		$msg = str_replace('%field%',$field_name,_ALRT_CHECK_MAXSIZE);
		return $msg;
	}

	if(!checkLength($file['name'],50))
	{
	  $msg = str_replace('%field%',$field_name,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if (! preg_match('#\.(.+)$#', $file['name'], $matches))
	{
		return _ALRT_FILE_EXT_ERROR;
	}
	else if (in_array(strtolower($matches[1]), $allowed_extensions))
	{
		return _ALRT_DOWNLOAD_TYPE_ERROR;
	}
	
	if(strpos('audio',$contentType) || strpos('video',$contentType))
	{
		return _ALRT_FILE_UPLOAD_ERROR;
	}
	return true;
}

/* validates year. */
function checkValidYear($year)
{
	$result=ereg("^[1-9][0-9]+$", $year, $trashed);

	if($result && $year <= date('Y') && strlen(trim($year)) == 4)
	{
		return true;
	}
	return false;
}


/* validate phone number. */
function validatePhone($phone)
{
	/*if(preg_match("/^[0-9]([0-9\-])+$/", $phone))
	{
		return true;
	}
	return false;*/
	return true;
}

/* validate fax number. */
function validateFax($fax)
{
	if(preg_match("/^[0-9]([0-9\-])+$/", $fax))
	{
		return true;
	}
	return false;
}

/* validate zip. */
function validateZip($zip)
{
	/*if(preg_match("/^[0-9]([0-9\-])+$/", $zip))
	{
		return true;
	}
	return false;*/
	return true;
}
/*****************************************************************************************/



?>
