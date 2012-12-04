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
class onlinedb extends mysqldb 
{
	/* This function checks the online status of the user. */
	function get_online_info($mid) 
	{
		$User_Online = 0;
		
		$sel_member = "SELECT MID,USERNAME FROM xebura_ONLINEUSER WHERE MID ='".$mid."'";	
		$rs_member		= $this->query($sel_member);
		$total_member	= $this->getNumRows($rs_member);
	
		if($total_member > 0) 
		{
			$User_Online=1;
		}
		return $User_Online;		
	}
	
	/* This function set the user status to offline. 
	Deletes the record from the table. */
	function set_offline_user() 
	{
		$delete_query = "DELETE FROM xebura_ONLINEUSER WHERE MID = '".$_SESSION['Member_Id']."'";
		$this->query($delete_query);
	}
	
	/* This function set the user status to online.
	Inserts the record to the table. */
	function set_online_user($min_online)
	{
		if (getenv('HTTP_CLIENT_IP')) 
		{
			$ip = getenv('HTTP_CLIENT_IP');
		}
		elseif (getenv('HTTP_X_FORWARDED_FOR')) 
		{
			$ip = getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_X_FORWARDED')) 
		{
			$ip = getenv('HTTP_X_FORWARDED');
		}
		elseif (getenv('HTTP_FORWARDED_FOR')) 
		{
			$ip = getenv('HTTP_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_FORWARDED')) 
		{
			$ip = getenv('HTTP_FORWARDED');
		}
		else 
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		$day =date("d");
		$month =date("m");
		$year =date("Y");
		$date="$year-$month-$day";
		$ora = date("H");
		$minuti = date("i");
		$secondi = date("s");
		$time="$ora:$minuti:$secondi";
		$timestamp = $date.' '.$time;
		
		$sel_query = "SELECT MID,USERNAME FROM xebura_ONLINEUSER";
		$result		= $this->query($sel_query);
		$totalRows	= $this->getNumRows($result);
		
		$value = '';
		
		if($_SESSION['Username']!="")
		{
			$value = array('MID'=> $_SESSION['Member_Id'] , 'USERNAME'=> $_SESSION['Username'], 'IP'=> $ip, 'TIMESTAMP'=> $timestamp);
			$value1 = array('USERNAME'=> $_SESSION['Username'], 'IP'=>$ip, 'TIMESTAMP'=> $timestamp);
		}
		
		if($totalRows == 0)
		{
			if(is_array($value))
			{
				$this->insert("xebura_ONLINEUSER ",$value); 
			}
		}
		else
		{
			$delete_query = "DELETE FROM xebura_ONLINEUSER WHERE MID != '".$_SESSION['Member_Id']."' AND (UNIX_TIMESTAMP('$timestamp') - UNIX_TIMESTAMP(TIMESTAMP)) >= ".$min_online;
			$this->query($delete_query);
				
			$sel_member = "SELECT MID,USERNAME FROM xebura_ONLINEUSER WHERE MID ='".$_SESSION['Member_Id']."'";
			
			$rs_member		= $this->query($sel_member);
			$total_member	= $this->getNumRows($rs_member);
		
			if($total_member == 0) 
			{
				if(is_array($value))
				{
					$this->insert("xebura_ONLINEUSER ",$value); 
				}
			}
			else 
			{
				$conds=" where MID = '".$_SESSION['Member_Id']."'";
				$this->update('xebura_ONLINEUSER',$value1,$conds);
			}	
		}
	}
}
?>