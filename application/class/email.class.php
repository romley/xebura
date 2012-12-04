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
class MyEmail
{
	var	$semi_rand;
	var $mime_boundary;
	
	function MyEmail()
	{
		$this->semi_rand = md5(time());
		$this->mime_boundary = "==Multipart_Boundary_x{".$this->semi_rand."}x";		
	}
	
	
	function sendTextMail($from,$to,$subject,$msg,$from_name='')
	{
		if(trim($from_name) == '')
		{
			$from_name = $from;
		}
		$headers .= 'From: '.$from_name.' <'.$from.'>' . "\r\n";	  
		$headers .= 'MIME-Version: 1.0' . "\n";
		$headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\n";		
    $message = $msg;
		
		if(mail($to,$subject,$message,$headers))
		{
			return 'Seccess';
		}
	
	}
	function sendHtmlMail($from,$to,$subject,$msg,$from_name='')
	{
		$to = 'supritip@mindfiresolutions.com';
		if(trim($from_name) == '')
		{
			$from_name = $from;
		}
		$headers .= 'From: '.$from_name.' <'.$from.'>' . "\r\n";
    $headers .= 'MIME-Version: 1.0' . "\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";		
    $message = $msg;
	
		mail($to,$subject,$message,$headers);		
	}
	function sendAttachMail($from,$to,$subject,$msg,$attach,$from_name)
	{
		$fileatt      = $_FILES[$attach]['tmp_name'];
		$fileatt_type = $_FILES[$attach]['type'];
		$fileatt_name = $_FILES[$attach]['name'];
		
		if (is_uploaded_file($fileatt))
		{
		  // Read the file to be attached ('rb' = read binary)
  			$file = fopen($fileatt,'rb');
			$data = fread($file,filesize($fileatt));
			fclose($file);
  
			$data = chunk_split(base64_encode($data));
			
			if(trim($from_name) == '')
			{
				$from_name = $from;
			}
			$headers .= 'From: '.$from_name.'  <'.$from.'>' . "\r\n";	  
	
			$headers .= "\nMIME-Version: 1.0\n" .
				  "Content-Type: multipart/mixed; charset=\"iso-8859-1\"\n" .
				  " boundary=\"{".$this->mime_boundary."}\"";
				  
			/*$message = "This is a multi-part message in MIME format.\n\n" .
				 "--{".$this->mime_boundary."}\n" .
				 "Content-Type: text/html; charset=\"iso-8859-1\"\n" .
				 "Content-Transfer-Encoding: 7bit\n\n" .
				 $msg . "{".$this->mime_boundary."}\n\n";*/	
			$message = $msg;
	
			$message .= "--{".$this->mime_boundary."}\n" .
				  "Content-Type: {".$fileatt_type."};\n" .
				  " name=\"{".$fileatt_name."}\"\n" .
				  "Content-Disposition: attachment;\n" .
				  " filename=\"{".$fileatt_name."}\"\n" .
				  "Content-Transfer-Encoding: base64\n\n" .
				  $data . "\n\n" .
				  "--{".$this->mime_boundary."}--\n";
	
			mail($to,$subject,$message,$headers);	
	
		}
	}
}