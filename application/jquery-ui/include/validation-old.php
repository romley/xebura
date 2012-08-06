<?PHP
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

/****************** Validation functions for avail inquiry offer. *********************/
function validateAvailInq($form,$action='add')
{
	global $db;
	$msg = '';
	/* Required field check. */
	if(checkEmpty($form['artist']))
	{
		$msg = _ALRT_SEL_ARTIST;
		return $msg;
	}

	if($action != 'edit' && checkEmpty($form['VENUE_ID']))
	{
		$msg = _ALRT_SEL_VENUE;
		return $msg;
	}

	if(checkEmpty($form['showdate']))
	{
		$msg = _ALRT_SEL_SHOWDT;
		return $msg;
	}
	else
	{
		$showdate = validateDate($form['showdate']);
		if($showdate !== true)
		{
			$msg = $showdate.' '._LBL_FOR.' '._LBL_SHOW_DATE;;
			return $msg;
		}
	}

	if(checkEmpty($form['expirydate']))
	{
		$msg = _ALRT_OFFER_EXP_DT;
		return $msg;
	}
	else
	{
		$showdate = validateDate($form['expirydate']);
		if($showdate !== true)
		{
			$msg = $showdate.' '._LBL_FOR.' '._LBL_OFFER_EXP_DT;
			return $msg;
		}
	}

	/*if(checkEmpty($form['textfield47']))
	{
		$msg = _ALRT_OFFER_EXP_DT;
		return $msg;
	}
	else
	{
		$showdate = validateDate($form['textfield47']);
		if($showdate !== true)
		{
			$msg = $showdate.' '._LBL_FOR.' '._LBL_OFFER_ADDED_ON;
			return $msg;
		}
	}*/

	/* Compare the offer added date, show date and the expiration date. */
	if(compareDate($form['expirydate'], date('m/d/Y')))
	{
		$msg = _ALRT_VALID_EXPDATE;
		return $msg;
	}
	if(compareDate($form['showdate'],$form['expirydate']))
	{
		$msg = _ALRT_VALID_SHOWDATE;
		return $msg;
	}

	/*if($action != 'edit')
	{
		if(compareDate($form['textfield47'], date('m/d/Y')))
		{
			$msg = _ALRT_VALID_ADDDATE;
			return $msg;
		}
	}*/

	/* Checks the length of the fields. */
	if(!checkLength($form['artist'],255))
	{
		$msg = str_replace('%field%',_LBL_ARTIST_OR_BAND,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if(!checkLength($form['showtitle'],255))
	{
		$msg = str_replace('%field%',_LBL_SHOW_TITLE,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if(!checkLength($form['venuename'],255))
	{
		$msg = str_replace('%field%',_VENUE.' '._LBL_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if(!checkLength($form['venuelocation'],255))
	{
		$msg = str_replace('%field%',_VENUE.' '._LBL_LOCATION,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if((!checkEmpty($form['offeramount'])) && !checkNumeric($form['offeramount']))
	{
		$msg = str_replace('%field%',_LBL_OFFER_AMT,_ALRT_CHECK_NUMERIC);
		return $msg;
	}

	if((!checkEmpty($form['offeramount'])) && !checkNumericRange($form['offeramount'],'double'))
	{
		$msg = str_replace('%field%',_LBL_OFFER_AMT,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if((!checkEmpty($form['offeramount'])) && !checkNumeric($form['offeramount']))
	{
		$msg = str_replace('%field%',_LBL_OFFER_AMT,_ALRT_CHECK_NUMERIC);
		return $msg;
	}

	return true;
}
/*****************************************************************************************/

/****************** Validation functions for standard offer. *********************/
function validateStandard($form,$action='add')
{
	$msg = '';

	/* Required field check. */
	if(checkEmpty($form['artistId']))
	{
		$msg = _ALRT_SEL_ARTIST;
		return $msg;
	}
	if(checkEmpty($form['artist']))
	{
		$msg = _ALRT_SEL_ARTIST;
		return $msg;
	}

  if(checkEmpty($form['offeramount']))
	{
		$msg = _ALRT_OFFER_AMT;
		return $msg;
	}

	if(checkEmpty($form['offertype']))
	{
		$msg = _ALRT_SEL_OFFER_TYPE;
		return $msg;
	}

	if((trim($form['offertype']) == 'split') && checkEmpty($form['splitguarantee']))
	{
		$msg = _ALRT_SEL_SHARE_AMT;
		return $msg;
	}

	if(checkEmpty($form['depositamount']))
	{
		$msg = _ALRT_ENT_DEP_AMT;
		return $msg;
	}
	if(checkEmpty($form['transferby']))
	{
		$msg = _ALRT_ENT_TRANS_MODE;
		return $msg;
	}

	if((trim($form['transferby']) == 'Other') && checkEmpty($form['depositother']))
	{
		$msg = _ALRT_ENT_OTHER_TRANS;
		return $msg;
	}
	if(checkEmpty($form['nolaterthan']))
	{
		$msg = _ALRT_LASTDT_DEPO;
		return $msg;
	}

	if($action != 'edit' && checkEmpty($form['VENUE_ID']))
	{
		$msg = _ALRT_SEL_VENUE;
		return $msg;
	}

	if(checkEmpty($form['venuename']))
	{
		$msg = _ALRT_SEL_VENUE;
		return $msg;
	}
	if(checkEmpty($form['numshows']))
	{
		$msg = _ALRT_NUM_OF_SHOWS;
		return $msg;
	}

	if(checkEmpty($form['showdate']))
	{
		$msg = _ALRT_SEL_SHOWDT;
		return $msg;
	}
	else
	{
		$showdate = validateDate($form['showdate']);
		if($showdate !== true)
		{
			$msg = $showdate.' '._LBL_FOR.' '._LBL_SHOW_DATE;;
			return $msg;
		}
	}

	if(checkEmpty($form['expirydate']))
	{
		$msg = _ALRT_OFFER_EXP_DT;
		return $msg;
	}
	else
	{
		$showdate = validateDate($form['expirydate']);
		if($showdate !== true)
		{
			$msg = $showdate.' '._LBL_FOR.' '._LBL_OFFER_EXP_DT;
			return $msg;
		}
	}

	if(checkEmpty($form['evtdescription']))
	{
		$msg = _ALRT_DESC_EVENT;
		return $msg;
	}

  if(checkEmpty($form['loctype']))
	{
		$msg = _ALRT_LOC_TYPE;
		return $msg;
	}
	if(($form['loctype'] == 'outdoor') && checkEmpty($form['outdoorcov']))
	{
		$msg = _ALRT_STAGE_COV;
		return $msg;
	}
	if(checkEmpty($form['rainshine']))
	{
		$msg = _ALRT_RAIN_SHINE;
		return $msg;
	}
	if(checkEmpty($form['allage']))
	{
		$msg = _ALRT_AGE_RESTRICTION;
		return $msg;
	}
	if(($form['allage'] == 'no') && checkEmpty($form['agerestrict']))
	{
		$msg = _ALRT_SEL_RES_AGE;
		return $msg;
	}
	if(($form['agerestrict'] == 'others') && checkEmpty($form['otherage']))
	{
		$msg = _ALRT_SEL_ORES_AGE;
		return $msg;
	}
  
	if(checkEmpty($form['lineup']))
	{
		$msg = _ALRT_SEL_DET_LINEUP;
		return $msg;
	}

	/* Compare the offer added date, show date and the expiration date. */
	if(compareDate($form['expirydate'], date('m/d/Y')))
	{
		$msg = _ALRT_VALID_EXPDATE;
		return $msg;
	}
	if(compareDate($form['showdate'],$form['expirydate']))
	{
		$msg = _ALRT_VALID_SHOWDATE;
		return $msg;
	}

	/* Checks the length of the fields. */
	if(!checkLength($form['artist'],200))
	{
		$msg = str_replace('%field%',_ARTIST,_ALRT_CHECK_LENGTH);
		return $msg;
	}
  
  if((!checkEmpty($form['offeramount'])) && !checkNumeric($form['offeramount']))
	{
		$msg = str_replace('%field%',_LBL_OFFER_AMT,_ALRT_CHECK_NUMERIC);			
		return $msg;
	}
	if((!checkEmpty($form['offeramount'])) && !checkNumericRange($form['offeramount'],'double'))
	{
		$msg = str_replace('%field%',_LBL_OFFER_AMT,_ALRT_CHECK_LENGTH);	
		return $msg;	
	}
	if((!checkEmpty($form['offertype'])) && !checkLength($form['offertype'],30))
	{
		$msg = str_replace('%field%',_LBL_OFFER_AMT.' '._LBL_TYPE,_ALRT_CHECK_LENGTH);	
		return $msg;	
	}
	if((!checkEmpty($form['splitguarantee'])) && !checkLength($form['splitguarantee'],30))
	{
		$msg = str_replace('%field%',_LBL_GUARANTEE_VS.' '._LBL_TYPE,_ALRT_CHECK_LENGTH);	
		return $msg;	
	}
	if((!checkEmpty($form['depositamount'])) && !checkNumeric($form['depositamount']))
	{
		$msg = str_replace('%field%',_LBL_DEPO_AMT,_ALRT_CHECK_NUMERIC);			
		return $msg;
	}
	if((!checkEmpty($form['depositamount'])) && !checkNumericRange($form['depositamount'],'double'))
	{
		$msg = str_replace('%field%',_LBL_DEPO_AMT,_ALRT_CHECK_LENGTH);	
		return $msg;	
	}
	if((!checkEmpty($form['transferby'])) && !checkLength($form['transferby'],30))
	{
		$msg = str_replace('%field%',_LBL_DEPO_AMT.' '._LBL_BY,_ALRT_CHECK_LENGTH);	
		return $msg;	
	}
	if((!checkEmpty($form['depositother'])) && !checkLength($form['depositother'],80))
	{
		$msg = str_replace('%field%',_LBL_OTH_MODE,_ALRT_CHECK_LENGTH);	
		return $msg;	
	}
	
	if(!checkEmpty($form['nolongerthan']))
	{
		$nolongerthan = validateDate($form['nolongerthan']);
		
		if($nolongerthan !== true)
		{
			$msg = $nolongerthan.' '._LBL_FOR.' '._LBL_NO_LATER_THAN;;
			return $msg;
		}
	}
  
  // no longer needed to pull from DB
  $added_date = date('m/d/Y');
	
	if(compareDate($added_date, $form['nolongerthan']))
	{
		$msg = str_replace('%field%',_LBL_NO_LATER_THAN,_ALRT_VALID_PAYMENT_DATE);	
		return $msg;
	}
	if(compareDate($form['showdate'],$form['nolongerthan']))
	{
		$msg = str_replace('%field%',_LBL_NO_LATER_THAN,_ALRT_VALID_PAYMENT_DATE);	
		return $msg;
	}	

	if(!checkLength($form['showtitle'],80))
	{
		$msg = str_replace('%field%',_LBL_SHOW_TITLE,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if(!checkLength($form['venuename'],100))
	{
		$msg = str_replace('%field%',_VENUE.' '._LBL_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
  
  if(!checkLength($form['eventtype'],20))
	{
		$msg = str_replace('%field%',_LBL_EVENT_TYPE,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if((!checkEmpty($form['perftimeh'])) && !checkHour($form['perftimeh']))
	{
		$msg = str_replace('%field%',_LBL_PERF_TIME.' '._LBL_HH,_ALRT_CHECK_DATA);
		return $msg;
	}
	if((!checkEmpty($form['perftimem'])) && !checkMinute($form['perftimem']))
	{
		$msg = str_replace('%field%',_LBL_PERF_TIME.' '._LBL_MM,_ALRT_CHECK_DATA);
		return $msg;
	}
	if((!checkEmpty($form['perftime24'])) && !checkMinute($form['perftime24']))
	{
		$msg = str_replace('%field%',_LBL_PERF_TIME.' '._LBL_MERIDIAN,_ALRT_CHECK_DATA);
		return $msg;
	}

	if((!checkEmpty($form['doortimeh'])) && !checkHour($form['doortimeh']))
	{
		$msg = str_replace('%field%',_LBL_DOOR_TIME.' '._LBL_HH,_ALRT_CHECK_DATA);
		return $msg;
	}
	if((!checkEmpty($form['doortimem'])) && !checkMinute($form['doortimem']))
	{
		$msg = str_replace('%field%',_LBL_DOOR_TIME.' '._LBL_MM,_ALRT_CHECK_DATA);
		return $msg;
	}
	if((!checkEmpty($form['doortime24'])) && !checkMinute($form['doortime24']))
	{
		$msg = str_replace('%field%',_LBL_DOOR_TIME.' '._LBL_MERIDIAN,_ALRT_CHECK_DATA);
		return $msg;
	}
	if((!checkEmpty($form['perfdurh'])) && !checkHour($form['perfdurh']))
	{
		$msg = str_replace('%field%',_LBL_PERF_DUR.' '._LBL_HH,_ALRT_CHECK_DATA);
		return $msg;
	}
	if((!checkEmpty($form['perfdurm'])) && !checkMinute($form['perfdurm']))
	{
		$msg = str_replace('%field%',_LBL_PERF_DUR.' '._LBL_MM,_ALRT_CHECK_DATA);
		return $msg;
	}
  
	if(!checkNumeric($form['numshows']))
	{
		$msg = str_replace('%field%',_LBL_NO_SHOW,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
  
  if(!checkNumericRange($form['numshows'],11))
	{
		$msg = str_replace('%field%',_LBL_NO_SHOW,_ALRT_CHECK_LENGTH);
		return $msg;
	}
  
  if(!checkLength($form['otheracts'],50))
	{
		$msg = str_replace('%field%',_LBL_OTHER_ACT_SHOW.' '._LBL_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
  
  if((!checkEmpty($form['lineup'])) && !checkLength($form['lineup'],30))
	{
		$msg = str_replace('%field%',_LBL_SHOW_LINEUP,_ALRT_CHECK_LENGTH);
		return $msg;
	}
  
  if(!checkEmpty($form['adbreakdate']))
	{
		$advdate = validateDate($form['adbreakdate']);
		if($advdate !== true)
		{
			$msg = $advdate.' '._LBL_FOR.' '._LBL_ADV_BRK_DT;
			return $msg;
		}
	}
	
	if(!checkEmpty($form['airprovided']) &&(checkEmpty($form['airec']) && checkEmpty($form['airbc'])&& checkEmpty($form['airfc'])))
	{
		$msg = _ALRT_AIR_TYPE;
		return $msg;
	}
	
	if(!checkEmpty($form['groundprovided']) &&(checkEmpty($form['groundairport']) && checkEmpty($form['groundhotel'])&& 
		checkEmpty($form['groundvenue']) && checkEmpty($form['groundasdirected'])))
	{
		$msg = _ALRT_GROUND_TYPE;
		return $msg;
	}
	
	if(!checkEmpty($form['hotelprovided']) &&(checkEmpty($form['hotelclass_sel'])))
	{
		$msg = _ALRT_HOTEL_TYPE;
		return $msg;
	}
	if((!checkEmpty($form['hotelclass_sel']) && (trim($form['hotelclass_sel']) != 'Not Req'))
		&&(checkEmpty($form['hotelsingle']) && checkEmpty($form['hoteldouble']) && checkEmpty($form['hotelsuite'])))
	{
		$msg = _ALRT_HOTEL_ROOM_TYPE;
		return $msg;
	}
	
	if(!checkEmpty($form['mealsprovided']) &&(checkEmpty($form['mealrider']) 
		&& checkEmpty($form['mealbuyout'])))
	{
		$msg = _ALRT_MEAL_TYPE;
		return $msg;
	}
	
	if(!checkEmpty($form['other1name']) &&(checkEmpty($form['other1'])))
	{
		$msg = _ALRT_OTHER_TYPE;
		return $msg;
	}
	if(!checkEmpty($form['other1']) &&(checkEmpty($form['other1desc'])))
	{
		$msg = _ALRT_OTHER_TYPE_DESC;
		return $msg;
	}
	
	if(!checkEmpty($form['other2name']) &&(checkEmpty($form['other2'])))
	{
		$msg = _ALRT_OTHER_TYPE;
		return $msg;
	}
	if(!checkEmpty($form['other2']) &&(checkEmpty($form['other2desc'])))
	{
		$msg = _ALRT_OTHER_TYPE_DESC;
		return $msg;
	}
  
	return true;
}

/****************** Validation functions for standard offer. *********************/
function validateStandardStep2($form,$action='add')
{
	$msg = '';

	/* Required field check. */

	if(checkEmpty($form['level1q']))
	{
		$msg = _ALRT_LVL1_QTY;
		return $msg;
	}

	if(checkEmpty($form['level1p']))
	{
		$msg = _ALRT_LVL1_RS;
		return $msg;
	}

	if(!checkEmpty($form['level2q']) && checkEmpty($form['level2p']))
	{
		$msg = _ALRT_LVL2_RS;
		return $msg;
	}
	if(!checkEmpty($form['level3q']) && checkEmpty($form['level3p']))
	{
		$msg = _ALRT_LVL3_RS;
		return $msg;
	}
	if(!checkEmpty($form['level4q']) && checkEmpty($form['level4p']))
	{
		$msg = _ALRT_LVL4_RS;
		return $msg;
	}
	if(!checkEmpty($form['level5q']) && checkEmpty($form['level5p']))
	{
		$msg = _ALRT_LVL5_RS;
		return $msg;
	}

	if(checkEmpty($form['taxtype']))
	{
		$msg = _ALRT_SEL_TAX_TYPE;
		return $msg;
	}
	if(checkEmpty($form['taxrate']))
	{
		$msg = _ALRT_SEL_TAX_RT;
		return $msg;
	}
	if(checkEmpty($form['facilityfees']))
	{
		$msg = _ALRT_SEL_FAC_FEE;
		return $msg;
	}
  
  /* STEP 3 !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	if(checkEmpty($form['prodcompname']))
	{
		$msg = _ALRT_SEL_PROD_COMP;
		return $msg;
	}
	if(checkEmpty($form['prodcontname']))
	{
		$msg = _ALRT_CONTACT_PER_COMP;
		return $msg;
	}
	if(checkEmpty($form['prodcontphone']))
	{
		$msg = _ALRT_SEL_PH;
		return $msg;
	}*/
	
	if(checkEmpty($form['tickoutname']))
	{
		$msg = _ALRT_SEL_TICK_OUTLET;
		return $msg;
	}
	if(checkEmpty($form['tickoutphone']))
	{
		$msg = _ALRT_SEL_PH_TICK;
		return $msg;
	}

	/* Checks the length of the fields. */
  if((!checkEmpty($form['seating'])) && !checkLength($form['seating'],20))
	{
		$msg = str_replace('%field%',_LBL_SEATING,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if((!checkEmpty($form['level1q'])) && !checkNumeric($form['level1q']))
	{
		$msg = str_replace('%field%',_LBL_LEVEL1.' '._LBL_QTY,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['level1q'])) && !checkLength($form['level1q'],5))
	{
		$msg = str_replace('%field%',_LBL_LEVEL1.' '._LBL_QTY,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if((!checkEmpty($form['level1p'])) && !checkNumeric($form['level1p']))
	{
		$msg = str_replace('%field%',_LBL_LEVEL1.' '._LBL_PRICE,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['level1p'])) && !checkNumericRange($form['level1p'],'double'))
	{
		$msg = str_replace('%field%',_LBL_LEVEL1.' '._LBL_PRICE,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if((!checkEmpty($form['level2q'])) && !checkNumeric($form['level2q']))
	{
		$msg = str_replace('%field%',_LBL_LEVEL2.' '._LBL_QTY,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['level2q'])) && !checkLength($form['level2q'],5))
	{
		$msg = str_replace('%field%',_LBL_LEVEL2.' '._LBL_QTY,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if((!checkEmpty($form['level2p'])) && !checkNumeric($form['level2p']))
	{
		$msg = str_replace('%field%',_LBL_LEVEL2.' '._LBL_PRICE,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['level2p'])) && !checkNumericRange($form['level2p'],'double'))
	{
		$msg = str_replace('%field%',_LBL_LEVEL2.' '._LBL_PRICE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['level3q'])) && !checkNumeric($form['level3q']))
	{
		$msg = str_replace('%field%',_LBL_LEVEL3.' '._LBL_QTY,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['level3q'])) && !checkLength($form['level3q'],5))
	{
		$msg = str_replace('%field%',_LBL_LEVEL3.' '._LBL_QTY,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if((!checkEmpty($form['level3p'])) && !checkNumeric($form['level3p']))
	{
		$msg = str_replace('%field%',_LBL_LEVEL3.' '._LBL_PRICE,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['level3p'])) && !checkNumericRange($form['level3p'],'double'))
	{
		$msg = str_replace('%field%',_LBL_LEVEL3.' '._LBL_PRICE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['level5q'])) && !checkNumeric($form['level5q']))
	{
		$msg = str_replace('%field%',_LBL_LEVEL4.' '._LBL_QTY,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['level5q'])) && !checkLength($form['level5q'],5))
	{
		$msg = str_replace('%field%',_LBL_LEVEL4.' '._LBL_QTY,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if((!checkEmpty($form['level4p'])) && !checkNumeric($form['level4p']))
	{
		$msg = str_replace('%field%',_LBL_LEVEL4.' '._LBL_PRICE,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['level4p'])) && !checkNumericRange($form['level4p'],'double'))
	{
		$msg = str_replace('%field%',_LBL_LEVEL4.' '._LBL_PRICE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['level5q'])) && !checkNumeric($form['level5q']))
	{
		$msg = str_replace('%field%',_LBL_LEVEL5.' '._LBL_QTY,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['level5q'])) && !checkLength($form['level5q'],5))
	{
		$msg = str_replace('%field%',_LBL_LEVEL5.' '._LBL_QTY,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if((!checkEmpty($form['level5p'])) && !checkNumeric($form['level5p']))
	{
		$msg = str_replace('%field%',_LBL_LEVEL5.' '._LBL_PRICE,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['level5p'])) && !checkNumericRange($form['level5p'],'double'))
	{
		$msg = str_replace('%field%',_LBL_LEVEL5.' '._LBL_PRICE,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if((!checkEmpty($form['TOTAL_CAPACITY'])) && !checkNumeric($form['TOTAL_CAPACITY']))
	{
		$msg = str_replace('%field%',_LBL_TOT_CAPACITY,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['level5p'])) && !checkNumeric($form['level5p']))
	{
		$msg = str_replace('%field%',_LBL_LEVEL5.' '._LBL_PRICE,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['level5p'])) && !checkNumericRange($form['level5p'],'double'))
	{
		$msg = str_replace('%field%',_LBL_LEVEL5.' '._LBL_PRICE,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if((!checkEmpty($form['GROSS_POTENTIAL'])) && !checkNumeric($form['GROSS_POTENTIAL']))
	{
		$msg = str_replace('%field%',_LBL_GROSS_POT,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['GROSS_POTENTIAL'])) && !checkNumericRange($form['GROSS_POTENTIAL'],'double'))
	{
		$msg = str_replace('%field%',_LBL_GROSS_POT,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	
	if((!checkEmpty($form['artistcompsq'])) && !checkNumeric($form['artistcompsq']))
	{
		$msg = str_replace('%field%',_LBL_ARTIST_COMP_QTY,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['artistcompsq'])) && !checkLength($form['artistcompsq'],5))
	{
		$msg = str_replace('%field%',_LBL_ARTIST_COMP_QTY,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if((!checkEmpty($form['artistcompsp'])) && !checkNumeric($form['artistcompsp']))
	{
		$msg = str_replace('%field%',_LBL_ARTIST_COMP_PRICE,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['artistcompsp'])) && !checkNumericRange($form['artistcompsp'],'double'))
	{
		$msg = str_replace('%field%',_LBL_ARTIST_COMP_PRICE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['venuecompsq'])) && !checkNumeric($form['venuecompsq']))
	{
		$msg = str_replace('%field%',_LBL_VENUE_COMP_QTY,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['venuecompsq'])) && !checkNumericRange($form['venuecompsq'],'double'))
	{
		$msg = str_replace('%field%',_LBL_VENUE_COMP_QTY,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if((!checkEmpty($form['venuecompsp'])) && !checkNumeric($form['venuecompsp']))
	{
		$msg = str_replace('%field%',_LBL_VENUE_COMP_PRICE,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['venuecompsp'])) && !checkNumericRange($form['venuecompsp'],'double'))
	{
		$msg = str_replace('%field%',_LBL_VENUE_COMP_PRICE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
  
  if(!checkEmpty($form['onsale']))
	{
		$onsaledate = validateDate($form['onsale']);
		if($onsaledate !== true)
		{
			$msg = $onsaledate.' '._LBL_FOR.' '._LBL_ADV_BRK_DT;
			return $msg;
		}
	}

	if((!checkEmpty($form['taxtype'])) && !checkLength($form['taxtype'],30))
	{
		$msg = str_replace('%field%',_LBL_TYPE_OF_TAX,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if((!checkEmpty($form['taxrate'])) && !checkNumeric($form['taxrate']))
	{
		$msg = str_replace('%field%',_LBL_TAX_RATE_PER,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['taxrate'])) && !checkNumericRange($form['taxrate'],'double',5))
	{
		$msg = str_replace('%field%',_LBL_TAX_RATE_PER,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if((!checkEmpty($form['facilityfees'])) && !checkNumeric($form['facilityfees']))
	{
		$msg = str_replace('%field%',_LBL_FACILITY_FEES,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['facilityfees'])) && !checkNumericRange($form['facilityfees'],'double'))
	{
		$msg = str_replace('%field%',_LBL_FACILITY_FEES,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if((!checkEmpty($form['boxofficerate'])) && !checkNumeric($form['boxofficerate']))
	{
		$msg = str_replace('%field%',_LBL_BOX_OFF_FEE,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['boxofficerate'])) && !checkNumericRange($form['boxofficerate'],'double',5))
	{
		$msg = str_replace('%field%',_LBL_BOX_OFF_FEE,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if((!checkEmpty($form['boxofficefees'])) && !checkNumeric($form['boxofficefees']))
	{
		$msg = str_replace('%field%',_LBL_BOX_FEE_TICKET,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['boxofficefees'])) && !checkNumericRange($form['boxofficefees'],'double'))
	{
		$msg = str_replace('%field%',_LBL_BOX_FEE_TICKET,_ALRT_CHECK_LENGTH);
		return $msg;
	}
  
  /* STEP 3 !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	if((!checkEmpty($form['prodcompname'])) && !checkLength($form['prodcompname'],30))
	{
		$msg = str_replace('%field%',_LBL_PROD_COMP,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['prodcontname'])) && !checkLength($form['prodcontname'],30))
	{
		$msg = str_replace('%field%',_LBL_PROD_CON_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['prodcontphone'])) && !validatePhone($form['prodcontphone']))
	{
		$msg = str_replace('%field%',_LBL_PROD_CON_PH,_ALRT_CHECK_PHONE);
		return $msg;
	}
	if((!checkEmpty($form['prodcontphone'])) && !checkLength($form['prodcontphone'],18))
	{
		$msg = str_replace('%field%',_LBL_PROD_CON_PH,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['prodcontphone2'])) && !validatePhone($form['prodcontphone2']))
	{
		$msg = str_replace('%field%',_LBL_PROD_CON_ADD_PH,_ALRT_CHECK_PHONE);
		return $msg;
	}
	if((!checkEmpty($form['prodcontphone2'])) && !checkLength($form['prodcontphone2'],18))
	{
		$msg = str_replace('%field%',_LBL_PROD_CON_ADD_PH,_ALRT_CHECK_LENGTH);
		return $msg;
	}
  */
	if((!checkEmpty($form['merchandisingartist'])) && !checkNumeric($form['merchandisingartist']))
	{
		$msg = str_replace('%field%',_LBL_MERCH_ARTIST_RATE,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['merchandisingartist'])) && !checkNumericRange($form['merchandisingartist'],'double',5))
	{
		$msg = str_replace('%field%',_LBL_MERCH_ARTIST_RATE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['merchandisingvenue'])) && !checkNumeric($form['merchandisingvenue']))
	{
		$msg = str_replace('%field%',_LBL_MERCH_VENUE_RATE,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['merchandisingvenue'])) && !checkNumericRange($form['merchandisingvenue'],'double',5))
	{
		$msg = str_replace('%field%',_LBL_MERCH_VENUE_RATE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['tickoutname'])) && !checkLength($form['tickoutname'],30))
	{
		$msg = str_replace('%field%',_LBL_TIC_OUTLETS,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['tickoutphone'])) && !validatePhone($form['tickoutphone']))
	{
		$msg = str_replace('%field%',_LBL_TIC_OUTLETS_PH,_ALRT_CHECK_PHONE);
		return $msg;
	}
	if((!checkEmpty($form['tickoutphone'])) && !checkLength($form['tickoutphone'],20))
	{
		$msg = str_replace('%field%',_LBL_TIC_OUTLETS_PH,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if((!checkEmpty($form['tickouturl'])) && !validateURL($form['tickouturl']))
	{
		$msg = str_replace('%field%',_LBL_TIC_URL,_ALRT_CHECK_URL);
		return $msg;
	}
	if((!checkEmpty($form['tickouturl'])) && !checkLength($form['tickouturl'],150))
	{
		$msg = str_replace('%field%',_LBL_TIC_URL,_ALRT_CHECK_LENGTH);
		return $msg;
	}
  
	return true;
}

/*****************************************************************************************/
/****************** Validation functions for standard offer. *********************/
function validateStandardStep3($form,$action='add')
{
	$msg = '';
	global $db;
	/* Required field check. */
  
	if(checkEmpty($form['FACILITY_RENT']))
	{
		$msg = _ALRT_ENT_FAC_RENT;
		return $msg;
	}
	if(checkEmpty($form['TICKET_COMMISSION']))
	{
		$msg = _ALRT_ENT_TICK_COMM;
		return $msg;
	}
	if(checkEmpty($form['INSURANCE']))
	{
		$msg = _ALRT_ENT_INSURANCE;
		return $msg;
	}
  
	/* Checks the length of the fields. */
	if($action != 'edit')
	{
		/* For talent buyer. */
		if((!checkEmpty($form['txtBuy_Compname'])) && !checkLength($form['txtBuy_Compname'],100))
		{
			$msg = str_replace('%field%',_LBL_BUYER.' '._LBL_COMP_ORG,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtBuy_contractsign'])) && !checkLength($form['txtBuy_contractsign'],80))
		{
			$msg = str_replace('%field%',_LBL_BUYER.' '._LBL_CONTACT_SIGN,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtBuy_holdname'])) && !checkLength($form['txtBuy_holdname'],80))
		{
			$msg = str_replace('%field%',_LBL_BUYER.' '._LBL_HOLDER_NAME,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtBuy_street1'])) && !checkLength($form['txtBuy_street1'],100))
		{
			$msg = str_replace('%field%',_LBL_BUYER.' '._LBL_ADDRS_STR1,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtBuy_street2'])) && !checkLength($form['txtBuy_street2'],100))
		{
			$msg = str_replace('%field%',_LBL_BUYER.' '._LBL_ADDRS_STR2,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtBuy_city'])) && !checkLength($form['txtBuy_city'],20))
		{
			$msg = str_replace('%field%',_LBL_BUYER.' '._CITY,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtBuy_state'])) && !checkLength($form['txtBuy_state'],20))
		{
			$msg = str_replace('%field%',_LBL_BUYER.' '._LBL_ST_PROVINENCE,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtBuy_zip'])) && !checkLength($form['txtBuy_zip'],10))
		{
			$msg = str_replace('%field%',_LBL_BUYER.' '._LBL_ZIP,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtBuy_zip'])) && !validateZip($form['txtBuy_zip']))
		{
			$msg = str_replace('%field%',_LBL_BUYER.' '._LBL_ZIP,_ALRT_CHECK_ZIP);
			return $msg;
		}
		if((!checkEmpty($form['txtBuy_phone'])) && !checkLength($form['txtBuy_phone'],20))
		{
			$msg = str_replace('%field%',_LBL_BUYER.' '._LBL_PHONE,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtBuy_phone'])) && !validatePhone($form['txtBuy_phone']))
		{
			$msg = str_replace('%field%',_LBL_BUYER.' '._LBL_PHONE,_ALRT_CHECK_PHONE);
			return $msg;
		}
		if((!checkEmpty($form['txtBuy_fax'])) && !checkLength($form['txtBuy_fax'],20))
		{
			$msg = str_replace('%field%',_LBL_BUYER.' '._LBL_FAX,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtBuy_fax'])) && !validateFax($form['txtBuy_fax']))
		{
			$msg = str_replace('%field%',_LBL_BUYER.' '._LBL_FAX,_ALRT_CHECK_VALID);
			return $msg;
		}
		if((!checkEmpty($form['txtBuy_email'])) && !checkLength($form['txtBuy_email'],50))
		{
			$msg = str_replace('%field%',_LBL_BUYER.' '._LBL_EMAIL,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtBuy_email'])) && !validateEmail($form['txtBuy_email']))
		{
			$msg = str_replace('field',_LBL_BUYER.' '._LBL_EMAIL,_ALRT_VALID_FIELD);
			return $msg;
		}

		if((!checkEmpty($form['txtBuy_Concertinfo'])) && !checkLength($form['txtBuy_Concertinfo'],30))
		{
			$msg = str_replace('%field%',_LBL_BUYER.' '._LBL_CONCERT_INFO,_ALRT_CHECK_LENGTH);
			return $msg;
		}

		/* For promoter. */
		if((!checkEmpty($form['txtPro_compname'])) && !checkLength($form['txtPro_compname'],100))
		{
			$msg = str_replace('%field%',_PROMOTER.' '._LBL_COMP_ORG,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtPro_fname'])) && !checkLength($form['txtPro_fname'],80))
		{
			$msg = str_replace('%field%',_PROMOTER.' '._LBL_HOLDER_NAME,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtPro_phone'])) && !checkLength($form['txtPro_phone'],20))
		{
			$msg = str_replace('%field%',_PROMOTER.' '._LBL_PHONE,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtPro_phone'])) && !validatePhone($form['txtPro_phone']))
		{
			$msg = str_replace('%field%',_PROMOTER.' '._LBL_PHONE,_ALRT_CHECK_PHONE);
			return $msg;
		}
		if((!checkEmpty($form['txtPro_fax'])) && !checkLength($form['txtPro_fax'],20))
		{
			$msg = str_replace('%field%',_PROMOTER.' '._LBL_FAX,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtPro_fax'])) && !validateFax($form['txtPro_fax']))
		{
			$msg = str_replace('%field%',_PROMOTER.' '._LBL_FAX,_ALRT_CHECK_VALID);
			return $msg;
		}
		if((!checkEmpty($form['txtPro_email'])) && !checkLength($form['txtPro_email'],50))
		{
			$msg = str_replace('%field%',_PROMOTER.' '._LBL_EMAIL,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtPro_email'])) && !validateEmail($form['txtPro_email']))
		{
			$msg = str_replace('field',_PROMOTER.' '._LBL_EMAIL,_ALRT_VALID_FIELD);
			return $msg;
		}

		/* For middle buyer. */
		if((!checkEmpty($form['txtTaltent_compname'])) && !checkLength($form['txtTaltent_compname'],100))
		{
			$msg = str_replace('%field%',_LBL_MIDDLE_BUYER.' '._LBL_COMP_ORG,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtTaltent_fname'])) && !checkLength($form['txtTaltent_fname'],80))
		{
			$msg = str_replace('%field%',_LBL_MIDDLE_BUYER.' '._LBL_HOLDER_NAME,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtTalent_street1'])) && !checkLength($form['txtTalent_street1'],100))
		{
			$msg = str_replace('%field%',_LBL_MIDDLE_BUYER.' '._LBL_ADDRS_STR1,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtTalent_street2'])) && !checkLength($form['txtTalent_street2'],100))
		{
			$msg = str_replace('%field%',_LBL_MIDDLE_BUYER.' '._LBL_ADDRS_STR2,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtTalent_city'])) && !checkLength($form['txtTalent_city'],20))
		{
			$msg = str_replace('%field%',_LBL_MIDDLE_BUYER.' '._CITY,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtTalent_state'])) && !checkLength($form['txtTalent_state'],20))
		{
			$msg = str_replace('%field%',_LBL_MIDDLE_BUYER.' '._LBL_ST_PROVINENCE,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtTalent_zip'])) && !checkLength($form['txtTalent_zip'],10))
		{
			$msg = str_replace('%field%',_LBL_MIDDLE_BUYER.' '._LBL_ZIP,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtTalent_zip'])) && !validateZip($form['txtTalent_zip']))
		{
			$msg = str_replace('%field%',_LBL_MIDDLE_BUYER.' '._LBL_ZIP,_ALRT_CHECK_ZIP);
			return $msg;
		}
		if((!checkEmpty($form['txtTalent_phone'])) && !checkLength($form['txtTalent_phone'],20))
		{
			$msg = str_replace('%field%',_LBL_MIDDLE_BUYER.' '._LBL_PHONE,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtTalent_phone'])) && !validatePhone($form['txtTalent_phone']))
		{
			$msg = str_replace('%field%',_LBL_MIDDLE_BUYER.' '._LBL_PHONE,_ALRT_CHECK_PHONE);
			return $msg;
		}
		if((!checkEmpty($form['txtTalent_fax'])) && !checkLength($form['txtTalent_fax'],20))
		{
			$msg = str_replace('%field%',_LBL_MIDDLE_BUYER.' '._LBL_FAX,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtTalent_fax'])) && !validateFax($form['txtTalent_fax']))
		{
			$msg = str_replace('%field%',_LBL_MIDDLE_BUYER.' '._LBL_FAX,_ALRT_CHECK_VALID);
			return $msg;
		}
		if((!checkEmpty($form['txtTalent_email'])) && !checkLength($form['txtTalent_email'],50))
		{
			$msg = str_replace('%field%',_LBL_MIDDLE_BUYER.' '._LBL_EMAIL,_ALRT_CHECK_LENGTH);
			return $msg;
		}
		if((!checkEmpty($form['txtTalent_email'])) && !validateEmail($form['txtTalent_email']))
		{
			$msg = str_replace('field',_LBL_MIDDLE_BUYER.' '._LBL_EMAIL,_ALRT_VALID_FIELD);
			return $msg;
		}
	}

	/* For Other Info */
	
	if((!checkEmpty($form['AIR_PROVIDEDBY'])) && !checkLength($form['AIR_PROVIDEDBY'],1))
	{
		$msg = str_replace('%field%',_LBL_AIR.' '._LBL_PROVIDED_BY,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['AIR_DETAILS1'])) && !checkLength($form['AIR_DETAILS1'],30))
	{
		$msg = str_replace('%field%',_LBL_AIR.' '._LBL_DETAILS,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['AIR_CLASS1'])) && !checkLength($form['AIR_CLASS1'],30))
	{
		$msg = str_replace('%field%',_LBL_AIR.' '._LBL_CLASS,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['AIR_DETAILS2'])) && !checkLength($form['AIR_DETAILS2'],30))
	{
		$msg = str_replace('%field%',_LBL_AIR.' '._LBL_DETAILS,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['AIR_CLASS2'])) && !checkLength($form['AIR_CLASS2'],30))
	{
		$msg = str_replace('%field%',_LBL_AIR.' '._LBL_CLASS,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['AIR_DETAILS3'])) && !checkLength($form['AIR_DETAILS3'],30))
	{
		$msg = str_replace('%field%',_LBL_AIR.' '._LBL_DETAILS,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['AIR_CLASS3'])) && !checkLength($form['AIR_CLASS3'],30))
	{
		$msg = str_replace('%field%',_LBL_AIR.' '._LBL_CLASS,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if((!checkEmpty($form['HOTEL_PROVIDEDBY'])) && !checkLength($form['HOTEL_PROVIDEDBY'],1))
	{
		$msg = str_replace('%field%',_LBL_HOTEL.' '._LBL_PROVIDED_BY,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['HOTEL_NAME'])) && !checkLength($form['HOTEL_NAME'],50))
	{
		$msg = str_replace('%field%',_LBL_HOTEL.' '._LBL_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['NOOF_ROOM1'])) && !checkLength($form['NOOF_ROOM1'],5))
	{
		$msg = str_replace('%field%',_LBL_HOTEL.' '._LBL_ROOMS,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['ROOM_CLASS1'])) && !checkLength($form['ROOM_CLASS1'],10))
	{
		$msg = str_replace('%field%',_LBL_ROOMS.' '._LBL_CLASS,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['NOOF_ROOM2'])) && !checkLength($form['NOOF_ROOM2'],5))
	{
		$msg = str_replace('%field%',_LBL_HOTEL.' '._LBL_ROOMS,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['ROOM_CLASS2'])) && !checkLength($form['ROOM_CLASS2'],10))
	{
		$msg = str_replace('%field%',_LBL_ROOMS.' '._LBL_CLASS,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['NOOF_ROOM3'])) && !checkLength($form['NOOF_ROOM3'],5))
	{
		$msg = str_replace('%field%',_LBL_HOTEL.' '._LBL_ROOMS,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['ROOM_CLASS3'])) && !checkLength($form['ROOM_CLASS3'],10))
	{
		$msg = str_replace('%field%',_LBL_ROOMS.' '._LBL_CLASS,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if((!checkEmpty($form['TOFROM_PROVIDEDBY'])) && !checkLength($form['TOFROM_PROVIDEDBY'],1))
	{
		$msg = str_replace('%field%',_LBL_GROUND_TOFROM,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['TOFROM_AIRPORT'])) && !checkLength($form['TOFROM_AIRPORT'],1))
	{
		$msg = str_replace('%field%',_LBL_AIRPORT,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['TOFROM_HOTEL'])) && !checkLength($form['TOFROM_HOTEL'],1))
	{
		$msg = str_replace('%field%',_LBL_HOTEL,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['TOFROM_VENUE'])) && !checkLength($form['TOFROM_VENUE'],1))
	{
		$msg = str_replace('%field%',_LBL_VENUE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['TOFROM_ASDIRECTED'])) && !checkLength($form['TOFROM_ASDIRECTED'],1))
	{
		$msg = str_replace('%field%',_LBL_AS_DIRECTED,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['MEAL_PROVIDEDBY'])) && !checkLength($form['MEAL_PROVIDEDBY'],1))
	{
		$msg = str_replace('%field%',_LBL_MEALS.' '._LBL_PROVIDED_BY,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['MEALS_CATERING_RIDE'])) && !checkLength($form['MEALS_CATERING_RIDE'],1))
	{
		$msg = str_replace('%field%',_LBL_CAT_PER_RIDER,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['MEAL_BUYOUT'])) && !checkLength($form['MEAL_BUYOUT'],1))
	{
		$msg = str_replace('%field%',_LBL_MEAL_BUYOUT,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['OTHER1'])) && !checkLength($form['OTHER1'],80))
	{
		$msg = str_replace('%field%',_LBL_OTHERS.' '._LBL_PROVIDED_BY,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['OTHER1_PROVIDEDBY'])) && !checkLength($form['OTHER1_PROVIDEDBY'],1))
	{
		$msg = str_replace('%field%',_LBL_OTHERS.' '._LBL_PROVIDED_BY,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['OTHER1_DETAIL'])) && !checkLength($form['OTHER1_DETAIL'],100))
	{
		$msg = str_replace('%field%',_LBL_OTHERS.' '._LBL_DETAILS,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['OTHER2'])) && !checkLength($form['OTHER2'],80))
	{
		$msg = str_replace('%field%',_LBL_OTHERS.' '._LBL_PROVIDED_BY,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['OTHER2_PROVIDEDBY'])) && !checkLength($form['OTHER2_PROVIDEDBY'],1))
	{
		$msg = str_replace('%field%',_LBL_OTHERS.' '._LBL_PROVIDED_BY,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['OTHER2_DETAIL'])) && !checkLength($form['OTHER2_DETAIL'],100))
	{
		$msg = str_replace('%field%',_LBL_OTHERS.' '._LBL_DETAILS,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	/* For Expenses */
	if((!checkEmpty($form['FACILITY_RENT'])) && !checkNumeric($form['FACILITY_RENT']))
	{
		$msg = str_replace('%field%',_LBL_FACILITY_RENT,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['FACILITY_RENT'])) && !checkNumericRange($form['FACILITY_RENT'],'double'))
	{
		$msg = str_replace('%field%',_LBL_FACILITY_RENT,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if((!checkEmpty($form['TICKET_COMMISSION'])) && !checkNumeric($form['TICKET_COMMISSION']))
	{
		$msg = str_replace('%field%',_LBL_TICKET_COM,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['TICKET_COMMISSION'])) && !checkNumericRange($form['TICKET_COMMISSION'],'double'))
	{
		$msg = str_replace('%field%',_LBL_TICKET_COM,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['INSURANCE'])) && !checkNumeric($form['INSURANCE']))
	{
		$msg = str_replace('%field%',_LBL_INSURANCE,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['INSURANCE'])) && !checkNumericRange($form['INSURANCE'],'double'))
	{
		$msg = str_replace('%field%',_LBL_INSURANCE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['BOXOFFICE'])) && !checkNumeric($form['BOXOFFICE']))
	{
		$msg = str_replace('%field%',_LBL_BOX_OFF,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['BOXOFFICE'])) && !checkNumericRange($form['BOXOFFICE'],'double',5))
	{
		$msg = str_replace('%field%',_LBL_BOX_OFF,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['LICENSE_PERMIT'])) && !checkNumeric($form['LICENSE_PERMIT']))
	{
		$msg = str_replace('%field%',_LBL_LIC_PERMIT,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['LICENSE_PERMIT'])) && !checkNumericRange($form['LICENSE_PERMIT'],'double'))
	{
		$msg = str_replace('%field%',_LBL_LIC_PERMIT,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['SETUP'])) && !checkNumeric($form['SETUP']))
	{
		$msg = str_replace('%field%',_LBL_SETUP,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['SETUP'])) && !checkNumericRange($form['SETUP'],'double'))
	{
		$msg = str_replace('%field%',_LBL_SETUP,_ALRT_CHECK_LENGTH);
		return $msg;
	}

	if((!checkEmpty($form['CREDITCARD'])) && !checkNumeric($form['CREDITCARD']))
	{
		$msg = str_replace('%field%',_LBL_CC,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['CREDITCARD'])) && !checkNumericRange($form['CREDITCARD'],'double'))
	{
		$msg = str_replace('%field%',_LBL_CC,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['LOADERS'])) && !checkNumeric($form['LOADERS']))
	{
		$msg = str_replace('%field%',_LBL_LOADERS,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['LOADERS'])) && !checkNumericRange($form['LOADERS'],'double'))
	{
		$msg = str_replace('%field%',_LBL_LOADERS,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['SOUND_LIGHTS'])) && !checkNumeric($form['SOUND_LIGHTS']))
	{
		$msg = str_replace('%field%',_LBL_SOUND_LIGHT,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['SOUND_LIGHTS'])) && !checkNumericRange($form['SOUND_LIGHTS'],'double'))
	{
		$msg = str_replace('%field%',_LBL_SOUND_LIGHT,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['ADVERTISING'])) && !checkNumeric($form['ADVERTISING']))
	{
		$msg = str_replace('%field%',_LBL_ADV,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['ADVERTISING'])) && !checkNumericRange($form['ADVERTISING'],'double'))
	{
		$msg = str_replace('%field%',_LBL_ADV,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['MEDICAL'])) && !checkNumeric($form['MEDICAL']))
	{
		$msg = str_replace('%field%',_LBL_MEDICAL,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['MEDICAL'])) && !checkNumericRange($form['MEDICAL'],'double'))
	{
		$msg = str_replace('%field%',_LBL_MEDICAL,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['SPOTLIGHTS'])) && !checkNumeric($form['SPOTLIGHTS']))
	{
		$msg = str_replace('%field%',_LBL_SPOTLIGHT,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['SPOTLIGHTS'])) && !checkNumericRange($form['SPOTLIGHTS'],'double'))
	{
		$msg = str_replace('%field%',_LBL_SPOTLIGHT,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['ASCAP_BMI'])) && !checkNumeric($form['ASCAP_BMI']))
	{
		$msg = str_replace('%field%',_LBL_ASCAP_BMI,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['ASCAP_BMI'])) && !checkNumericRange($form['ASCAP_BMI'],'double'))
	{
		$msg = str_replace('%field%',_LBL_ASCAP_BMI,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['OTHER_TAX'])) && !checkNumeric($form['OTHER_TAX']))
	{
		$msg = str_replace('%field%',_LBL_OTH_TAX,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['OTHER_TAX'])) && !checkNumericRange($form['OTHER_TAX'],'double'))
	{
		$msg = str_replace('%field%',_LBL_OTH_TAX,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['STAGE'])) && !checkNumeric($form['STAGE']))
	{
		$msg = str_replace('%field%',_LBL_STAGE,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['STAGE'])) && !checkNumericRange($form['STAGE'],'double'))
	{
		$msg = str_replace('%field%',_LBL_STAGE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['BARRICADE'])) && !checkNumeric($form['BARRICADE']))
	{
		$msg = str_replace('%field%',_LBL_BARRICADE,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['BARRICADE'])) && !checkNumericRange($form['BARRICADE'],'double'))
	{
		$msg = str_replace('%field%',_LBL_BARRICADE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['PHONE_INTERNET'])) && !checkNumeric($form['PHONE_INTERNET']))
	{
		$msg = str_replace('%field%',_LBL_PHONE_INT,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['PHONE_INTERNET'])) && !checkNumericRange($form['PHONE_INTERNET'],'double'))
	{
		$msg = str_replace('%field%',_LBL_PHONE_INT,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['STAGE_HANDS'])) && !checkNumeric($form['STAGE_HANDS']))
	{
		$msg = str_replace('%field%',_LBL_STAGE_HANDS,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['STAGE_HANDS'])) && !checkNumericRange($form['STAGE_HANDS'],'double'))
	{
		$msg = str_replace('%field%',_LBL_STAGE_HANDS,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['CATERING'])) && !checkNumeric($form['CATERING']))
	{
		$msg = str_replace('%field%',_LBL_CATERING,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['CATERING'])) && !checkNumericRange($form['CATERING'],'double'))
	{
		$msg = str_replace('%field%',_LBL_CATERING,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['PIANO'])) && !checkNumeric($form['PIANO']))
	{
		$msg = str_replace('%field%',_LBL_PIANO,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['PIANO'])) && !checkNumericRange($form['PIANO'],'double'))
	{
		$msg = str_replace('%field%',_LBL_PIANO,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['STAGE_MANAGER'])) && !checkNumeric($form['STAGE_MANAGER']))
	{
		$msg = str_replace('%field%',_LBL_STAGE_MAN,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['STAGE_MANAGER'])) && !checkNumericRange($form['STAGE_MANAGER'],'double'))
	{
		$msg = str_replace('%field%',_LBL_STAGE_MAN,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['CHAIR_RENTAL'])) && !checkNumeric($form['CHAIR_RENTAL']))
	{
		$msg = str_replace('%field%',_LBL_CHR_RENTAL,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['CHAIR_RENTAL'])) && !checkNumericRange($form['CHAIR_RENTAL'],'double'))
	{
		$msg = str_replace('%field%',_LBL_CHR_RENTAL,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['TUNER'])) && !checkNumeric($form['TUNER']))
	{
		$msg = str_replace('%field%',_LBL_TUNER,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['TUNER'])) && !checkNumericRange($form['TUNER'],'double'))
	{
		$msg = str_replace('%field%',_LBL_TUNER,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['CLEANUP'])) && !checkNumeric($form['CLEANUP']))
	{
		$msg = str_replace('%field%',_LBL_CLEANUP,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['CLEANUP'])) && !checkNumericRange($form['CLEANUP'],'double'))
	{
		$msg = str_replace('%field%',_LBL_CLEANUP,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['SUPPORT_TALENT'])) && !checkNumeric($form['SUPPORT_TALENT']))
	{
		$msg = str_replace('%field%',_LBL_SUPPORT_TALENT,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['SUPPORT_TALENT'])) && !checkNumericRange($form['SUPPORT_TALENT'],'double'))
	{
		$msg = str_replace('%field%',_LBL_SUPPORT_TALENT,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['POLICE'])) && !checkNumeric($form['POLICE']))
	{
		$msg = str_replace('%field%',_LBL_POLICE,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['POLICE'])) && !checkNumericRange($form['POLICE'],'double'))
	{
		$msg = str_replace('%field%',_LBL_POLICE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['TICKET_PRINTING'])) && !checkNumeric($form['TICKET_PRINTING']))
	{
		$msg = str_replace('%field%',_LBL_TICK_PRINTING,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['TICKET_PRINTING'])) && !checkNumericRange($form['TICKET_PRINTING'],'double'))
	{
		$msg = str_replace('%field%',_LBL_TICK_PRINTING,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['DAMAGE_DEPOSIT'])) && !checkNumeric($form['DAMAGE_DEPOSIT']))
	{
		$msg = str_replace('%field%',_LBL_DAMAGE_DEPO,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['DAMAGE_DEPOSIT'])) && !checkNumericRange($form['DAMAGE_DEPOSIT'],'double'))
	{
		$msg = str_replace('%field%',_LBL_DAMAGE_DEPO,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['PRIVATE_SECURITY'])) && !checkNumeric($form['PRIVATE_SECURITY']))
	{
		$msg = str_replace('%field%',_LBL_PRIVACY_SEC,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['PRIVATE_SECURITY'])) && !checkNumericRange($form['PRIVATE_SECURITY'],'double'))
	{
		$msg = str_replace('%field%',_LBL_PRIVACY_SEC,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['TICKET_SELLERS'])) && !checkNumeric($form['TICKET_SELLERS']))
	{
		$msg = str_replace('%field%',_LBL_TICKET_SELLERS,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['TICKET_SELLERS'])) && !checkNumericRange($form['TICKET_SELLERS'],'double'))
	{
		$msg = str_replace('%field%',_LBL_TICKET_SELLERS,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['DRESSROOM_FURNITURE'])) && !checkNumeric($form['DRESSROOM_FURNITURE']))
	{
		$msg = str_replace('%field%',_LBL_DRESSING_ROOMS,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['DRESSROOM_FURNITURE'])) && !checkNumericRange($form['DRESSROOM_FURNITURE'],'double'))
	{
		$msg = str_replace('%field%',_LBL_DRESSING_ROOMS,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['BACKSTAGE_SECURITY'])) && !checkNumeric($form['BACKSTAGE_SECURITY']))
	{
		$msg = str_replace('%field%',_LBL_BACKSTAGE_SEC,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['BACKSTAGE_SECURITY'])) && !checkNumericRange($form['BACKSTAGE_SECURITY'],'double'))
	{
		$msg = str_replace('%field%',_LBL_BACKSTAGE_SEC,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['TICKET_TAKERS'])) && !checkNumeric($form['TICKET_TAKERS']))
	{
		$msg = str_replace('%field%',_LBL_TICKET_TRACK,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['TICKET_TAKERS'])) && !checkNumericRange($form['TICKET_TAKERS'],'double'))
	{
		$msg = str_replace('%field%',_LBL_TICKET_TRACK,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['ELECTRICIAN'])) && !checkNumeric($form['ELECTRICIAN']))
	{
		$msg = str_replace('%field%',_LBL_ELECTRICIAN,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['ELECTRICIAN'])) && !checkNumericRange($form['ELECTRICIAN'],'double'))
	{
		$msg = str_replace('%field%',_LBL_ELECTRICIAN,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['POWER'])) && !checkNumeric($form['POWER']))
	{
		$msg = str_replace('%field%',_LBL_POWER,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['POWER'])) && !checkNumericRange($form['POWER'],'double'))
	{
		$msg = str_replace('%field%',_LBL_POWER,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['TOWELS'])) && !checkNumeric($form['TOWELS']))
	{
		$msg = str_replace('%field%',_LBL_TOWELS,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['TOWELS'])) && !checkNumericRange($form['TOWELS'],'double'))
	{
		$msg = str_replace('%field%',_LBL_TOWELS,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['EQUIPMENT_RENTAL'])) && !checkNumeric($form['EQUIPMENT_RENTAL']))
	{
		$msg = str_replace('%field%',_LBL_EQUIPMENT_RENTAL,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['EQUIPMENT_RENTAL'])) && !checkNumericRange($form['EQUIPMENT_RENTAL'],'double'))
	{
		$msg = str_replace('%field%',_LBL_EQUIPMENT_RENTAL,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['REMOTES'])) && !checkNumeric($form['REMOTES']))
	{
		$msg = str_replace('%field%',_LBL_REMOTES,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['REMOTES'])) && !checkNumericRange($form['REMOTES'],'double'))
	{
		$msg = str_replace('%field%',_LBL_REMOTES,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['TRANSPORTATION'])) && !checkNumeric($form['TRANSPORTATION']))
	{
		$msg = str_replace('%field%',_LBL_TRANSPOTATIONS,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['TRANSPORTATION'])) && !checkNumericRange($form['TRANSPORTATION'],'double'))
	{
		$msg = str_replace('%field%',_LBL_TRANSPOTATIONS,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['FIREMEN'])) && !checkNumeric($form['FIREMEN']))
	{
		$msg = str_replace('%field%',_LBL_FIREMEN,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['FIREMEN'])) && !checkNumericRange($form['FIREMEN'],'double'))
	{
		$msg = str_replace('%field%',_LBL_FIREMEN,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['RIGGING'])) && !checkNumeric($form['RIGGING']))
	{
		$msg = str_replace('%field%',_LBL_RIGGING,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['RIGGING'])) && !checkNumericRange($form['RIGGING'],'double'))
	{
		$msg = str_replace('%field%',_LBL_RIGGING,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['USHERS'])) && !checkNumeric($form['USHERS']))
	{
		$msg = str_replace('%field%',_LBL_USHERS,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['USHERS'])) && !checkNumericRange($form['USHERS'],'double'))
	{
		$msg = str_replace('%field%',_LBL_USHERS,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['FORKLIFT'])) && !checkNumeric($form['FORKLIFT']))
	{
		$msg = str_replace('%field%',_LBL_FORKLIFT,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['FORKLIFT'])) && !checkNumericRange($form['FORKLIFT'],'double'))
	{
		$msg = str_replace('%field%',_LBL_FORKLIFT,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['RUNNER'])) && !checkNumeric($form['RUNNER']))
	{
		$msg = str_replace('%field%',_LBL_RUNNER,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['RUNNER'])) && !checkNumericRange($form['RUNNER'],'double'))
	{
		$msg = str_replace('%field%',_LBL_RUNNER,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['MISC'])) && !checkNumeric($form['MISC']))
	{
		$msg = str_replace('%field%',_LBL_MISC,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['MISC'])) && !checkNumericRange($form['MISC'],'double'))
	{
		$msg = str_replace('%field%',_LBL_MISC,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['OTHEREXP1'])) && (!($form['OTHEREXP_DETAIL1'] > 0)))
	{
		$msg = str_replace('field',_LBL_OTH_EXPENSES.' '._LBL_EXPENSES,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if(($form['OTHEREXP_DETAIL1'] > 0) && checkEmpty($form['OTHEREXP1']))
	{
		$msg = str_replace('field',_LBL_OTH_EXPENSES.' '._LBL_DETAILS,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['OTHEREXP1'])) && !checkLength($form['OTHEREXP1'],50))
	{
		$msg = str_replace('%field%',_LBL_OTH_EXPENSES.' '._LBL_DETAILS,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['OTHEREXP_DETAIL1'])) && !checkNumeric($form['OTHEREXP_DETAIL1']))
	{
		$msg = str_replace('%field%',_LBL_OTH_EXPENSES.' '._LBL_EXPENSES,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['OTHEREXP_DETAIL1'])) && !checkNumericRange($form['OTHEREXP_DETAIL1'],'double'))
	{
		$msg = str_replace('%field%',_LBL_OTH_EXPENSES.' '._LBL_EXPENSES,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['OTHEREXP2'])) && (!($form['OTHEREXP_DETAIL2'] > 0)))
	{
		$msg = str_replace('field',_LBL_OTH_EXPENSES.' '._LBL_EXPENSES,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if(($form['OTHEREXP_DETAIL2'] > 0) && checkEmpty($form['OTHEREXP2']))
	{
		$msg = str_replace('field',_LBL_OTH_EXPENSES.' '._LBL_DETAILS,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['OTHEREXP2'])) && !checkLength($form['OTHEREXP2'],50))
	{
		$msg = str_replace('%field%',_LBL_OTH_EXPENSES.' '._LBL_DETAILS.' '._LBL_DETAILS,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['OTHEREXP_DETAIL2'])) && !checkNumeric($form['OTHEREXP_DETAIL2']))
	{
		$msg = str_replace('%field%',_LBL_OTH_EXPENSES.' '._LBL_EXPENSES,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['OTHEREXP_DETAIL2'])) && !checkNumericRange($form['OTHEREXP_DETAIL2'],'double'))
	{
		$msg = str_replace('%field%',_LBL_OTH_EXPENSES.' '._LBL_EXPENSES,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['OTHEREXP3'])) && (!($form['OTHEREXP_DETAIL3'] > 0)))
	{
		$msg = str_replace('field',_LBL_OTH_EXPENSES.' '._LBL_EXPENSES,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if(($form['OTHEREXP_DETAIL3'] > 0) && checkEmpty($form['OTHEREXP3']))
	{
		$msg = str_replace('field',_LBL_OTH_EXPENSES.' '._LBL_DETAILS,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['OTHEREXP3'])) && !checkLength($form['OTHEREXP3'],50))
	{
		$msg = str_replace('%field%',_LBL_OTH_EXPENSES.' '._LBL_DETAILS,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['OTHEREXP_DETAIL3'])) && !checkNumeric($form['OTHEREXP_DETAIL3']))
	{
		$msg = str_replace('%field%',_LBL_OTH_EXPENSES.' '._LBL_EXPENSES,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['OTHEREXP_DETAIL3'])) && !checkNumericRange($form['OTHEREXP_DETAIL3'],'double'))
	{
		$msg = str_replace('%field%',_LBL_OTH_EXPENSES.' '._LBL_EXPENSES,_ALRT_CHECK_LENGTH);
		return $msg;
	}


	return true;
}
/*****************************************************************************************/
/****************** Validation functions for agency profile. *********************/
function validateAgencyProfile($form,$file)
{
	global $show_tab_type;
	if(checkEmpty($form['firstname']))
	{
		$msg = str_replace('field',_LBL_FIRST_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if(checkEmpty($form['lastname']))
	{
		$msg = str_replace('field',_LBL_LAST_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if(checkEmpty($form['companyname']))
	{
		$msg = str_replace('field',_LBL_COMPANY_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if(checkEmpty($form['companyholdername']))
	{
		$msg = str_replace('field',_LBL_COMPANY_HOLDER,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	/*if(checkEmpty($form['pri_email']))
	{
		$msg = str_replace('field',_LBL_PRI_EMAIL,_ALRT_REQUIRED_FIELD);
		return $msg;
	}*/
	if(checkEmpty($form['contactname']))
	{
		$msg = str_replace('field',_LBL_CON_PERSON,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if(checkEmpty($form['contacttitle']))
	{
		$msg = str_replace('field',_LBL_TITLE,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if(checkEmpty($form['brief']))
	{
		$msg = str_replace('field',_LBL_BRIEF_DESC,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'BIO_INFO';
		return $msg;
	}
	if(checkEmpty($form['aboutus']))
	{
		$msg = str_replace('field',_LBL_ABOUT_US,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'BIO_INFO';
		return $msg;
	}
	if(checkEmpty($form['additionalinfo']))
	{
		$msg = str_replace('field',_LBL_ADDITIONAL_INFO,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'BIO_INFO';
		return $msg;
	}
	if(checkEmpty($form['yearfounded']))
	{
		$msg = str_replace('field',_LBL_YR_FOUNDED,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if(checkEmpty($form['url']))
	{
		$msg = str_replace('field',_LBL_COMP_URL,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if(checkEmpty($form['contactemail']))
	{
		$msg = str_replace('field',_LBL_COMP_EMAIL,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if(checkEmpty($form['basecommission']))
	{
		$msg = str_replace('field',_LBL_BASE_COMM,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if(checkEmpty($form['licno']))
	{
		$msg = str_replace('field',_LBL_LIC_NUM,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if(checkEmpty($form['tax']) && checkEmpty($form['duns']))
	{
		$msg = str_replace('field',_LBL_TAX_EIN.' '._LBL_OR.' '._LBL_DUNS.' '._LBL_DETAILS,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if(checkEmpty($form['workaddress1']))
	{
		$msg = str_replace('field',_LBL_WORK_ADDR1,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if(checkEmpty($form['workcity']))
	{
		$msg = str_replace('field',_LBL_WORK_CITY,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if(checkEmpty($form['workstate']))
	{
		$msg = str_replace('field',_LBL_WORK_STATE,_ALRT_REQUIRED_SELECT);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if(checkEmpty($form['workcountry']))
	{
		$msg = str_replace('field',_LBL_WORK_COUNTRY,_ALRT_REQUIRED_SELECT);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if(checkEmpty($form['workzip']))
	{
		$msg = str_replace('field',_LBL_WORK_ZIP,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if(checkEmpty($form['officephone']))
	{
		$msg = str_replace('field',_LBL_WORK_PH,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if(checkEmpty($form['type_entertainment']))
	{
		$msg = str_replace('field',_LBL_TYPE_OF_ENT,_ALRT_REQUIRED_SELECT);
		$show_tab_type = 'REP_INFO';
		return $msg;
	}
	if(checkEmpty($form['GENRE']))
	{
		$msg = str_replace('field',_LBL_ACCEPTED_GENRE,_ALRT_REQUIRED_SELECT);
		$show_tab_type = 'REP_INFO';
		return $msg;
	}
	if(checkEmpty($form['contactaddress1']))
	{
		$msg = str_replace('field',_LBL_ADDR1,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['contactcity']))
	{
		$msg = str_replace('field',_CITY,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['contactstate']))
	{
		$msg = str_replace('field',_LBL_STATE,_ALRT_REQUIRED_SELECT);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['contactstate']))
	{
		$msg = str_replace('field',_LBL_COUNTRY,_ALRT_REQUIRED_SELECT);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['contactzip']))
	{
		$msg = str_replace('field',_LBL_ZIP,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['contactphone']))
	{
		$msg = str_replace('field',_LBL_PH_NO,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($file['logo']['name']) && checkEmpty($form['LOGO_temp']))
	{
		$msg = str_replace('field',_LBL_UPLOAD_LOGO,_ALRT_REQUIRED_UPLOAD);
		$show_tab_type = 'UPLOAD_INFO';
		return $msg;
	}
	if(checkEmpty($file['liccopy']['name']) && checkEmpty($form['LIC_temp']))
	{
		$msg = str_replace('field',_LBL_LIC_COPY,_ALRT_REQUIRED_UPLOAD);
		$show_tab_type = 'UPLOAD_INFO';
		return $msg;
	}
	if(checkEmpty($file['bondcopy']['name']) && checkEmpty($form['BOND_temp']))
	{
		$msg = str_replace('field',_LBL_BOND_COPY,_ALRT_REQUIRED_UPLOAD);
		$show_tab_type = 'UPLOAD_INFO';
		return $msg;
	}

	if((!checkEmpty($form['firstname'])) && !checkLength($form['firstname'],255))
	{
		$msg = str_replace('%field%',_LBL_FIRST_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['lastname'])) && !checkLength($form['lastname'],255))
	{
		$msg = str_replace('%field%',_LBL_LAST_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['companyname'])) && !checkLength($form['companyname'],100))
	{
		$msg = str_replace('%field%',_LBL_COMPANY_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['contactname'])) && !checkLength($form['contactname'],100))
	{
		$msg = str_replace('%field%',_LBL_CON_PERSON,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['companyholdername'])) && !checkLength($form['companyholdername'],100))
	{
		$msg = str_replace('%field%',_LBL_HOLDER_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['contacttitle'])) && !checkLength($form['contacttitle'],100))
	{
		$msg = str_replace('%field%',_LBL_TITLE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	/*if((!checkEmpty($form['pri_email'])) && !checkLength($form['pri_email'],100))
	{
		$msg = str_replace('%field%',_LBL_PRI_EMAIL,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['pri_email'])) && !validateEmail($form['pri_email']))
	{
		$msg = str_replace('field',_LBL_PRI_EMAIL,_ALRT_VALID_FIELD);
		return $msg;
	}*/
	if((!checkEmpty($form['yearfounded'])) && !checkLength($form['yearfounded'],4))
	{
		$msg = str_replace('%field%',_LBL_YR_FOUNDED,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['yearfounded'])) && !checkValidYear($form['yearfounded']))
	{
		$msg = str_replace('field',_LBL_YR_FOUNDED,_ALRT_VALID_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['workaddress1'])) && !checkLength($form['workaddress1'],100))
	{
		$msg = str_replace('%field%',_LBL_WORK_ADDR1,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['workaddress2'])) && !checkLength($form['workaddress2'],100))
	{
		$msg = str_replace('%field%',_LBL_WORK_ADDR2,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['url'])) && !checkLength($form['url'],200))
	{
		$msg = str_replace('%field%',_LBL_COMP_URL,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['url'])) && !validateURL($form['url']))
	{
		$msg = str_replace('%field%',_LBL_COMP_URL,_ALRT_CHECK_URL);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['contactemail'])) && !checkLength($form['contactemail'],200))
	{
		$msg = str_replace('%field%',_LBL_COMP_EMAIL,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['contactemail'])) && !validateEmail($form['contactemail']))
	{
		$msg = str_replace('field',_LBL_COMP_EMAIL,_ALRT_VALID_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['workcity'])) && !checkLength($form['workcity'],80))
	{
		$msg = str_replace('%field%',_LBL_WORK_CITY,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['basecommission'])) && !checkLength($form['basecommission'],3))
	{
		$msg = str_replace('%field%',_LBL_BASE_COMM,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['workstate'])) && !checkLength($form['workstate'],80))
	{
		$msg = str_replace('%field%',_LBL_WORK_STATE,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['licno'])) && !checkLength($form['licno'],20))
	{
		$msg = str_replace('%field%',_LBL_LIC_NUM,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['workcountry'])) && !checkLength($form['workcountry'],80))
	{
		$msg = str_replace('%field%',_LBL_WORK_COUNTRY,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['tax'])) && !checkLength($form['tax'],20))
	{
		$msg = str_replace('%field%',_LBL_TAX_EIN,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['workzip'])) && !checkLength($form['workzip'],15))
	{
		$msg = str_replace('%field%',_LBL_WORK_ZIP,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['workzip'])) && !validateZip($form['workzip']))
	{
		$msg = str_replace('%field%',_LBL_WORK_ZIP,_ALRT_CHECK_ZIP);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['duns'])) && !checkLength($form['duns'],20))
	{
		$msg = str_replace('%field%',_LBL_DUNS,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['officephone'])) && !checkLength($form['officephone'],20))
	{
		$msg = str_replace('%field%',_LBL_WORK_PH,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['officephone'])) && !validatePhone($form['officephone']))
	{
		$msg = str_replace('%field%',_LBL_WORK_PH,_ALRT_CHECK_PHONE);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['contactaddress1'])) && !checkLength($form['contactaddress1'],100))
	{
		$msg = str_replace('%field%',_LBL_ADDR1,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['contactzip'])) && !checkLength($form['contactzip'],15))
	{
		$msg = str_replace('%field%',_LBL_ZIP,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['contactzip'])) && !validateZip($form['contactzip']))
	{
		$msg = str_replace('%field%',_LBL_ZIP,_ALRT_CHECK_ZIP);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['contactaddress2'])) && !checkLength($form['contactaddress2'],100))
	{
		$msg = str_replace('%field%',_LBL_ADDR2,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['contactphone'])) && !checkLength($form['contactphone'],20))
	{
		$msg = str_replace('%field%',_LBL_PH_NO,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['contactphone'])) && !validatePhone($form['contactphone']))
	{
		$msg = str_replace('%field%',_LBL_PH_NO,_ALRT_CHECK_PHONE);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['contactcity'])) && !checkLength($form['contactcity'],80))
	{
		$msg = str_replace('%field%',_CITY,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['contactmobile'])) && !checkLength($form['contactmobile'],20))
	{
		$msg = str_replace('%field%',_LBL_MO_NO,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['contactmobile'])) && !validatePhone($form['contactmobile']))
	{
		$msg = str_replace('%field%',_LBL_MO_NO,_ALRT_CHECK_PHONE);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['contactstate'])) && !checkLength($form['contactstate'],80))
	{
		$msg = str_replace('%field%',_LBL_STATE,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['contactfax'])) && !checkLength($form['contactfax'],20))
	{
		$msg = str_replace('%field%',_LBL_FAX_NO,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['contactfax'])) && !validateFax($form['contactfax']))
	{
		$msg = str_replace('%field%',_LBL_FAX_NO,_ALRT_CHECK_VALID);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['contactcountry'])) && !checkLength($form['contactcountry'],80))
	{
		$msg = str_replace('%field%',_LBL_COUNTRY,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}

	if(!checkEmpty($file['logo']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg');
		$validate_img = validateImage($file['logo'], _LBL_UPLOAD_LOGO , $allowed_extensions);

		if($validate_img !== true)
		{
			$show_tab_type = 'UPLOAD_INFO';
			return $validate_img;
		}
	}
	if(!checkEmpty($file['liccopy']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg');
		$validate_img = validateImage($file['liccopy'], _LBL_LIC_COPY , $allowed_extensions);

		if($validate_img !== true)
		{
			$show_tab_type = 'UPLOAD_INFO';
			return $validate_img;
		}
	}
	if(!checkEmpty($file['bondcopy']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg');
		$validate_img = validateImage($file['bondcopy'], _LBL_BOND_COPY , $allowed_extensions);

		if($validate_img !== true)
		{
			$show_tab_type = 'UPLOAD_INFO';
			return $validate_img;
		}
	}
	return true;
}

/****************** Validation functions for agent profile. *********************/
function validateAgentProfile($form, $file)
{
	global $show_tab_type;
	if(checkEmpty($form['firstname']))
	{
		$msg = str_replace('field',_LBL_FIRST_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['firstname'])) && !checkLength($form['firstname'],255))
	{
		$msg = str_replace('%field%',_LBL_FIRST_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['lastname']))
	{
		$msg = str_replace('field',_LBL_LAST_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['lastname'])) && !checkLength($form['lastname'],255))
	{
		$msg = str_replace('%field%',_LBL_LAST_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	/*if(checkEmpty($form['contactname']))
	{
		$msg = str_replace('field',_LBL_CONTACT_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}*/
	if((!checkEmpty($form['contactname'])) && !checkLength($form['contactname'],100))
	{
		$msg = str_replace('%field%',_LBL_CONTACT_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	/*if(checkEmpty($form['pri_email']))
	{
		$msg = str_replace('field',_LBL_PRI_EMAIL,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['pri_email'])) && !checkLength($form['pri_email'],255))
	{
		$msg = str_replace('%field%',_LBL_PRI_EMAIL,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['pri_email'])) && !validateEmail($form['pri_email']))
	{
		$msg = str_replace('field',_LBL_PRI_EMAIL,_ALRT_VALID_FIELD);
		return $msg;
	}	*/
	if((!checkEmpty($form['gender'])) && !checkLength($form['gender'],1))
	{
		$msg = str_replace('%field%',_LBL_GENDER,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['homephone']))
	{
		$msg = str_replace('field',_LBL_HOME_PHONE,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['homephone'])) && !checkLength($form['homephone'],20))
	{
		$msg = str_replace('%field%',_LBL_HOME_PHONE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['homephone'])) && !validatePhone($form['homephone']))
	{
		$msg = str_replace('%field%',_LBL_HOME_PHONE,_ALRT_CHECK_PHONE);
		return $msg;
	}
	if(checkEmpty($form['dob']))
	{
		$msg = str_replace('field',_LBL_DOB,_ALRT_REQUIRED_SELECT);
		return $msg;
	}
	if(!checkEmpty($form['dob']))
	{
		$dob = validateDate($form['dob']);
		if($dob !== true)
		{
			$msg = $dob.' '._LBL_FOR.' '._LBL_DOB;
			return $msg;
		}
	}
	if(checkEmpty($form['homestreet1']))
	{
		$msg = str_replace('field',_LBL_HOME_ADDR1,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['homestreet1'])) && !checkLength($form['homestreet1'],100))
	{
		$msg = str_replace('%field%',_LBL_HOME_ADDR1,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['experience']))
	{
		$msg = str_replace('field',_LBL_YR_FIRST_LIC,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['experience'])) && !checkLength($form['experience'],4))
	{
		$msg = str_replace('%field%',_LBL_YR_FIRST_LIC,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['experience'])) && !checkValidYear($form['experience']))
	{
		$msg = str_replace('field',_LBL_YR_FIRST_LIC,_ALRT_VALID_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['homestreet2'])) && !checkLength($form['homestreet2'],100))
	{
		$msg = str_replace('%field%',_LBL_HOME_ADDR2,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['url'])) && !checkLength($form['url'],200))
	{
		$msg = str_replace('%field%',_LBL_URL,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['url'])) && !validateURL($form['url']))
	{
		$msg = str_replace('%field%',_LBL_URL,_ALRT_CHECK_URL);
		return $msg;
	}
	/*if(checkEmpty($form['licno']))
	{
		$msg = str_replace('field',_LBL_LIC_NUM,_ALRT_REQUIRED_FIELD);
		return $msg;
	}	*/
	if(checkEmpty($form['city']))
	{
		$msg = str_replace('field',_CITY,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['city'])) && !checkLength($form['city'],80))
	{
		$msg = str_replace('%field%',_CITY,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['assistname'])) && !checkLength($form['assistname'],100))
	{
		$msg = str_replace('%field%',_LBL_ASS_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['state']))
	{
		$msg = str_replace('field',_LBL_STATE,_ALRT_REQUIRED_SELECT);
		return $msg;
	}
	if((!checkEmpty($form['state'])) && !checkLength($form['state'],80))
	{
		$msg = str_replace('%field%',_LBL_STATE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['assistmail'])) && !checkLength($form['assistmail'],100))
	{
		$msg = str_replace('%field%',_LBL_ASS_EMAIL,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['assistmail'])) && !validateEmail($form['assistmail']))
	{
		$msg = str_replace('field',_LBL_ASS_EMAIL,_ALRT_VALID_FIELD);
		return $msg;
	}
	if(checkEmpty($form['country']))
	{
		$msg = str_replace('field',_LBL_COUNTRY,_ALRT_REQUIRED_SELECT);
		return $msg;
	}
	if((!checkEmpty($form['country'])) && !checkLength($form['country'],80))
	{
		$msg = str_replace('%field%',_LBL_COUNTRY,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['secmailid'])) && !checkLength($form['secmailid'],100))
	{
		$msg = str_replace('%field%',_LBL_SEC_EMAIL,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['secmailid'])) && !validateEmail($form['secmailid']))
	{
		$msg = str_replace('field',_LBL_SEC_EMAIL,_ALRT_VALID_FIELD);
		return $msg;
	}
	if(checkEmpty($form['zip']))
	{
		$msg = str_replace('field',_LBL_ZIP,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['zip'])) && !checkLength($form['zip'],15))
	{
		$msg = str_replace('%field%',_LBL_ZIP,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['zip'])) && !validateZip($form['zip']))
	{
		$msg = str_replace('%field%',_LBL_ZIP,_ALRT_CHECK_ZIP);
		return $msg;
	}	
	/*if((!checkEmpty($form['licno'])) && !checkLength($form['licno'],20))
	{
		$msg = str_replace('%field%',_LBL_LIC_NUM,_ALRT_CHECK_LENGTH);
		return $msg;
	}*/
	if(checkEmpty($form['jobtitle']))
	{
		$msg = str_replace('field',_LBL_JOB_TITLE,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['jobtitle'])) && !checkLength($form['jobtitle'],50))
	{
		$msg = str_replace('%field%',_LBL_JOB_TITLE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	/*if(checkEmpty($form['briefdesc']))
	{
		$msg = _ALRT_BRIEF_DESC;
		$show_tab_type = 'BIO_INFO';
		return $msg;
	}
	if(checkEmpty($form['FULLBIO']))
	{
		$msg = str_replace('field',_LBL_FULL_BIO,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'BIO_INFO';
		return $msg;
	}	*/
	if((!checkEmpty($form['agency_employed'])) && !checkLength($form['agency_employed'],50))
	{
		$msg = str_replace('%field%',_LBL_AGENCY_EMP_BY,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'AGENCY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['agency_job_title'])) && !checkLength($form['agency_job_title'],50))
	{
		$msg = str_replace('%field%',_LBL_JOB_TITLE,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'AGENCY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['report_to'])) && !checkLength($form['report_to'],50))
	{
		$msg = str_replace('%field%',_LBL_REPO_TO,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'AGENCY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['agency_lic_no'])) && !checkLength($form['agency_lic_no'],20))
	{
		$msg = str_replace('%field%',_LBL_LIC_NUM,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'AGENCY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['lic_state'])) && !checkLength($form['lic_state'],80))
	{
		$msg = str_replace('%field%',_LBL_LIC_STATE,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'AGENCY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['lic_agency'])) && !checkLength($form['lic_agency'],100))
	{
		$msg = str_replace('%field%',_LBL_LIC_AGENCY,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'AGENCY_INFO';
		return $msg;
	}
	if(!checkEmpty($form['LID']))
	{
		$dob = validateDate($form['LID']);
		if($dob !== true)
		{
			$msg = $dob.' '._LBL_FOR.' '._LBL_LIC_ISSUE_DT;
			$show_tab_type = 'AGENCY_INFO';
			return $msg;
		}
	}
	if(!checkEmpty($form['LED']))
	{
		$dob = validateDate($form['LED']);
		if($dob !== true)
		{
			$msg = $dob.' '._LBL_FOR.' '._LBL_LIC_EXP_DT;
			$show_tab_type = 'AGENCY_INFO';
			return $msg;
		}
	}
		/* Compare the offer added date, show date and the expiration date. */
	if(compareDate($form['LED'], $form['LID'], 1))
	{
		$msg = _ALRT_VALID_LIC_EXPDATE;
		$show_tab_type = 'AGENCY_INFO';
		return $msg;
	}
	if(checkEmpty($form['workstreet1']))
	{
		$msg = str_replace('field',_LBL_WORK_ADDR1,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['workstreet1'])) && !checkLength($form['workstreet1'],100))
	{
		$msg = str_replace('%field%',_LBL_WORK_ADDR1,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['officephone']))
	{
		$msg = str_replace('field',_LBL_WORK_PH,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['officephone'])) && !checkLength($form['officephone'],20))
	{
		$msg = str_replace('%field%',_LBL_WORK_PH,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['officephone'])) && !validatePhone($form['officephone']))
	{
		$msg = str_replace('%field%',_LBL_WORK_PH,_ALRT_CHECK_PHONE);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['workstreet2'])) && !checkLength($form['workstreet2'],100))
	{
		$msg = str_replace('%field%',_LBL_WORK_ADDR2,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['officephone_direct'])) && !checkLength($form['officephone_direct'],20))
	{
		$msg = str_replace('%field%',_LBL_WORK_PH(_LBL_DIRECT),_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['officephone_direct'])) && !validatePhone($form['officephone_direct']))
	{
		$msg = str_replace('%field%',_LBL_WORK_PH(_LBL_DIRECT),_ALRT_CHECK_PHONE);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['workcity']))
	{
		$msg = str_replace('field',_LBL_WORK_CITY,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['workcity'])) && !checkLength($form['workcity'],80))
	{
		$msg = str_replace('%field%',_LBL_WORK_CITY,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['mobile'])) && !checkLength($form['mobile'],20))
	{
		$msg = str_replace('%field%',_LBL_MO_NO,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['mobile'])) && !validatePhone($form['mobile']))
	{
		$msg = str_replace('%field%',_LBL_MO_NO,_ALRT_CHECK_PHONE);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['workstate']))
	{
		$msg = str_replace('field',_LBL_WORK_STATE,_ALRT_REQUIRED_SELECT);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['workstate'])) && !checkLength($form['workstate'],80))
	{
		$msg = str_replace('%field%',_LBL_WORK_STATE,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['fax'])) && !checkLength($form['fax'],20))
	{
		$msg = str_replace('%field%',_LBL_FAX.'('._LBL_MAIN.')',_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['fax'])) && !validateFax($form['fax']))
	{
		$msg = str_replace('%field%',_LBL_FAX.'('._LBL_MAIN.')',_ALRT_CHECK_VALID);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['workcountry']))
	{
		$msg = str_replace('field',_LBL_WORK_COUNTRY,_ALRT_REQUIRED_SELECT);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['workcountry'])) && !checkLength($form['workcountry'],80))
	{
		$msg = str_replace('%field%',_LBL_WORK_COUNTRY,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['fax_direct'])) && !checkLength($form['fax_direct'],20))
	{
		$msg = str_replace('%field%',_LBL_FAX.'('._LBL_DIRECT.')',_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['fax_direct'])) && !validateFax($form['fax_direct']))
	{
		$msg = str_replace('%field%',_LBL_FAX.'('._LBL_DIRECT.')',_ALRT_CHECK_VALID);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['workzip']))
	{
		$msg = str_replace('field',_LBL_WORK_ZIP,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['workzip'])) && !checkLength($form['workzip'],15))
	{
		$msg = str_replace('%field%',_LBL_WORK_ZIP,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['workzip'])) && !validateZip($form['workzip']))
	{
		$msg = str_replace('%field%',_LBL_WORK_ZIP,_ALRT_CHECK_ZIP);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['PHOTO_temp']))
	{
		if(checkEmpty($file['photo']['name']))
		{
			$msg = str_replace('field',_LBL_PRI_PHOTO,_ALRT_REQUIRED_FIELD);	
			$show_tab_type = 'UPLOAD_INFO';
			return $msg;
		}
	}
	if(!checkEmpty($file['photo']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg');
		$validate_img = validateImage($file['photo'], _LBL_PRI_PHOTO , $allowed_extensions);

		if($validate_img !== true)
		{
			$show_tab_type = 'UPLOAD_INFO';
			return $validate_img;
		}
	}
	
	/*if(!checkEmpty($file['liccopy']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg');
		$validate_img = validateImage($file['liccopy'], _LBL_LIC_COPY , $allowed_extensions);

		if($validate_img !== true)
		{
			$show_tab_type = 'UPLOAD_INFO';
			return $validate_img;
		}
	}*/
	
	return true;
}

/****************** Validation functions for artist profile. *********************/
function validateArtistProfile($form, $file)
{
	global $show_tab_type;

	if(checkEmpty($form['firstname']))
	{
		$msg = str_replace('field',_LBL_FIRST_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['firstname'])) && !checkLength($form['firstname'],255))
	{
		$msg = str_replace('%field%',_LBL_FIRST_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['lastname']))
	{
		$msg = str_replace('field',_LBL_LAST_NAME,_ALRT_REQUIRED_FIELD);	
		return $msg;
	}
	if((!checkEmpty($form['lastname'])) && !checkLength($form['lastname'],255))
	{
		$msg = str_replace('%field%',_LBL_LAST_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['bandname']))
	{
		$msg = str_replace('field',_LBL_ARTIST_OR_BAND,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['bandname'])) && !checkLength($form['bandname'],200))
	{
		$msg = str_replace('%field%',_LBL_ARTIST_OR_BAND,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	/*if(checkEmpty($form['highfee']))
	{
		$msg = str_replace('field',_LBL_HIGH_FEES,_ALRT_REQUIRED_FIELD);
		return $msg;
	}*/	
	/*if(checkEmpty($form['pri_email']))
	{
		$msg = str_replace('field',_LBL_PRI_EMAIL,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['pri_email'])) && !checkLength($form['pri_email'],255))
	{
		$msg = str_replace('%field%',_LBL_PRI_EMAIL,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['pri_email'])) && !validateEmail($form['pri_email']))
	{
		$msg = str_replace('field',_LBL_PRI_EMAIL,_ALRT_VALID_FIELD);
		return $msg;
	}*/
	if((!checkEmpty($form['lowfee'])) && !checkNumeric($form['lowfee']))
	{
		$msg = str_replace('%field%',_LBL_LOW_FEES,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['lowfee'])) && (($form['lowfee'] > 0) && ($form['lowfee'] < 1000)))
	{
		$msg = '$1000 Minimum. YOU ARE WORTH IT! xebura Talent may not list a fee less than $1000. <br>You can still receive and accept offers of any amount.';			
		return $msg;
	}
	if((!checkEmpty($form['lowfee'])) && !checkNumericRange($form['lowfee'],'double',12))
	{
		$msg = str_replace('%field%',_LBL_LOW_FEES,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['highfee'])) && !checkNumeric($form['highfee']))
	{
		$msg = str_replace('%field%',_LBL_HIGH_FEES,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['highfee'])) && !checkNumericRange($form['highfee'],'double',12))
	{
		$msg = str_replace('%field%',_LBL_HIGH_FEES,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if($form['lowfee'] > $form['highfee'])
	{
		$msg = _ALRT_HIGN_VER_LOW1;	
		return $msg;
	}
	if(checkEmpty($form['yearfound']))
	{
		$msg = str_replace('field',_LBL_YR_FOUNDED,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['yearfound'])) && !checkLength($form['yearfound'],4))
	{
		$msg = str_replace('%field%',_LBL_YR_FOUNDED,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['yearfound'])) && !checkValidYear($form['yearfound']))
	{
		$msg = str_replace('field',_LBL_YR_FOUNDED,_ALRT_VALID_FIELD);
		return $msg;
	}
	
	// 2008-12-05 pkolas
	if( !checkEmpty($form['profile_url']))
	{
	
		if (strlen($form['profile_url']) < 2) {
			$msg = 'Profile URL is too short (min 2 chars)';
			return $msg;
		}
		
		if (strlen($form['profile_url']) > 50) {
			$msg = 'Profile URL is too long (max 50 chars)';
			return $msg;
		}
		
		if (!preg_match("/^[a-z0-9_]+$/", $form['profile_url'])) {
			$msg = "Profile URL must contain only alphanumeric characters (a-z, 0-9 and '_')";
			return $msg;
		}
		
		if (is_numeric($_SESSION['Member_Id'])) {
			$sql = "
				SELECT 1
				FROM `xebura_ARTIST` 
				WHERE `AF_ARTIST_PROFILEURL` = '" . addslashes($form['profile_url']) . "'
				  AND `AF_ARTIST_MID` <> '" . $_SESSION['Member_Id'] . "'
			";
			global $db;
			$r = $db->query($sql);
			if ($db->getNumRows($r) > 0) {
				$msg = "'" . $form['profile_url'] . "' is already taken, please try another name";
				return $msg;
			}
		}
	}
	// 2008-12-05 pkolas end
	
	if((!checkEmpty($form['review'])) && !checkLength($form['review'],1))
	{
		$msg = str_replace('%field%',_LBL_REVIEW_ENABLED,_ALRT_CHECK_LENGTH);
		return $msg;
	}	
	if(checkEmpty($form['banddesc']))
	{
		$msg = _ALRT_BRIEF_ARTIST_DESC;
		$show_tab_type = 'BIO_INFO';
		return $msg;
	}
	/*if((!checkEmpty($form['discg_year'])) && !checkLength($form['discg_year'],4))
	{
		$msg = str_replace('%field%',_LBL_YEAR,_ALRT_CHECK_LENGTH);	
		$show_tab_type = 'DISCG_INFO';
		return $msg;	
	}
	if((!checkEmpty($form['discg_year'])) && !checkValidYear($form['discg_year']))
	{
		$msg = str_replace('field',_LBL_YEAR,_ALRT_VALID_FIELD);	
		$show_tab_type = 'DISCG_INFO';
		return $msg;	
	}
	if((!checkEmpty($form['discg_album'])) && !checkLength($form['discg_album'],255))
	{
		$msg = str_replace('%field%',_LBL_ALBUM,_ALRT_CHECK_LENGTH);	
		$show_tab_type = 'DISCG_INFO';
		return $msg;	
	}
	if((!checkEmpty($form['discg_label'])) && !checkLength($form['discg_label'],255))
	{
		$msg = str_replace('%field%',_LBL_LABEL,_ALRT_CHECK_LENGTH);	
		$show_tab_type = 'DISCG_INFO';
		return $msg;	
	}
	if(!checkEmpty($form['discg_year']))
	{
		if(checkEmpty($form['discg_album']))
		{
			$msg = str_replace('field',_LBL_ALBUM,_ALRT_REQUIRED_FIELD);	
			$show_tab_type = 'DISCG_INFO';
			return $msg;
		}
		else if(checkEmpty($form['discg_label']))
		{
			$msg = str_replace('field',_LBL_LABEL,_ALRT_REQUIRED_FIELD);	
			$show_tab_type = 'DISCG_INFO';
			return $msg;
		}
	}
	if(!checkEmpty($form['discg_album']))
	{
		if(checkEmpty($form['discg_year']))
		{
			$msg = str_replace('field',_LBL_YEAR,_ALRT_REQUIRED_FIELD);	
			$show_tab_type = 'DISCG_INFO';
			return $msg;
		}
		else if(checkEmpty($form['discg_label']))
		{
			$msg = str_replace('field',_LBL_LABEL,_ALRT_REQUIRED_FIELD);	
			$show_tab_type = 'DISCG_INFO';
			return $msg;
		}
	}
	if(!checkEmpty($form['discg_label']))
	{
		if(checkEmpty($form['discg_year']))
		{
			$msg = str_replace('field',_LBL_YEAR,_ALRT_REQUIRED_FIELD);	
			$show_tab_type = 'DISCG_INFO';
			return $msg;
		}
		else if(checkEmpty($form['discg_album']))
		{
			$msg = str_replace('field',_LBL_ALBUM,_ALRT_REQUIRED_FIELD);	
			$show_tab_type = 'DISCG_INFO';
			return $msg;
		}		
	}*/
	if(checkEmpty($form['genre1']))
	{
		$msg = str_replace('field',_LBL_GENRE1,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'GENRE_INFO';
		return $msg;
	}
	if((!checkEmpty($form['genre1'])) && !checkLength($form['genre1'],25))
	{
		$msg = str_replace('%field%',_LBL_GENRE1,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'GENRE_INFO';
		return $msg;
	}
	if((!checkEmpty($form['genre2'])) && !checkLength($form['genre2'],25))
	{
		$msg = str_replace('%field%',_LBL_GENRE2,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'GENRE_INFO';
		return $msg;
	}
	if((!checkEmpty($form['genre3'])) && !checkLength($form['genre3'],25))
	{
		$msg = str_replace('%field%',_LBL_GENRE3,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'GENRE_INFO';
		return $msg;
	}
	if(checkEmpty($form['street1']))
	{
		$msg = str_replace('field',_LBL_MAILING_ADDR1,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['street1'])) && !checkLength($form['street1'],100))
	{
		$msg = str_replace('%field%',_LBL_MAILING_ADDR1,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['altmailid'])) && !checkLength($form['altmailid'],100))
	{
		$msg = str_replace('%field%',_LBL_ALTERNATE_EMAIL,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['altmailid'])) && !validateEmail($form['altmailid']))
	{
		$msg = str_replace('field',_LBL_ALTERNATE_EMAIL,_ALRT_VALID_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['street2'])) && !checkLength($form['street2'],100))
	{
		$msg = str_replace('%field%',_LBL_MAILING_ADDR2,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}	
	if(checkEmpty($form['phone']))
	{
		$msg = str_replace('field',_LBL_PH_NO,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['phone'])) && !checkLength($form['phone'],20))
	{
		$msg = str_replace('%field%',_LBL_PH_NO,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['phone'])) && !validatePhone($form['phone']))
	{
		$msg = str_replace('%field%',_LBL_PH_NO,_ALRT_CHECK_PHONE);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['city']))
	{
		$msg = str_replace('field',_CITY,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['city'])) && !checkLength($form['city'],80))
	{
		$msg = str_replace('%field%',_CITY,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['cell'])) && !checkLength($form['cell'],20))
	{
		$msg = str_replace('%field%',_LBL_MO_NO,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['cell'])) && !validatePhone($form['cell']))
	{
		$msg = str_replace('%field%',_LBL_MO_NO,_ALRT_CHECK_PHONE);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['state']))
	{
		$msg = str_replace('field',_LBL_STATE,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['state'])) && !checkLength($form['state'],80))
	{
		$msg = str_replace('%field%',_LBL_STATE,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['fax'])) && !checkLength($form['fax'],20))
	{
		$msg = str_replace('%field%',_LBL_FAX_NO,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['fax'])) && !validateFax($form['fax']))
	{
		$msg = str_replace('%field%',_LBL_FAX_NO,_ALRT_CHECK_VALID);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['country']))
	{
		$msg = str_replace('field',_LBL_COUNTRY,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['country'])) && !checkLength($form['country'],80))
	{
		$msg = str_replace('%field%',_LBL_COUNTRY,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['web'])) && !checkLength($form['web'],200))
	{
		$msg = str_replace('%field%',_LBL_ARTIST_WEB,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['web'])) && !validateURL($form['web']))
	{
		$msg = str_replace('%field%',_LBL_ARTIST_WEB,_ALRT_CHECK_URL);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['zip']))
	{
		$msg = str_replace('field',_LBL_ZIP_POSTALCODE,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['zip'])) && !checkLength($form['zip'],15))
	{
		$msg = str_replace('%field%',_LBL_ZIP_POSTALCODE,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['zip'])) && !validateZip($form['zip']))
	{
		$msg = str_replace('%field%',_LBL_ZIP,_ALRT_CHECK_ZIP);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	
	if(checkEmpty($form['photo_temp']))
	{
		if(checkEmpty($file['photo']['name']))
		{
			$msg = str_replace('field',_LBL_PRI_ARTIST_PHOTO,_ALRT_REQUIRED_FIELD);	
			$show_tab_type = 'UPLOAD_INFO';
			return $msg;
		}
	}
	if(!checkEmpty($file['photo']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg');
		$validate_img = validateImage($file['photo'], _LBL_PRI_ARTIST_PHOTO , $allowed_extensions);

		if($validate_img !== true)
		{
			$show_tab_type = 'UPLOAD_INFO';
			return $validate_img;
		}
	}
	if(!checkEmpty($file['filename1']['name']) && checkEmpty($form['title1']))
	{
		$msg = str_replace('field',_LBL_TITLE1,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'DOWNLOAD_INFO';
		return $msg;
	}
	if(!checkEmpty($file['filename1']['name']) && checkEmpty($form['desc1']))
	{
		$msg = str_replace('field',_LBL_DESC1,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'DOWNLOAD_INFO';
		return $msg;
	}
	/* Validation for download3 center */
	if(!checkEmpty($file['filename1']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg','pdf','doc','zip');		
		$validate_img = validateDownloadFiles($file['filename1'], _LBL_FILE1 , $allowed_extensions);

		if($validate_img !== true)
		{
			$show_tab_type = 'DOWNLOAD_INFO';
			return $validate_img;
		}
	}
	if(!checkEmpty($file['filename1']['name']) && (!checkEmpty($form['title1'])) && !checkLength($form['title1'],20))
	{
		$msg = str_replace('%field%',_LBL_TITLE1,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'DOWNLOAD_INFO';
		return $msg;
	}
	if(!checkEmpty($file['filename1']['name']) && (!checkEmpty($form['desc1'])) && !checkLength($form['desc1'],100))
	{
		$msg = str_replace('%field%',_LBL_DESC1,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'DOWNLOAD_INFO';
		return $msg;
	}
	if(!checkEmpty($file['filename2']['name']) && checkEmpty($form['title2']))
	{
		$msg = str_replace('field',_LBL_TITLE2,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'DOWNLOAD_INFO';
		return $msg;
	}
	if(!checkEmpty($file['filename2']['name']) && checkEmpty($form['desc2']))
	{
		$msg = str_replace('field',_LBL_DESC2,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'DOWNLOAD_INFO';
		return $msg;
	}
	if(!checkEmpty($file['filename2']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg','pdf','doc','zip');
		$validate_img = validateDownloadFiles($file['filename2'], _LBL_FILE2 , $allowed_extensions);

		if($validate_img !== true)
		{
			$show_tab_type = 'DOWNLOAD_INFO';
			return $validate_img;
		}
	}
	if(!checkEmpty($file['filename2']['name']) && (!checkEmpty($form['title2'])) && !checkLength($form['title2'],20))
	{
		$msg = str_replace('%field%',_LBL_TITLE2,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'DOWNLOAD_INFO';
		return $msg;
	}
	if(!checkEmpty($file['filename2']['name']) && (!checkEmpty($form['desc2'])) && !checkLength($form['desc2'],100))
	{
		$msg = str_replace('%field%',_LBL_DESC2,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'DOWNLOAD_INFO';
		return $msg;
	}
	if(!checkEmpty($file['filename3']['name']) && checkEmpty($form['title3']))
	{
		$msg = str_replace('field',_LBL_TITLE3,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'DOWNLOAD_INFO';
		return $msg;
	}
	if(!checkEmpty($file['filename3']['name']) && checkEmpty($form['desc3']))
	{
		$msg = str_replace('field',_LBL_DESC3,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'DOWNLOAD_INFO';
		return $msg;
	}
	if(!checkEmpty($file['filename3']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg','pdf','doc','zip');
		$validate_img = validateDownloadFiles($file['filename3'], _LBL_FILE3 , $allowed_extensions);

		if($validate_img !== true)
		{
			$show_tab_type = 'DOWNLOAD_INFO';
			return $validate_img;
		}
	}
	if(!checkEmpty($file['filename3']['name']) && (!checkEmpty($form['title3'])) && !checkLength($form['title3'],20))
	{
		$msg = str_replace('%field%',_LBL_TITLE3,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'DOWNLOAD_INFO';
		return $msg;
	}
	if(!checkEmpty($file['filename3']['name']) && (!checkEmpty($form['desc3'])) && !checkLength($form['desc3'],100))
	{
		$msg = str_replace('%field%',_LBL_DESC3,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'DOWNLOAD_INFO';
		return $msg;
	}	
	return true;
}

/****************** Validation functions for manager profile. *********************/
function validateManagerProfile($form, $file)
{
	global $show_tab_type;

	if(checkEmpty($form['FIRSTNAME']))
	{
		$msg = str_replace('field',_LBL_FIRST_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['FIRSTNAME'])) && !checkLength($form['FIRSTNAME'],255))
	{
		$msg = str_replace('%field%',_LBL_FIRST_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	/*if(checkEmpty($form['PRI_EMAIL']))
	{
		$msg = str_replace('field',_LBL_PRI_EMAIL,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['PRI_EMAIL'])) && !checkLength($form['PRI_EMAIL'],255))
	{
		$msg = str_replace('%field%',_LBL_PRI_EMAIL,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['PRI_EMAIL'])) && !validateEmail($form['PRI_EMAIL']))
	{
		$msg = str_replace('field',_LBL_PRI_EMAIL,_ALRT_VALID_FIELD);
		return $msg;
	}*/
	
	if(checkEmpty($form['LASTNAME']))
	{
		$msg = str_replace('field',_LBL_LAST_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['LASTNAME'])) && !checkLength($form['LASTNAME'],255))
	{
		$msg = str_replace('%field%',_LBL_LAST_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['HOMEPHONE'])) && !checkLength($form['HOMEPHONE'],20))
	{
		$msg = str_replace('%field%',_LBL_HOME_PHONE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['HOMEPHONE'])) && !validatePhone($form['HOMEPHONE']))
	{
		$msg = str_replace('%field%',_LBL_HOME_PHONE,_ALRT_CHECK_PHONE);
		return $msg;
	}	
	/*if(checkEmpty($form['CONTACTNAME']))
	{
		$msg = str_replace('field',_LBL_CONTACT_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}*/
	if((!checkEmpty($form['CONTACTNAME'])) && !checkLength($form['CONTACTNAME'],100))
	{
		$msg = str_replace('%field%',_LBL_CONTACT_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['SECMAILID'])) && !checkLength($form['SECMAILID'],100))
	{
		$msg = str_replace('%field%',_LBL_SEC_EMAIL,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['SECMAILID'])) && !validateEmail($form['SECMAILID']))
	{
		$msg = str_replace('field',_LBL_SEC_EMAIL,_ALRT_VALID_FIELD);
		return $msg;
	}
	if(checkEmpty($form['COMPANYNAME']))
	{
		$msg = str_replace('field',_LBL_COMPANY_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['COMPANYNAME'])) && !checkLength($form['COMPANYNAME'],100))
	{
		$msg = str_replace('%field%',_LBL_COMPANY_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['HOMESTREET1']))
	{
		$msg = str_replace('field',_LBL_HOME_STREET1,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['HOMESTREET1'])) && !checkLength($form['HOMESTREET1'],100))
	{
		$msg = str_replace('%field%',_LBL_HOME_STREET1,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(!isset($form['GENDER']) || (trim($form['GENDER']) == ''))
	{
		$msg = str_replace('field',_LBL_GENDER,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['GENDER'])) && !checkLength($form['GENDER'],1))
	{
		$msg = str_replace('%field%',_LBL_GENDER,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['HOMESTREET2'])) && !checkLength($form['HOMESTREET2'],100))
	{
		$msg = str_replace('%field%',_LBL_HOME_STREET2,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['ASSISTANTNAME'])) && !checkLength($form['ASSISTANTNAME'],100))
	{
		$msg = str_replace('%field%',_LBL_ASS_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['CITY']))
	{
		$msg = str_replace('field',_CITY,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['CITY'])) && !checkLength($form['CITY'],80))
	{
		$msg = str_replace('%field%',_CITY,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['ASSISTANTEMAIL'])) && !checkLength($form['ASSISTANTEMAIL'],100))
	{
		$msg = str_replace('%field%',_LBL_ASS_EMAIL,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['ASSISTANTEMAIL'])) && !validateEmail($form['ASSISTANTEMAIL']))
	{
		$msg = str_replace('field',_LBL_ASS_EMAIL,_ALRT_VALID_FIELD);
		return $msg;
	}
	if(checkEmpty($form['STATE']))
	{
		$msg = str_replace('field',_LBL_STATE,_ALRT_REQUIRED_SELECT);
		return $msg;
	}
	if((!checkEmpty($form['STATE'])) && !checkLength($form['STATE'],80))
	{
		$msg = str_replace('%field%',_LBL_STATE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['dob']))
	{
		$msg = str_replace('field',_LBL_DOB,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if(!checkEmpty($form['dob']))
	{
		$dob = validateDate($form['dob']);
		if($dob !== true)
		{
			$msg = $dob.' '._LBL_FOR.' '._LBL_DOB;;
			return $msg;
		}
	}	
	if(checkEmpty($form['COUNTRY']))
	{
		$msg = str_replace('field',_LBL_COUNTRY,_ALRT_REQUIRED_SELECT);
		return $msg;
	}
	if((!checkEmpty($form['COUNTRY'])) && !checkLength($form['COUNTRY'], 80))
	{
		$msg = str_replace('%field%',_LBL_COUNTRY,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['EXPERIENCE']))
	{
		$msg = str_replace('field',_LBL_MANAGING_TALENT,_ALRT_REQUIRED_SELECT);
		return $msg;
	}
	if((!checkEmpty($form['EXPERIENCE'])) && !checkLength($form['EXPERIENCE'],4))
	{
		$msg = str_replace('%field%',_LBL_MANAGING_TALENT,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['EXPERIENCE'])) && !checkValidYear($form['EXPERIENCE']))
	{
		$msg = str_replace('field',_LBL_MANAGING_TALENT,_ALRT_VALID_FIELD);
		return $msg;
	}
	if(checkEmpty($form['ZIP']))
	{
		$msg = str_replace('field',_LBL_ZIP,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['ZIP'])) && !checkLength($form['ZIP'],15))
	{
		$msg = str_replace('%field%',_LBL_ZIP,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['ZIP'])) && !validateZip($form['ZIP']))
	{
		$msg = str_replace('%field%',_LBL_ZIP,_ALRT_CHECK_ZIP);
		return $msg;
	}
	if((!checkEmpty($form['URL'])) && !checkLength($form['URL'],200))
	{
		$msg = str_replace('%field%',_LBL_URL,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['URL'])) && !validateURL($form['URL']))
	{
		$msg = str_replace('%field%',_LBL_URL,_ALRT_CHECK_URL);
		return $msg;
	}
	/*if(checkEmpty($form['BRIEFDESC']))
	{
		$msg = _ALRT_BRIEF_DESC;
		$show_tab_type = 'bio';
		return $msg;
	}
	if(checkEmpty($form['FULLBIO']))
	{
		$msg = str_replace('field',_LBL_FULL_BIO,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'bio';
		return $msg;
	}*/
	if(checkEmpty($form['WORKSTREET1']))
	{
		$msg = str_replace('field',_LBL_WORK_STREET1,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'WORK_INFO';
		return $msg;
	}
	if((!checkEmpty($form['WORKSTREET1'])) && !checkLength($form['WORKSTREET1'],100))
	{
		$msg = str_replace('%field%',_LBL_WORK_STREET1,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'WORK_INFO';
		return $msg;
	}
	if(checkEmpty($form['WORKZIP']))
	{
		$msg = str_replace('field',_LBL_ZIP,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'WORK_INFO';
		return $msg;
	}
	if((!checkEmpty($form['WORKZIP'])) && !checkLength($form['WORKZIP'],15))
	{
		$msg = str_replace('%field%',_LBL_ZIP,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'WORK_INFO';
		return $msg;
	}
	if((!checkEmpty($form['WORKZIP'])) && !validateZip($form['WORKZIP']))
	{
		$msg = str_replace('%field%',_LBL_ZIP,_ALRT_CHECK_ZIP);
		$show_tab_type = 'WORK_INFO';
		return $msg;
	}
	if((!checkEmpty($form['WORKSTREET2'])) && !checkLength($form['WORKSTREET2'],100))
	{
		$msg = str_replace('%field%',_LBL_WORK_STREET2,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'WORK_INFO';
		return $msg;
	}
	if((!checkEmpty($form['OFFICEPHONE'])) && !checkLength($form['OFFICEPHONE'],20))
	{
		$msg = str_replace('%field%',_LBL_OFFICE_PHONE,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'WORK_INFO';
		return $msg;
	}
	if(checkEmpty($form['OFFICEPHONE']))
	{
		$msg = str_replace('field',_LBL_OFFICE_PHONE,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'WORK_INFO';
		return $msg;
	}
	if((!checkEmpty($form['OFFICEPHONE'])) && !validatePhone($form['OFFICEPHONE']))
	{
		$msg = str_replace('%field%',_LBL_OFFICE_PHONE,_ALRT_CHECK_PHONE);
		$show_tab_type = 'WORK_INFO';
		return $msg;
	}
	if(checkEmpty($form['WORKCITY']))
	{
		$msg = str_replace('field',_CITY,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'WORK_INFO';
		return $msg;
	}
	if((!checkEmpty($form['WORKCITY'])) && !checkLength($form['WORKCITY'],80))
	{
		$msg = str_replace('%field%',_CITY,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'WORK_INFO';
		return $msg;
	}
	if((!checkEmpty($form['MOBILE'])) && !checkLength($form['MOBILE'],20))
	{
		$msg = str_replace('%field%',_LBL_MO_NO,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'WORK_INFO';
		return $msg;
	}
	if((!checkEmpty($form['MOBILE'])) && !validatePhone($form['MOBILE']))
	{
		$msg = str_replace('%field%',_LBL_MO_NO,_ALRT_CHECK_PHONE);
		$show_tab_type = 'WORK_INFO';
		return $msg;
	}
	if(checkEmpty($form['WORKSTATE']))
	{
		$msg = str_replace('field',_LBL_STATE,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'WORK_INFO';
		return $msg;
	}
	if((!checkEmpty($form['WORKSTATE'])) && !checkLength($form['WORKSTATE'],80))
	{
		$msg = str_replace('%field%',_LBL_STATE,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'WORK_INFO';
		return $msg;
	}
	if((!checkEmpty($form['FAX'])) && !checkLength($form['FAX'],20))
	{
		$msg = str_replace('%field%',_LBL_FAX,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'WORK_INFO';
		return $msg;
	}
	if((!checkEmpty($form['FAX'])) && !validateFax($form['FAX']))
	{
		$msg = str_replace('%field%',_LBL_FAX_NO,_ALRT_CHECK_VALID);
		$show_tab_type = 'WORK_INFO';
		return $msg;
	}
	if(checkEmpty($form['WORKCOUNTRY']))
	{
		$msg = str_replace('field',_LBL_COUNTRY,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'WORK_INFO';
		return $msg;
	}
	if((!checkEmpty($form['WORKCOUNTRY'])) && !checkLength($form['WORKCOUNTRY'],80))
	{
		$msg = str_replace('%field%',_LBL_COUNTRY,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'WORK_INFO';
		return $msg;
	}
	if((!checkEmpty($form['EMPLOYEDBY'])) && !checkLength($form['EMPLOYEDBY'],50))
	{
		$msg = str_replace('%field%',_LBL_EMP_BY,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if(checkEmpty($form['JOBTITLE']))
	{
		$msg = str_replace('field',_LBL_JOB_TITLE,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['REPORTSTO'])) && !checkLength($form['REPORTSTO'],50))
	{
		$msg = str_replace('%field%',_LBL_REPO_TO,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if(!checkEmpty($file['PHOTO']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg');
		$validate_img = validateImage($file['PHOTO'], _LBL_PRI_PHOTO , $allowed_extensions);

		if($validate_img !== true)
		{
			$show_tab_type = 'UPLOAD_INFO';
			return $validate_img;
		}
	}
	if(checkEmpty($form['PHOTO_temp']))
	{
		if(checkEmpty($file['PHOTO']['name']))
		{
			$msg = str_replace('field',_LBL_PRI_PHOTO,_ALRT_REQUIRED_FIELD);	
			$show_tab_type = 'UPLOAD_INFO';
			return $msg;
		}
	}	
	/*if(!checkEmpty($file['LICCOPY']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg');
		$validate_img = validateImage($file['LICCOPY'], _LBL_LIC_COPY , $allowed_extensions);

		if($validate_img !== true)
		{
			$show_tab_type = 'upload';
			return $validate_img;
		}
	}*/
	return true;
}
/****************** Validation functions for buyer profile. *********************/
function validateBuyerProfile($form, $file)
{
	global $show_tab_type;

	if(checkEmpty($form['firstname']))
	{
		$msg = str_replace('field',_LBL_FIRST_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['firstname'])) && !checkLength($form['firstname'],255))
	{
		$msg = str_replace('%field%',_LBL_FIRST_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['lastname']))
	{
		$msg = str_replace('field',_LBL_LAST_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['lastname'])) && !checkLength($form['lastname'],255))
	{
		$msg = str_replace('%field%',_LBL_LAST_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['companyname']))
	{
		$msg = str_replace('field',_LBL_COMPANY_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['companyname'])) && !checkLength($form['companyname'],100))
	{
		$msg = str_replace('%field%',_LBL_COMPANY_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}		
	if(checkEmpty($form['contactname']))
	{
		$msg = str_replace('field',_LBL_CON_PERSON,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['contactname'])) && !checkLength($form['contactname'],100))
	{
		$msg = str_replace('%field%',_LBL_CON_PERSON,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['holdername']))
	{
		$msg = str_replace('field',_LBL_HOLDER_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['holdername'])) && !checkLength($form['holdername'],100))
	{
		$msg = str_replace('%field%',_LBL_HOLDER_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['titleposition']))
	{
		$msg = str_replace('field',_LBL_TITLE.' ('._LBL_POS.')',_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['titleposition'])) && !checkLength($form['titleposition'],100))
	{
		$msg = str_replace('%field%',_LBL_TITLE.' ('._LBL_POS.')',_ALRT_CHECK_LENGTH);
		return $msg;
	}
	/*if(checkEmpty($form['pri_email']))
	{
		$msg = str_replace('field',_LBL_PRI_EMAIL,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['pri_email'])) && !checkLength($form['pri_email'],255))
	{
		$msg = str_replace('%field%',_LBL_PRI_EMAIL,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['pri_email'])) && !validateEmail($form['pri_email']))
	{
		$msg = str_replace('field',_LBL_PRI_EMAIL,_ALRT_VALID_FIELD);
		return $msg;
	}	*/
	if(checkEmpty($form['GENRE']))
	{
		$msg = str_replace('field',_LBL_GENRES_BOOKED,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if(checkEmpty($form['type_entertainment']))
	{
		$msg = str_replace('field',_LBL_TYPE_OF_ENT,_ALRT_REQUIRED_SELECT);
		return $msg;
	}
	if(checkEmpty($form['briefdesc']))
	{
		$msg = str_replace('field',_LBL_BRIEF_DESC,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'BIO_INFO';
		return $msg;
	}
	/*if(checkEmpty($form['fullbio']))
	{
		$msg = str_replace('field',_LBL_FULL_BIO,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'BIO_INFO';
		return $msg;
	}	*/
	if(checkEmpty($form['yearfound']))
	{
		$msg = str_replace('field',_LBL_YR_FOUNDED,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['yearfound'])) && !checkLength($form['yearfound'],4))
	{
		$msg = str_replace('%field%',_LBL_YR_FOUNDED,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['yearfound'])) && !checkValidYear($form['yearfound']))
	{
		$msg = str_replace('field',_LBL_YR_FOUNDED,_ALRT_VALID_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if(checkEmpty($form['phone']))
	{
		$msg = str_replace('field',_LBL_PH_NO,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['phone'])) && !checkLength($form['phone'],20))
	{
		$msg = str_replace('%field%',_LBL_PH_NO,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['phone'])) && !validatePhone($form['phone']))
	{
		$msg = str_replace('%field%',_LBL_PH_NO,_ALRT_CHECK_PHONE);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if(checkEmpty($form['url']))
	{
		$msg = str_replace('field',_LBL_COMP_URL,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}	
	if((!checkEmpty($form['url'])) && !checkLength($form['url'],200))
	{
		$msg = str_replace('%field%',_LBL_COMP_URL,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['url'])) && !validateURL($form['url']))
	{
		$msg = str_replace('%field%',_LBL_COMP_URL,_ALRT_CHECK_URL);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['tax'])) && !checkLength($form['tax'],20))
	{
		$msg = str_replace('%field%',_LBL_TAX_EIN,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	/*if(checkEmpty($form['tax']) && checkEmpty($form['duns']))
	{
		$msg = str_replace('field',_LBL_TAX_EIN.' '._LBL_OR.' '._LBL_DUNS.' '._LBL_DETAILS,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}*/
	if(checkEmpty($form['secemail']))
	{
		$msg = str_replace('field',_LBL_COMP_EMAIL_ID,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['secemail'])) && !checkLength($form['secemail'],100))
	{
		$msg = str_replace('%field%',_LBL_COMP_EMAIL,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['secemail'])) && !validateEmail($form['secemail']))
	{
		$msg = str_replace('field',_LBL_COMP_EMAIL,_ALRT_VALID_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['duns'])) && !checkLength($form['duns'],20))
	{
		$msg = str_replace('%field%',_LBL_DUNS,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}	
	if(checkEmpty($form['street1']))
	{
		$msg = str_replace('field',_LBL_WORK_ADDR1,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['street1'])) && !checkLength($form['street1'],100))
	{
		$msg = str_replace('%field%',_LBL_WORK_ADDR1,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['zip']))
	{
		$msg = str_replace('field',_LBL_WORK_ZIP,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['zip'])) && !checkLength($form['zip'],15))
	{
		$msg = str_replace('%field%',_LBL_WORK_ZIP,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['zip'])) && !validateZip($form['zip']))
	{
		$msg = str_replace('%field%',_LBL_WORK_ZIP,_ALRT_CHECK_ZIP);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['street2'])) && !checkLength($form['street2'],100))
	{
		$msg = str_replace('%field%',_LBL_WORK_ADDR2,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['conphone']))
	{
		$msg = str_replace('field',_LBL_PH_NO,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['conphone'])) && !checkLength($form['conphone'],20))
	{
		$msg = str_replace('%field%',_LBL_PH_NO,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['conphone'])) && !validatePhone($form['conphone']))
	{
		$msg = str_replace('%field%',_LBL_PH_NO,_ALRT_CHECK_PHONE);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['city']))
	{
		$msg = str_replace('field',_LBL_WORK_CITY,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['city'])) && !checkLength($form['city'],80))
	{
		$msg = str_replace('%field%',_LBL_WORK_CITY,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['mobile'])) && !checkLength($form['mobile'],20))
	{
		$msg = str_replace('%field%',_LBL_MO_NO,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['mobile'])) && !validatePhone($form['mobile']))
	{
		$msg = str_replace('%field%',_LBL_MO_NO,_ALRT_CHECK_PHONE);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['state']))
	{
		$msg = str_replace('field',_LBL_WORK_STATE,_ALRT_REQUIRED_SELECT);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['state'])) && !checkLength($form['state'],80))
	{
		$msg = str_replace('%field%',_LBL_WORK_STATE,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['fax'])) && !checkLength($form['fax'],20))
	{
		$msg = str_replace('%field%',_LBL_FAX_NO,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['fax'])) && !validateFax($form['fax']))
	{
		$msg = str_replace('%field%',_LBL_FAX_NO,_ALRT_CHECK_VALID);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['country']))
	{
		$msg = str_replace('field',_LBL_WORK_COUNTRY,_ALRT_REQUIRED_SELECT);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['country'])) && !checkLength($form['country'],80))
	{
		$msg = str_replace('%field%',_LBL_WORK_COUNTRY,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}	
	/*if(checkEmpty($form['logo']) && checkEmpty($form['logo_temp']))
	{
		$msg = str_replace('field',_LBL_UPLOAD_LOGO,_ALRT_REQUIRED_UPLOAD);
		$show_tab_type = 'UPLOAD_INFO';
		return $msg;
	}*/	
	if(!checkEmpty($file['logo']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg');
		$validate_img = validateImage($file['logo'], _LBL_UPLOAD_LOGO , $allowed_extensions);

		if($validate_img !== true)
		{
			$show_tab_type = 'UPLOAD_INFO';
			return $validate_img;
		}
	}	
	if(checkEmpty($form['primaryphoto_temp']))
	{
		if(checkEmpty($file['primaryphoto']['name']))
		{
			$msg = str_replace('field',_LBL_PRI_PHOTO,_ALRT_REQUIRED_UPLOAD);	
			$show_tab_type = 'UPLOAD_INFO';
			return $msg;
		}
	}
	if(!checkEmpty($file['primaryphoto']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg');
		$validate_img = validateImage($file['primaryphoto'], _LBL_PRI_PHOTO , $allowed_extensions);

		if($validate_img !== true)
		{
			$show_tab_type = 'UPLOAD_INFO';
			return $validate_img;
		}
	}
	return true;
}

/****************** Validation functions for promoter profile. *********************/
function validatePromoterProfile($form, $file)
{
	global $show_tab_type;

	if(checkEmpty($form['firstname']))
	{
		$msg = str_replace('field',_LBL_FIRST_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['firstname'])) && !checkLength($form['firstname'],255))
	{
		$msg = str_replace('%field%',_LBL_FIRST_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['lastname']))
	{
		$msg = str_replace('field',_LBL_LAST_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['lastname'])) && !checkLength($form['lastname'],255))
	{
		$msg = str_replace('%field%',_LBL_LAST_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['companyname']))
	{
		$msg = str_replace('field',_LBL_COMPANY_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['companyname'])) && !checkLength($form['companyname'],100))
	{
		$msg = str_replace('%field%',_LBL_COMPANY_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}	
	/*if(checkEmpty($form['contactname']))
	{
		$msg = str_replace('field',_LBL_CON_PERSON,_ALRT_REQUIRED_FIELD);
		return $msg;
	}*/
	if((!checkEmpty($form['contactname'])) && !checkLength($form['contactname'],100))
	{
		$msg = str_replace('%field%',_LBL_CON_PERSON,_ALRT_CHECK_LENGTH);
		return $msg;
	}	
	/*if(checkEmpty($form['holdername']))
	{
		$msg = str_replace('field',_LBL_HOLDER_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}*/
	/*if((!checkEmpty($form['holdername'])) && !checkLength($form['holdername'],100))
	{
		$msg = str_replace('%field%',_LBL_HOLDER_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}*/
	/*if(checkEmpty($form['pri_email']))
	{
		$msg = str_replace('field',_LBL_PRI_EMAIL,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['pri_email'])) && !checkLength($form['pri_email'],255))
	{
		$msg = str_replace('%field%',_LBL_PRI_EMAIL,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['pri_email'])) && !validateEmail($form['pri_email']))
	{
		$msg = str_replace('field',_LBL_PRI_EMAIL,_ALRT_VALID_FIELD);
		return $msg;
	}*/	
	if(checkEmpty($form['titleposition']))
	{
		$msg = str_replace('field',_LBL_TITLE.' ('._LBL_POS.')',_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['titleposition'])) && !checkLength($form['titleposition'],100))
	{
		$msg = str_replace('%field%',_LBL_TITLE.' ('._LBL_POS.')',_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['GENRE']))
	{
		$msg = str_replace('field',_LBL_GENRES_BOOKED,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if(checkEmpty($form['type_entertainment']))
	{
		$msg = str_replace('field',_LBL_TYPE_OF_ENT,_ALRT_REQUIRED_SELECT);
		return $msg;
	}
	if(checkEmpty($form['briefdesc']))
	{
		$msg = str_replace('field',_LBL_BRIEF_DESC,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'BIO_INFO';
		return $msg;
	}
	/*if(checkEmpty($form['fullbio']))
	{
		$msg = str_replace('field',_LBL_FULL_BIO,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'BIO_INFO';
		return $msg;
	}	*/
	if(checkEmpty($form['yearfound']))
	{
		$msg = str_replace('field',_LBL_YR_FOUNDED,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['yearfound'])) && !checkLength($form['yearfound'],4))
	{
		$msg = str_replace('%field%',_LBL_YR_FOUNDED,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['yearfound'])) && !checkValidYear($form['yearfound']))
	{
		$msg = str_replace('field',_LBL_YR_FOUNDED,_ALRT_VALID_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}	
	if(checkEmpty($form['phone']))
	{
		$msg = str_replace('field',_LBL_PH_NO,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['phone'])) && !checkLength($form['phone'],20))
	{
		$msg = str_replace('%field%',_LBL_PH_NO,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['phone'])) && !validatePhone($form['phone']))
	{
		$msg = str_replace('%field%',_LBL_PH_NO,_ALRT_CHECK_PHONE);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if(checkEmpty($form['url']))
	{
		$msg = str_replace('field',_LBL_COMP_URL,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['url'])) && !checkLength($form['url'],200))
	{
		$msg = str_replace('%field%',_LBL_COMP_URL,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['url'])) && !validateURL($form['url']))
	{
		$msg = str_replace('%field%',_LBL_COMP_URL,_ALRT_CHECK_URL);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['tax'])) && !checkLength($form['tax'],20))
	{
		$msg = str_replace('%field%',_LBL_TAX_EIN,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	/*if(checkEmpty($form['tax']) && checkEmpty($form['duns']))
	{
		$msg = str_replace('field',_LBL_TAX_EIN.' '._LBL_OR.' '._LBL_DUNS.' '._LBL_DETAILS,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}	*/
	if(checkEmpty($form['secemail']))
	{
		$msg = str_replace('field',_LBL_COMP_EMAIL_ID,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['secemail'])) && !checkLength($form['secemail'],100))
	{
		$msg = str_replace('%field%',_LBL_COMP_EMAIL,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['secemail'])) && !validateEmail($form['secemail']))
	{
		$msg = str_replace('field',_LBL_COMP_EMAIL,_ALRT_VALID_FIELD);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}	
	if((!checkEmpty($form['duns'])) && !checkLength($form['duns'],20))
	{
		$msg = str_replace('%field%',_LBL_DUNS,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if(checkEmpty($form['street1']))
	{
		$msg = str_replace('field',_LBL_WORK_ADDR1,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['street1'])) && !checkLength($form['street1'],100))
	{
		$msg = str_replace('%field%',_LBL_WORK_ADDR1,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['zip']))
	{
		$msg = str_replace('field',_LBL_WORK_ZIP,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['zip'])) && !checkLength($form['zip'],15))
	{
		$msg = str_replace('%field%',_LBL_WORK_ZIP,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['zip'])) && !validateZip($form['zip']))
	{
		$msg = str_replace('%field%',_LBL_WORK_ZIP,_ALRT_CHECK_ZIP);
			$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['street2'])) && !checkLength($form['street2'],100))
	{
		$msg = str_replace('%field%',_LBL_WORK_ADDR2,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['conphone']))
	{
		$msg = str_replace('field',_LBL_PH_NO,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['conphone'])) && !checkLength($form['conphone'],20))
	{
		$msg = str_replace('%field%',_LBL_PH_NO,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['conphone'])) && !validatePhone($form['conphone']))
	{
		$msg = str_replace('%field%',_LBL_PH_NO,_ALRT_CHECK_PHONE);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['city']))
	{
		$msg = str_replace('field',_LBL_WORK_CITY,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['city'])) && !checkLength($form['city'],80))
	{
		$msg = str_replace('%field%',_LBL_WORK_CITY,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['mobile'])) && !checkLength($form['mobile'],20))
	{
		$msg = str_replace('%field%',_LBL_MO_NO,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['mobile'])) && !validatePhone($form['mobile']))
	{
		$msg = str_replace('%field%',_LBL_MO_NO,_ALRT_CHECK_PHONE);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['state']))
	{
		$msg = str_replace('field',_LBL_WORK_STATE,_ALRT_REQUIRED_SELECT);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['state'])) && !checkLength($form['state'],80))
	{
		$msg = str_replace('%field%',_LBL_WORK_STATE,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['fax'])) && !checkLength($form['fax'],20))
	{
		$msg = str_replace('%field%',_LBL_FAX_NO,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['fax'])) && !validateFax($form['fax']))
	{
		$msg = str_replace('%field%',_LBL_FAX_NO,_ALRT_CHECK_VALID);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['country']))
	{
		$msg = str_replace('field',_LBL_WORK_COUNTRY,_ALRT_REQUIRED_SELECT);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['country'])) && !checkLength($form['country'],80))
	{
		$msg = str_replace('%field%',_LBL_WORK_COUNTRY,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	
	/*if(checkEmpty($form['logo']) && checkEmpty($form['logo_temp']))
	{
		$msg = str_replace('field',_LBL_UPLOAD_LOGO,_ALRT_REQUIRED_UPLOAD);
		$show_tab_type = 'UPLOAD_INFO';
		return $msg;
	}
	if(!checkEmpty($file['logo']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg');
		$validate_img = validateImage($file['logo'], _LBL_UPLOAD_LOGO , $allowed_extensions);

		if($validate_img !== true)
		{
			$show_tab_type = 'UPLOAD_INFO';
			return $validate_img;
		}
	}*/
	if(checkEmpty($form['photo_temp']))
	{
		if(checkEmpty($file['photo']['name']))
		{
			$msg = str_replace('field',_LBL_PRI_PHOTO,_ALRT_REQUIRED_UPLOAD);	
			$show_tab_type = 'UPLOAD_INFO';
			return $msg;
		}
	}	
	if(!checkEmpty($file['photo']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg');
		$validate_img = validateImage($file['photo'], _LBL_PRI_PHOTO , $allowed_extensions);

		if($validate_img !== true)
		{
			$show_tab_type = 'UPLOAD_INFO';
			return $validate_img;
		}
	}
	
	return true;
}

/****************** Validation functions for venue profile. *********************/
function validateVenueProfile($form,$file)
{
	global $show_tab_type;

	if(checkEmpty($form['firstname']))
	{
		$msg = str_replace('field',_LBL_FIRST_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['firstname'])) && !checkLength($form['firstname'],255))
	{
		$msg = str_replace('%field%',_LBL_FIRST_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['lastname']))
	{
		$msg = str_replace('field',_LBL_LAST_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['lastname'])) && !checkLength($form['lastname'],255))
	{
		$msg = str_replace('%field%',_LBL_LAST_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['VENUENAME']))
	{
		$msg = str_replace('field',_LBL_VENUE_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['VENUENAME'])) && !checkLength($form['VENUENAME'],100))
	{
		$msg = str_replace('%field%',_LBL_VENUE_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['MANAGERNAME']))
	{
		$msg = str_replace('field',_LBL_VEN_MANAGER,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['MANAGERNAME'])) && !checkLength($form['MANAGERNAME'],100))
	{
		$msg = str_replace('%field%',_LBL_VEN_MANAGER,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['JOBTITLE']))
	{
		$msg = str_replace('field',_LBL_JOB_TITLE,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['JOBTITLE'])) && !checkLength($form['JOBTITLE'],100))
	{
		$msg = str_replace('%field%',_LBL_JOB_TITLE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	/*if(checkEmpty($form['pri_email']))
	{
		$msg = str_replace('field',_LBL_PRI_EMAIL,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['pri_email'])) && !checkLength($form['pri_email'],255))
	{
		$msg = str_replace('%field%',_LBL_PRI_EMAIL,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['pri_email'])) && !validateEmail($form['pri_email']))
	{
		$msg = str_replace('field',_LBL_PRI_EMAIL,_ALRT_VALID_FIELD);
		return $msg;
	}*/
	if(checkEmpty($form['EMPLOYED_BY']))
	{
		$msg = str_replace('field',_LBL_EMP_BY,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['EMPLOYED_BY'])) && !checkLength($form['EMPLOYED_BY'],100))
	{
		$msg = str_replace('%field%',_LBL_EMP_BY,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['ABOUT_VENUE']))
	{
		$msg = _ALRT_DESC_VENUE;
		return $msg;
	}	
	if(checkEmpty($form['CONNAME']))
	{
		$msg = str_replace('field',_LBL_CONTACT_NAME,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['CONNAME'])) && !checkLength($form['CONNAME'],100))
	{
		$msg = str_replace('%field%',_LBL_CONTACT_NAME,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['MAILSTATE']))
	{
		$msg = str_replace('field',_LBL_MAILING_ST,_ALRT_REQUIRED_SELECT);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['MAILSTATE'])) && !checkLength($form['MAILSTATE'],80))
	{
		$msg = str_replace('%field%',_LBL_MAILING_ST,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['CONEMAIL']))
	{
		$msg = str_replace('field',_LBL_CON_EMAIL,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['CONEMAIL'])) && !checkLength($form['CONEMAIL'],100))
	{
		$msg = str_replace('%field%',_LBL_CON_EMAIL,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['CONEMAIL'])) && !validateEmail($form['CONEMAIL']))
	{
		$msg = str_replace('field',_LBL_CON_EMAIL,_ALRT_VALID_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['MAILCOUNTRY']))
	{
		$msg = str_replace('field',_LBL_MAILING_COUNTRY,_ALRT_REQUIRED_SELECT);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['MAILCOUNTRY'])) && !checkLength($form['MAILCOUNTRY'],80))
	{
		$msg = str_replace('%field%',_LBL_MAILING_COUNTRY,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['MAILSTREET1']))
	{
		$msg = str_replace('field',_LBL_MAILING_ADDR,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['MAILSTREET1'])) && !checkLength($form['MAILSTREET1'],100))
	{
		$msg = str_replace('%field%',_LBL_MAILING_ADDR,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['MAILZIP']))
	{
		$msg = str_replace('field',_LBL_MAILING_ZIP,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['MAILZIP'])) && !checkLength($form['MAILZIP'],15))
	{
		$msg = str_replace('%field%',_LBL_MAILING_ZIP,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['MAILZIP'])) && !validateZip($form['MAILZIP']))
	{
		$msg = str_replace('%field%',_LBL_MAILING_ZIP,_ALRT_CHECK_ZIP);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['MAILSTREET2'])) && !checkLength($form['MAILSTREET2'],100))
	{
		$msg = str_replace('%field%',_LBL_MAILING_ADDR,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['CONPHONE'])) && !checkLength($form['CONPHONE'],20))
	{
		$msg = str_replace('%field%',_LBL_PH_NO,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['CONPHONE'])) && !validatePhone($form['CONPHONE']))
	{
		$msg = str_replace('%field%',_LBL_PH_NO,_ALRT_CHECK_PHONE);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if(checkEmpty($form['MAILCITY']))
	{
		$msg = str_replace('field',_LBL_MAILING_CITY,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['MAILCITY'])) && !checkLength($form['MAILCITY'],80))
	{
		$msg = str_replace('%field%',_LBL_MAILING_CITY,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['CONFAX'])) && !checkLength($form['CONFAX'],20))
	{
		$msg = str_replace('%field%',_LBL_FAX_NO,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['CONFAX'])) && !validateFax($form['CONFAX']))
	{
		$msg = str_replace('%field%',_LBL_FAX_NO,_ALRT_CHECK_VALID);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}	
	if(checkEmpty($form['YEARSEXIST']))
	{
		$msg = str_replace('field',_LBL_YR_OPENED,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['YEARSEXIST'])) && !checkLength($form['YEARSEXIST'],4))
	{
		$msg = str_replace('%field%',_LBL_YR_OPENED,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['YEARSEXIST'])) && !checkValidYear($form['YEARSEXIST']))
	{
		$msg = str_replace('field',_LBL_YR_OPENED,_ALRT_VALID_FIELD);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['ALCOHOL'])) && !checkLength($form['ALCOHOL'],1))
	{
		$msg = str_replace('%field%',_LBL_ALCOHOL_SERVED,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if(checkEmpty($form['GENRE']))
	{
		$msg = str_replace('field',_LBL_ACCEPTED_GENRE,_ALRT_REQUIRED_SELECT);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if(checkEmpty($form['CAPACITY']))
	{
		$msg = str_replace('field',_LBL_TOT_CAPACITY,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['CAPACITY'])) && !checkNumeric($form['CAPACITY']))
	{
		$msg = str_replace('%field%',_LBL_TOT_CAPACITY,_ALRT_CHECK_NUMERIC);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['CAPACITY'])) && !checkNumericRange($form['CAPACITY'],11))
	{
		$msg = str_replace('%field%',_LBL_TOT_CAPACITY,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if(checkEmpty($form['VENUESTREET1']))
	{
		$msg = str_replace('field',_LBL_STRT_ADDR1,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['VENUESTREET1'])) && !checkLength($form['VENUESTREET1'],100))
	{
		$msg = str_replace('%field%',_LBL_STRT_ADDR1,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['VENUESTREET2'])) && !checkLength($form['VENUESTREET2'],100))
	{
		$msg = str_replace('%field%',_LBL_STRT_ADDR2,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}	
	if(checkEmpty($form['BRIEFDESC']))
	{
		$msg = str_replace('field',_LBL_BRIEF_DESC.' '._LBL_ABT_VENUE,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if(checkEmpty($form['CITY']))
	{
		$msg = str_replace('field',_CITY,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['CITY'])) && !checkLength($form['CITY'],80))
	{
		$msg = str_replace('%field%',_CITY,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if(checkEmpty($form['STATE']))
	{
		$msg = str_replace('field',_LBL_STATE,_ALRT_REQUIRED_SELECT);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['STATE'])) && !checkLength($form['STATE'],80))
	{
		$msg = str_replace('%field%',_LBL_STATE,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if(checkEmpty($form['COUNTRY']))
	{
		$msg = str_replace('field',_LBL_COUNTRY,_ALRT_REQUIRED_SELECT);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['COUNTRY'])) && !checkLength($form['COUNTRY'],80))
	{
		$msg = str_replace('%field%',_LBL_COUNTRY,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if(checkEmpty($form['AGERESTRICT']))
	{
		$msg = str_replace('field',_LBL_AGE_RESTRICTION,_ALRT_REQUIRED_SELECT);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['AGE_RESTRICT'])) && !checkLength($form['AGE_RESTRICT'],50))
	{
		$msg = str_replace('%field%',_LBL_AGE_RESTRICTION,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if(checkEmpty($form['ZIP']))
	{
		$msg = str_replace('field',_LBL_ZIP,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['ZIP'])) && !checkLength($form['ZIP'],15))
	{
		$msg = str_replace('%field%',_LBL_ZIP,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['ZIP'])) && !validateZip($form['ZIP']))
	{
		$msg = str_replace('%field%',_LBL_ZIP,_ALRT_CHECK_ZIP);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['PHONE'])) && !checkLength($form['PHONE'],20))
	{
		$msg = str_replace('%field%',_LBL_PHONE,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['PHONE'])) && !validatePhone($form['PHONE']))
	{
		$msg = str_replace('%field%',_LBL_PHONE,_ALRT_CHECK_PHONE);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['URL'])) && !checkLength($form['URL'],200))
	{
		$msg = str_replace('%field%',_LBL_URL,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['URL'])) && !validateURL($form['URL']))
	{
		$msg = str_replace('%field%',_LBL_URL,_ALRT_CHECK_URL);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if(checkEmpty($form['FACILITY_TYPE']))
	{
		$msg = str_replace('field',_LBL_FAC_TYPE,_ALRT_REQUIRED_SELECT);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['FACILITY_TYPE'])) && !checkLength($form['FACILITY_TYPE'],255))
	{
		$msg = str_replace('%field%',_LBL_FAC_TYPE,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if(checkEmpty($form['type_entertainment']))
	{
		$msg = str_replace('field',_LBL_ACCEPTED_FORMAT,_ALRT_REQUIRED_SELECT);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if(checkEmpty($form['FACILITYFEE']))
	{
		$msg = str_replace('field',_LBL_BASIC_RENTAL_FEE,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if((!checkEmpty($form['FACILITYFEE'])) && !checkNumeric($form['FACILITYFEE']))
	{
		$msg = str_replace('%field%',_LBL_BASIC_RENTAL_FEE,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['FACILITYFEE'])) && !checkLength($form['FACILITYFEE'],50))
	{
		$msg = str_replace('%field%',_LBL_BASIC_RENTAL_FEE,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'FACILITY_INFO';
		return $msg;
	}
	if(checkEmpty($form['STAGESIZE']))
	{
		$msg = str_replace('field',_LBL_STAGE_SIZE,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'TECHNICAL_INFO';
		return $msg;
	}
	if((!checkEmpty($form['STAGESIZE'])) && !checkLength($form['STAGESIZE'],30))
	{
		$msg = str_replace('%field%',_LBL_STAGE_SIZE,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'TECHNICAL_INFO';
		return $msg;
	}
	if((!checkEmpty($form['CEILINGHEIGHT'])) && !checkLength($form['CEILINGHEIGHT'],50))
	{
		$msg = str_replace('%field%',_LBL_CEILING_HEIGHT,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'TECHNICAL_INFO';
		return $msg;
	}
	if((!checkEmpty($form['LOADINDOORSIZE'])) && !checkLength($form['LOADINDOORSIZE'],100))
	{
		$msg = str_replace('%field%',_LBL_LOAD_IN_DOOR,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'TECHNICAL_INFO';
		return $msg;
	}
	if((!checkEmpty($form['NODRESSROOM'])) && !checkLength($form['NODRESSROOM'],50))
	{
		$msg = str_replace('%field%',_LBL_DRESSING_ROOMS,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'TECHNICAL_INFO';
		return $msg;
	}
	if(checkEmpty($form['SOUND']))
	{
		$msg = str_replace('field',_LBL_SOUND_LIGHT.' '._LBL_HANGING_CAP,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'TECHNICAL_INFO';
		return $msg;
	}
	if(!checkEmpty($file['LOGO']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg');
		$validate_img = validateImage($file['LOGO'], _LBL_UPLOAD_LOGO , $allowed_extensions);


		if($validate_img !== true)
		{
			$show_tab_type = 'UPLOAD_INFO';
			return $validate_img;
		}
	}
	if(!checkEmpty($file['LOGO']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg');
		$validate_img = validateImage($file['LOGO'], _LBL_UPLOAD_LOGO , $allowed_extensions);


		if($validate_img !== true)
		{
			$show_tab_type = 'UPLOAD_INFO';
			return $validate_img;
		}
	}
	if(checkEmpty($form['PHOTO1_temp']))
	{
		if(checkEmpty($file['PHOTO1']['name'])) 
		{
			$msg = str_replace('field',_LBL_VENUE_PHOTO,_ALRT_REQUIRED_SELECT);	
			$show_tab_type = 'UPLOAD_INFO';
			return $msg;
		}
	}
	if(!checkEmpty($file['PHOTO1']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg');
		$validate_img = validateImage($file['PHOTO1'], _LBL_VENUE_PHOTO , $allowed_extensions);


		if($validate_img !== true)
		{
			$show_tab_type = 'UPLOAD_INFO';
			return $validate_img;
		}
	}
	if(checkEmpty($form['PHOTO2_temp']))
	{
		if(checkEmpty($file['PHOTO2']['name']))
		{
			$msg = str_replace('field',_LBL_MAN_PHOTO,_ALRT_REQUIRED_SELECT);	
			$show_tab_type = 'UPLOAD_INFO';
			return $msg;
		}
	}
	if(!checkEmpty($file['PHOTO2']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg');
		$validate_img = validateImage($file['PHOTO2'], _LBL_MAN_PHOTO , $allowed_extensions);


		if($validate_img !== true)
		{
			$show_tab_type = 'UPLOAD_INFO';
			return $validate_img;
		}
	}
	if(!checkEmpty($file['RULES']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg');
		$validate_img = validateImage($file['RULES'], _LBL_RULES_REGULATION , $allowed_extensions);


		if($validate_img !== true)
		{
			$show_tab_type = 'UPLOAD_INFO';
			return $validate_img;
		}
	}
	if(!checkEmpty($file['CHART1']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg');
		$validate_img = validateImage($file['CHART1'], _LBL_SEATING_CHAT1 , $allowed_extensions);


		if($validate_img !== true)
		{
			$show_tab_type = 'UPLOAD_INFO';
			return $validate_img;
		}
	}
	if(!checkEmpty($file['CHART2']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg');
		$validate_img = validateImage($file['CHART2'], _LBL_SEATING_CHAT2 , $allowed_extensions);


		if($validate_img !== true)
		{
			$show_tab_type = 'UPLOAD_INFO';
			return $validate_img;
		}
	}
	/* Validation for download center */
	if(!checkEmpty($file['filename1']['name']) && checkEmpty($form['title1']))
	{
		$msg = str_replace('field',_LBL_TITLE1,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'DOWNLOAD_INFO';
		return $msg;
	}
	if(!checkEmpty($file['filename1']['name']) && checkEmpty($form['desc1']))
	{
		$msg = str_replace('field',_LBL_DESC1,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'DOWNLOAD_INFO';
		return $msg;
	}
	if(!checkEmpty($file['filename1']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg','pdf','doc','zip');
		$validate_img = validateDownloadFiles($file['filename1'], _LBL_FILE1 , $allowed_extensions);

		if($validate_img !== true)
		{
			$show_tab_type = 'DOWNLOAD_INFO';
			return $validate_img;
		}
	}
	if(!checkEmpty($file['filename1']['name']) && (!checkEmpty($form['title1'])) && !checkLength($form['title1'],20))
	{
		$msg = str_replace('%field%',_LBL_TITLE1,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'DOWNLOAD_INFO';
		return $msg;
	}
	if(!checkEmpty($file['filename1']['name']) && (!checkEmpty($form['desc1'])) && !checkLength($form['desc1'],100))
	{
		$msg = str_replace('%field%',_LBL_DESC1,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'DOWNLOAD_INFO';
		return $msg;
	}
	if(!checkEmpty($file['filename2']['name']) && checkEmpty($form['title2']))
	{
		$msg = str_replace('field',_LBL_TITLE2,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'DOWNLOAD_INFO';
		return $msg;
	}
	if(!checkEmpty($file['filename2']['name']) && checkEmpty($form['desc2']))
	{
		$msg = str_replace('field',_LBL_DESC2,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'DOWNLOAD_INFO';
		return $msg;
	}
	if(!checkEmpty($file['filename2']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg','pdf','doc','zip');
		$validate_img = validateDownloadFiles($file['filename2'], _LBL_FILE2 , $allowed_extensions);

		if($validate_img !== true)
		{
			$show_tab_type = 'DOWNLOAD_INFO';
			return $validate_img;
		}
	}
	if(!checkEmpty($file['filename2']['name']) && (!checkEmpty($form['title2'])) && !checkLength($form['title2'],20))
	{
		$msg = str_replace('%field%',_LBL_TITLE2,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'DOWNLOAD_INFO';
		return $msg;
	}
	if(!checkEmpty($file['filename2']['name']) && (!checkEmpty($form['desc2'])) && !checkLength($form['desc2'],100))
	{
		$msg = str_replace('%field%',_LBL_DESC2,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'DOWNLOAD_INFO';
		return $msg;
	}
	if(!checkEmpty($file['filename3']['name']) && checkEmpty($form['title3']))
	{
		$msg = str_replace('field',_LBL_TITLE3,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'DOWNLOAD_INFO';
		return $msg;
	}
	if(!checkEmpty($file['filename3']['name']) && checkEmpty($form['desc3']))
	{
		$msg = str_replace('field',_LBL_DESC3,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'DOWNLOAD_INFO';
		return $msg;
	}
	if(!checkEmpty($file['filename3']['name']))
	{
		$allowed_extensions = array('jpg','gif','png','jpeg','pdf','doc','zip');
		$validate_img = validateDownloadFiles($file['filename3'], _LBL_FILE3 , $allowed_extensions);

		if($validate_img !== true)
		{
			$show_tab_type = 'DOWNLOAD_INFO';
			return $validate_img;
		}
	}
	if(!checkEmpty($file['filename3']['name']) && (!checkEmpty($form['title3'])) && !checkLength($form['title3'],20))
	{
		$msg = str_replace('%field%',_LBL_TITLE3,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'DOWNLOAD_INFO';
		return $msg;
	}
	if(!checkEmpty($file['filename3']['name']) && (!checkEmpty($form['desc3'])) && !checkLength($form['desc3'],100))
	{
		$msg = str_replace('%field%',_LBL_DESC3,_ALRT_CHECK_LENGTH);
		$show_tab_type = 'DOWNLOAD_INFO';
		return $msg;
	}
	
	return true;
}

/****************** Validation functions for artist profile. *********************/
function validateNewArtistProfile($form)
{
	if(checkEmpty($form['bandname']))
	{
		$msg = str_replace('field',_LBL_ARTIST_OR_BAND,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['bandname'])) && !checkLength($form['bandname'],200))
	{
		$msg = str_replace('%field%',_LBL_ARTIST_OR_BAND,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['genre'])) && !checkLength($form['genre'],25))
	{
		$msg = str_replace('%field%',_LBL_GENRE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['highfee'])) && !checkNumeric($form['highfee']))
	{
		$msg = str_replace('%field%',_LBL_HIGH_FEES,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['highfee'])) && !checkNumericRange($form['highfee'],'double',12))
	{
		$msg = str_replace('%field%',_LBL_HIGH_FEES,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['lowfee'])) && !checkNumeric($form['lowfee']))
	{
		$msg = str_replace('%field%',_LBL_LOW_FEES,_ALRT_CHECK_NUMERIC);
		return $msg;
	}
	if((!checkEmpty($form['lowfee'])) && (($form['lowfee'] > 0) && ($form['lowfee'] < 1000)))
	{
		$msg = '$1000 Minimum. YOU ARE WORTH IT! xebura Talent may not list a fee less than $1000. <br>You can still receive and accept offers of any amount.';			
		return $msg;
	}
	if((!checkEmpty($form['lowfee'])) && !checkNumericRange($form['lowfee'],'double',12))
	{
		$msg = str_replace('%field%',_LBL_LOW_FEES,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if($form['lowfee'] > $form['highfee'])
	{
		$msg = _ALRT_HIGN_VER_LOW1;	
		return $msg;
	}
	if(checkEmpty($form['type_entertainment']))
	{
		$msg = str_replace('field',_LBL_TYPE_OF_ENT,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if(checkEmpty($form['yearfound']))
	{
		$msg = str_replace('field',_LBL_YR_FOUNDED,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['yearfound'])) && !checkLength($form['yearfound'],4))
	{
		$msg = str_replace('%field%',_LBL_YR_FOUNDED,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['yearfound'])) && !checkValidYear($form['yearfound']))
	{
		$msg = str_replace('field',_LBL_YR_FOUNDED,_ALRT_VALID_FIELD);
		return $msg;
	}
	if(checkEmpty($form['firstname']))
	{
		$msg = str_replace('field',_LBL_FIRST_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['firstname'])) && !checkLength($form['firstname'],255))
	{
		$msg = str_replace('%field%',_LBL_FIRST_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['lastname']))
	{
		$msg = str_replace('field',_LBL_LAST_NAME,_ALRT_REQUIRED_FIELD);	
		return $msg;
	}
	if((!checkEmpty($form['lastname'])) && !checkLength($form['lastname'],255))
	{
		$msg = str_replace('%field%',_LBL_LAST_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['phone'])) && !checkLength($form['phone'],20))
	{
		$msg = str_replace('%field%',_LBL_PH_NO,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['phone'])) && !validatePhone($form['phone']))
	{
		$msg = str_replace('%field%',_LBL_PH_NO,_ALRT_CHECK_PHONE);
		return $msg;
	}
	if((!checkEmpty($form['fax'])) && !checkLength($form['fax'],20))
	{
		$msg = str_replace('%field%',_LBL_FAX_NO,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['fax'])) && !validateFax($form['fax']))
	{
		$msg = str_replace('%field%',_LBL_FAX_NO,_ALRT_CHECK_VALID);
		return $msg;
	}
	if((!checkEmpty($form['web'])) && !checkLength($form['web'],200))
	{
		$msg = str_replace('%field%',_LBL_ARTIST_WEB,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['web'])) && !validateURL($form['web']))
	{
		$msg = str_replace('%field%',_LBL_ARTIST_WEB,_ALRT_CHECK_URL);
		return $msg;
	}
	if(checkEmpty($form['city']))
	{
		$msg = str_replace('field',_CITY,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['city'])) && !checkLength($form['city'],80))
	{
		$msg = str_replace('%field%',_CITY,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['state']))
	{
		$msg = str_replace('field',_LBL_STATE,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['state'])) && !checkLength($form['state'],80))
	{
		$msg = str_replace('%field%',_LBL_STATE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['zip']))
	{
		$msg = str_replace('field',_LBL_ZIP_POSTALCODE,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['zip'])) && !checkLength($form['zip'],15))
	{
		$msg = str_replace('%field%',_LBL_ZIP_POSTALCODE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['zip'])) && !validateZip($form['zip']))
	{
		$msg = str_replace('%field%',_LBL_ZIP_POSTALCODE,_ALRT_CHECK_ZIP);
		return $msg;
	}
	return true;
}

function validateNewBuyerProfile($form)
{
	if(checkEmpty($form['firstname']))
	{
		$msg = str_replace('field',_LBL_FIRST_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['firstname'])) && !checkLength($form['firstname'],255))
	{
		$msg = str_replace('%field%',_LBL_FIRST_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['lastname']))
	{
		$msg = str_replace('field',_LBL_LAST_NAME,_ALRT_REQUIRED_FIELD);	
		return $msg;
	}
	if((!checkEmpty($form['lastname'])) && !checkLength($form['lastname'],255))
	{
		$msg = str_replace('%field%',_LBL_LAST_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	/*if(checkEmpty($form['companyname']))
	{
		$msg = str_replace('field',_LBL_COMPANY_NAME,_ALRT_REQUIRED_FIELD);
		return $msg;
	}*/
	if((!checkEmpty($form['companyname'])) && !checkLength($form['companyname'],100))
	{
		$msg = str_replace('%field%',_LBL_COMPANY_NAME,_ALRT_CHECK_LENGTH);
		return $msg;
	}		
	if(checkEmpty($form['type_entertainment']))
	{
		$msg = str_replace('field',_LBL_TYPE_OF_ENT,_ALRT_REQUIRED_FIELD);
		return $msg;
	}	
	/*if(checkEmpty($form['contactname']))
	{
		$msg = str_replace('field',_LBL_CON_PERSON,_ALRT_REQUIRED_FIELD);
		return $msg;
	}*/
	if((!checkEmpty($form['phone'])) && !checkLength($form['phone'],20))
	{
		$msg = str_replace('%field%',_LBL_PH_NO,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['phone'])) && !validatePhone($form['phone']))
	{
		$msg = str_replace('%field%',_LBL_PH_NO,_ALRT_CHECK_PHONE);
		return $msg;
	}
	if((!checkEmpty($form['jobtitle'])) && !checkLength($form['jobtitle'],100))
	{
		$msg = str_replace('%field%',_LBL_JOB_TITLE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	/*if(checkEmpty($form['yearfound']))
	{
		$msg = str_replace('field',_LBL_YR_FOUNDED,_ALRT_REQUIRED_FIELD);
		return $msg;
	}*/
	if((!checkEmpty($form['yearfound'])) && !checkLength($form['yearfound'],4))
	{
		$msg = str_replace('%field%','Year Started',_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['yearfound'])) && !checkValidYear($form['yearfound']))
	{
		$msg = str_replace('field','Year Started',_ALRT_VALID_FIELD);
		return $msg;
	}	
	if((!checkEmpty($form['fax'])) && !checkLength($form['fax'],20))
	{
		$msg = str_replace('%field%',_LBL_FAX_NO,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['fax'])) && !validateFax($form['fax']))
	{
		$msg = str_replace('%field%',_LBL_FAX_NO,_ALRT_CHECK_VALID);
		return $msg;
	}
	if((!checkEmpty($form['web'])) && !checkLength($form['web'],200))
	{
		$msg = str_replace('%field%',_LBL_COMP_URL,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['web'])) && !validateURL($form['web']))
	{
		$msg = str_replace('%field%',_LBL_COMP_URL,_ALRT_CHECK_URL);
		$show_tab_type = 'COMPANY_INFO';
		return $msg;
	}
	if(checkEmpty($form['city']))
	{
		$msg = str_replace('field',_CITY,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['city'])) && !checkLength($form['city'],80))
	{
		$msg = str_replace('%field%',_CITY,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['state']))
	{
		$msg = str_replace('field',_LBL_STATE,_ALRT_REQUIRED_FIELD);
		$show_tab_type = 'CONTACT_INFO';
		return $msg;
	}
	if((!checkEmpty($form['state'])) && !checkLength($form['state'],80))
	{
		$msg = str_replace('%field%',_LBL_STATE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if(checkEmpty($form['zip']))
	{
		$msg = str_replace('field',_LBL_ZIP_POSTALCODE,_ALRT_REQUIRED_FIELD);
		return $msg;
	}
	if((!checkEmpty($form['zip'])) && !checkLength($form['zip'],15))
	{
		$msg = str_replace('%field%',_LBL_ZIP_POSTALCODE,_ALRT_CHECK_LENGTH);
		return $msg;
	}
	if((!checkEmpty($form['zip'])) && !validateZip($form['zip']))
	{
		$msg = str_replace('%field%',_LBL_ZIP_POSTALCODE,_ALRT_CHECK_ZIP);
		return $msg;
	}
	return true;
}

function validateDiscography($form)
{
	global $db;
	if((!checkEmpty($form['year'])) && !checkLength($form['year'],4))
	{
		$msg = str_replace('%field%',_LBL_YEAR,_ALRT_CHECK_LENGTH);	
		$show_tab_type = 'DISCG_INFO';
		return $msg;	
	}
	if((!checkEmpty($form['year'])) && !checkValidYear($form['year']))
	{
		$msg = str_replace('field',_LBL_YEAR,_ALRT_VALID_FIELD);	
		$show_tab_type = 'DISCG_INFO';
		return $msg;	
	}
	if((!checkEmpty($form['album'])) && !checkLength($form['album'],255))
	{
		$msg = str_replace('%field%',_LBL_ALBUM,_ALRT_CHECK_LENGTH);	
		$show_tab_type = 'DISCG_INFO';
		return $msg;	
	}
	if((!checkEmpty($form['label'])) && !checkLength($form['label'],255))
	{
		$msg = str_replace('%field%',_LBL_LABEL,_ALRT_CHECK_LENGTH);	
		$show_tab_type = 'DISCG_INFO';
		return $msg;	
	}
	if(!checkEmpty($form['year']))
	{
		if(checkEmpty($form['album']))
		{
			$msg = str_replace('field',_LBL_ALBUM,_ALRT_REQUIRED_FIELD);	
			$show_tab_type = 'DISCG_INFO';
			return $msg;
		}
		else if(checkEmpty($form['label']))
		{
			$msg = str_replace('field',_LBL_LABEL,_ALRT_REQUIRED_FIELD);	
			$show_tab_type = 'DISCG_INFO';
			return $msg;
		}
	}
	if(!checkEmpty($form['album']))
	{
		if(checkEmpty($form['year']))
		{
			$msg = str_replace('field',_LBL_YEAR,_ALRT_REQUIRED_FIELD);	
			$show_tab_type = 'DISCG_INFO';
			return $msg;
		}
		else if(checkEmpty($form['label']))
		{
			$msg = str_replace('field',_LBL_LABEL,_ALRT_REQUIRED_FIELD);	
			$show_tab_type = 'DISCG_INFO';
			return $msg;
		}
	}
	if(!checkEmpty($form['label']))
	{
		if(checkEmpty($form['year']))
		{
			$msg = str_replace('field',_LBL_YEAR,_ALRT_REQUIRED_FIELD);	
			$show_tab_type = 'DISCG_INFO';
			return $msg;
		}
		else if(checkEmpty($form['album']))
		{
			$msg = str_replace('field',_LBL_ALBUM,_ALRT_REQUIRED_FIELD);	
			$show_tab_type = 'DISCG_INFO';
			return $msg;
		}		
	}
	
	if(isset($form['album_id']))
	{
		$discg_select = "SELECT * FROM xebura_ARTIST_DISCOG 
			WHERE AF_ARTIST_DISCOG_NAME = '".stripslashes($form['album'])."'
			AND AF_ARTIST_DISCOG_CATEGORY = '".stripslashes($form['category'])."' 
			AND AF_ARTIST_DISCOG_ID != '".$form['album_id']."'
			AND AF_ARTIST_ID = '".$_SESSION['User_Account_Id']."'";
	}
	else
	{
		$discg_select = "SELECT * FROM xebura_ARTIST_DISCOG 
			WHERE AF_ARTIST_DISCOG_NAME = '".stripslashes($form['album'])."'
			AND AF_ARTIST_DISCOG_CATEGORY = '".stripslashes($form['category'])."'
			AND AF_ARTIST_ID = '".$_SESSION['User_Account_Id']."'";
	}
	if($db->query_affected_rows($discg_select) > 0)
	{
		$msg = _CHECK_DUPLICATE_DISCOGRAPHY;
		$show_tab_type = 'DISCG_INFO';
		return $msg;
	}
	return true;
}

function validateTourdates($form)
{
	global $db;
	
	if(checkEmpty($form['venue_name']))
	{
		$msg = str_replace('field',_VENUE,_ALRT_REQUIRED_FIELD);	
		$show_tab_type = 'TOURDATES_INFO';
		return $msg;	
	}
	if(checkEmpty($form['tour_city']))
	{
		$msg = str_replace('field',_CITY,_ALRT_REQUIRED_FIELD);	
		$show_tab_type = 'TOURDATES_INFO';
		return $msg;	
	}
	if(checkEmpty($form['tour_state']))
	{
		$msg = str_replace('field',_LBL_STATE,_ALRT_REQUIRED_FIELD);	
		$show_tab_type = 'TOURDATES_INFO';
		return $msg;	
	}
	if(checkEmpty($form['tour_country']))
	{
		$msg = str_replace('field',_LBL_COUNTRY,_ALRT_REQUIRED_FIELD);	
		$show_tab_type = 'TOURDATES_INFO';
		return $msg;	
	}
	if(checkEmpty($form['tourdate']))
	{
		$msg = str_replace('field',_LBL_START_DATE,_ALRT_REQUIRED_FIELD);	
		$show_tab_type = 'TOURDATES_INFO';
		return $msg;	
	}
	if(checkEmpty($form['tourdate']))
	{
		$msg = _ALRT_SEL_STARTDATE;
		$show_tab_type = 'TOURDATES_INFO';
		return $msg;
	}
	else
	{
		$showdate = validateDate($form['tourdate']);
		if($showdate !== true)
		{
			$msg = $showdate.' '._LBL_FOR.' '._LBL_START_DATE;
			$show_tab_type = 'TOURDATES_INFO';
			return $msg;
		}
		if(compareDate($form['tourdate'], date('m/d/Y'), 1))
		{
			$msg = _ALRT_VALID_TOURDATE;
			$show_tab_type = 'TOURDATES_INFO';
			return $msg;
		}
	}
	
	$date = getYearMonthDateSearch($form['tourdate'],"/","-");
	if(isset($form['tourdate_id']))
	{
		$sql = 'SELECT AF_TOURDATE_ID FROM xebura_TOURDATE 
			WHERE AF_TOURDATE_ARTIST_ID = \''.$_SESSION['User_Account_Id'].'\'
			AND (AF_TOURDATE_VENUE_ID = \''.$form['venue'].'\' 
			OR (AF_TOURDATE_VENUE_NAME = \''.$form['venue_name'].'\'
			AND AF_TOURDATE_VENUE_CITY = \''.$form['tour_city'].'\'
			AND AF_TOURDATE_VENUE_STATE = \''.$form['tour_state'].'\'
			AND AF_TOURDATE_VENUE_COUNTRY = \''.$form['tour_country'].'\'))
			AND AF_TOURDATE_STARTDATE = \''.$date.'\'
			AND AF_ARTIST_DISCOG_ID != \''.$form['tourdate_id'].'\'';
	}
	else
	{
		$sql = 'SELECT AF_TOURDATE_ID FROM xebura_TOURDATE 
			WHERE AF_TOURDATE_ARTIST_ID = \''.$_SESSION['User_Account_Id'].'\'
			AND (AF_TOURDATE_VENUE_ID = \''.$form['venue'].'\' 
			OR (AF_TOURDATE_VENUE_NAME = \''.$form['venue_name'].'\'
			AND AF_TOURDATE_VENUE_CITY = \''.$form['tour_city'].'\'
			AND AF_TOURDATE_VENUE_STATE = \''.$form['tour_state'].'\'
			AND AF_TOURDATE_VENUE_COUNTRY = \''.$form['tour_country'].'\'))
			AND AF_TOURDATE_STARTDATE = \''.$date.'\'';
	}
	
	if($db->query_affected_rows($sql) > 0)
	{
		$msg = _CHECK_DUPLICATE_TOURDATE;
		$show_tab_type = 'TOURDATES_INFO';
		return $msg;
	}
	return true;
}

?>
