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
 class mysqldb{
   var $error;
   var $last_error;
   var $conn;
   var $host;
   var $user;
   var $pass;
   var $dbname;
   var $db;
   var $last_qres;
   var $last_q2a_res;
  /*********************************** DB Connection Functions *****************************************/
	function mysqldb($dbname=DB_DATABASE,$dbhost=DB_SERVER,$dbuser=DB_SERVER_USERNAME,$dbpass=DB_SERVER_PASSWORD){ # most common config ?
		$this->host   = $dbhost;
		$this->user   = $dbuser;
		$this->pass   = $dbpass;
		$this->dbname = $dbname;
		$this->autoconnect= TRUE;
		$this->open();
		$this->beverbose  = TRUE;
	}
	
	function open(){  
		return $this->check_conn('active');
	}
	
	function close(){
		return $this->check_conn('kill');
	}
	
	function select_db($dbname=null){
		if(! ($dbname ||$this->dbname) )
			return FALSE;
		if($dbname)
			$this->dbname = $dbname;
		if(! $this->db = @mysql_select_db($this->dbname,$this->conn)){
			$this->verbose("FATAL ERROR CAN'T CONNECT TO database ".$this->dbname);
			$this->set_error();
			return FALSE;
		}
		else{
			return $this->db;
		}
	}
	
	function check_conn($action = ''){
		if(! $host = @mysql_get_host_info($this->conn)){
			switch ($action){
				case 'kill':
					return $host;
					break;
				case 'check':
					return $host;
					break;
				default:
				case 'active':
					if(! $this->conn = @mysql_connect($this->host,$this->user,$this->pass)){
						$this->verbose("CONNECTION TO $this->host FAILED");
						return FALSE;
					}
					$this->verbose("CONNECTION TO $this->host ESTABLISHED");
					$this->select_db();
					return @mysql_get_host_info($this->conn);
					break;
			}
		}else{
			switch($action){
				case 'kill':
					@mysql_close($this->conn);
					$this->conn = $this->db = null;
					return true;
					break;
				case 'check':
					return $host;
					break;
				default:
				case 'active':
					return $host;
					break;
			}
		}
	}
	
	/*********************************** DB Connection Functions *****************************************/
	function select_to_array($tables,$fields = '*', $conds = null,$result_type = MYSQL_ASSOC){
		if(! $tb_str = $this->array_to_str($tables))
			return FALSE;
		if(! $fld_str =  $this->array_to_str($fields))
			$fld_str = '*';
		if($conds)
			$conds_str = $this->process_conds($conds);
		$Q_str = "SELECT $fld_str FROM $tb_str $conds_str";
		return $this->query_to_array($Q_str,$result_type);
	}
	
	function select_single_to_array($tables,$fields = '*', $conds = null,$result_type = MYSQL_ASSOC){
		if(! $res = $this->select_to_array($tables,$fields,$conds,$result_type))
			return FALSE;
		return $res[0];
	}
	
	function select2associative_array($tables,$fields='*',$conds=null,$index_field='id',$value_fields=null,$keep_index=FALSE){
		if(! $this->select_to_array($tables,$fields,$conds))
			return FALSE;
		return $this->associative_array_from_q2a_res($index_field,$value_fields,null,$keep_index);
	}
	
	function select_single_value($table,$field,$conds=null){
		if($res = $this->select_single_to_array($table,$field,$conds,MYSQL_NUM))
			return $res[0];
		else
			return FALSE;
	}
	
	function query_to_array($Q_str,$result_type=MYSQL_ASSOC){
		unset($this->last_q2a_res);
		if(! $this->query($Q_str)){
			//echo "QSTR $Q_str\n";
			return FALSE;
		}
		while($res[]=@mysql_fetch_array($this->last_qres,$result_type));
		unset($res[count($res)-1]);
		$this->num_rows = @mysql_affected_rows($this->conn);
		return $this->last_q2a_res = count($res)?$res:FALSE;
	}
	
	function insert($table,$values,$return_id=TRUE){
		if(!is_array($values))
			return FALSE;
		foreach( $values as $k=>$v){
			$fld[]= $k;
			$val[]= "'".$this->escape_chars($v)."'";
		}
		$Q_str = "INSERT INTO $table (".$this->array_to_str($fld).") VALUES (".$this->array_to_str($val).")";
		//echo $Q_str;
		//exit;
		/*if($table == 'xebura_MESSAGE')
		{
			$fp = fopen('class/test.txt','w');
			fwrite($fp, $Q_str);
			fclose($fp);
		}*/
		if(! $this->query_affected_rows($Q_str)){
			//echo $Q_str;
			return FALSE;
		}
		$this->last_id = @mysql_insert_id($this->conn);
		
		if($table == 'xebura_MESSAGE')
		{
			$send_email_alert = $this->select_single_value("xebura_MEMBERS",'SEND_EMAIL_ALERT'," WHERE MID = '".$values['AF_MESSAGE_RECEIVER']."'");
			
			if($send_email_alert == 1)
			{
				$this->send_notification($this->last_id);			
			}
		}
		return $return_id?$this->last_id:TRUE;
	}

	function delete($table,$conds) {
		if($conds)
			$conds_str = $this->process_conds($conds);
		$Q_str = "DELETE FROM $table $conds_str";
		return $this->query($Q_str);
	}
	
	function update($table,$values,$conds = null) {
		if(!is_array($values))
			return FALSE;
		foreach( $values as $k=>$v){
			$str[]= " $k = '".$this->escape_chars($v)."'";
		}
		 
		if($conds)
			$conds_str = $this->process_conds($conds);
		
		$Q_str = "UPDATE $table SET ".$this->array_to_str($str)." $conds_str";
		return $this->query($Q_str);
	}
	
	function query($Q_str){
		if(! $this->db ){
			if(! ($this->autoconnect && $this->check_conn('check')))
				return FALSE;
		}
		if(! $this->last_qres = mysql_query($Q_str,$this->conn))
			$this->set_error();
		return $this->last_qres;
	}
	
	function insert_query($Q_str){
		if(! $this->db ){
			if(! ($this->autoconnect && $this->check_conn('check')))
				return FALSE;
		}
		if(! $this->last_qres = mysql_query($Q_str,$this->conn))
			$this->set_error();
		else
			return @mysql_insert_id();
	}
	
	function query_affected_rows($Q_str){
		if(! $this->db ){
			if(! ($this->autoconnect && $this->check_conn('check')))
				return FALSE;
		}
		$this->last_qres = @mysql_query($Q_str,$this->conn);
		$num = @mysql_affected_rows($this->conn);
		if( $num == -1)
			$this->set_error();
		else
			return $num;
	}
	
	function get_fields($table){
		if(! $res = $this->query_to_array("SELECT * FROM $table LIMIT 0,1"))
			return FALSE;
		foreach($res[0] as $k=>$v){
			$r[]=$k;
		}
		return $r;
	}
	
	function get_count($table){
		return $this->select_single_value($table,'count(*) as c');
	}
	
	function list_dbs(){
		if(! $dbs = $this->query_to_array("SHOW databases",MYSQL_NUM))
			return FALSE;
		foreach($dbs as $db){
			$dbs_[]=$db[0];
		}
		return $dbs_;
	}
	
	function list_tables(){
		if(! $tables = $this->query_to_array('SHOW tables',MYSQL_NUM) )
			return FALSE;
		foreach($tables as $v){
			$ret[] = $v[0];
		}
		return $ret;
	}
	
	function list_fields($table,$indexed_by_name=FALSE){
		if(! $this->query_to_array("Show fields from $table"))
			return FALSE;
		return $this->associative_array_from_q2a_res('Field',null,null,TRUE);
	}
	
	function show_table_keys($table){
		return $this->query_to_array("SHOW KEYS FROM $table");
	}
	
	function dump_to_file($out_file,$droptables=TRUE,$gziped=TRUE){
		if($gziped){
			if(! $fout = gzopen($out_file,'w'))
				return FALSE;
		}
		else{
			if(! $fout = fopen($out_file,'w'))
				return FALSE;
		}
		$entete = "# PHP class mysqldb SQL Dump\n#\n# Host: $this->host\n# generate on: ".date("Y-m-d")."\n#\n# Db name: `$this->dbname`\n#\n#\n# --------------------------------------------------------\n\n";
		if($gziped)
			gzwrite($fout,$entete);
		else
			fwrite($fout,$entete);
		$tables = $this->list_tables();
		foreach($tables as $table) {
			$table_create = $this->query_to_array("SHOW CREATE TABLE $table",MYSQL_NUM);
			$table_create = $table_create[0]; # now we have the create statement
			$create_str = "\n\n#\n# Table Structure `$table`\n#\n\n".($droptables?"DROP TABLE IF EXISTS $table;\n":'').$table_create[1].";\n";
			if($gziped)
				gzwrite($fout,$create_str);
			else
				fwrite($fout,$create_str);
			$i=0;#initialiser au debut d'une table compteur de ligne
			if($tabledatas = $this->select_to_array($table)) { # si on a des donn√àes ds la table on les mets
				if($gziped)
					gzwrite($fout,"\n# `$table` DATAS\n\n");
				else
					fwrite($fout,"\n# `$table` DATAS\n\n");
				unset($stringsfields);$z=0;
		
				foreach($tabledatas as $row) {
					unset($values,$fields);
					foreach($row as $field=>$value){
						if($i==0){ # on the first line we get fields 
							$fields[] = "`$field`";
							if( @mysql_field_type($this->last_qres,$z++) == 'string') # will permit to correctly protect number in string fields
								$stringsfields[$field]  = TRUE;
						}
						if(preg_match("!^-?\d+(\.\d+)?$!",$value) && !$stringsfields[$field])
							$value = $value;
						elseif($value==null)
							$value =  $stringsfields[$field]?"''":"NULL";
						else
							$value = "'".$this->escape_chars($value)."'";
						$values[] = $value;
					}
					$insert_str = ($i==0?"INSERT INTO `$table` (".implode(',',$fields).")\n       VALUES ":",\n")."(".implode(',',$values).')';
					if($gziped)
					gzwrite($fout,$insert_str);
					else
					fwrite($fout,$insert_str);
					$i++; # increment line number
				}
				if($gziped)
					gzwrite($fout,";\n\n");
				else
					fwrite($fout,";\n\n");
			}
		}
		if($gziped)
		gzclose($fout);
		else
		fclose($fout);
	}

	function associative_array_from_q2a_res($index_field='id',$value_fields=null,$res = null,$keep_index=FALSE){
		if($res===null)
			$res = $this->last_q2a_res;
		if(! is_array($res)){
			$this->verbose("[error] mysqldb::associative_array_from_q2a_res with invalid result\n");
			return FALSE;
		}
		if(!isset($res[0][$index_field])){
			$this->verbose("[error] mysqldb::associative_array_from_q2a_res with invalid index field '$index_field'\n");
			return FALSE;
		}
		if(is_string($value_fields)){
			foreach($res as $row){
				$associatives_res[$row[$index_field]] = $row[$value_fields];
			}
		}
		elseif(is_array($value_fields)||$value_fields===null){
			foreach($res as $row){
				$associatives_res[$row[$index_field]] = $row;
				if(!$keep_index)
				 unset($associatives_res[$row[$index_field]][$index_field]);
			}
		}
		if(! count($associatives_res))
			return FALSE;
		ksort($associatives_res);
		return $this->last_q2a_res = $associatives_res;
	}
	
	/*********************************** Functions To Process The Arguments. *****************************************/
	
	function process_conds($conds=null){
		if(is_array($conds)){
			$WHERE = ($conds[WHERE]?'WHERE '.$this->array_to_str($conds[WHERE]):'');
			$WHERE.= ($WHERE?' ':'').$this->array_to_str($conds);
			$GROUP = ($conds[GROUP]?'GROUP BY '.$this->array_to_str($conds[GROUP]):'');
			$ORDER = ($conds[ORDER]?'ORDER BY '.$this->array_to_str($conds[GROUP]):'');
			$LIMIT = ($conds[LIMIT]?'LIMIT '.$conds[LIMIT]:'');
			$conds_str = "$WHERE $ORDER $GROUP $LIMIT";
		}
		elseif(is_string($conds)){
			$conds_str = $conds;
		}
		return $conds_str;
	}
	
	function set_error(){
		static $i=0;
		if(! $this->db ){
			$this->error[$i]['nb'] =$this->error['nb'] = null;
			$this->error[$i]['str'] =$this->error['str'] = '[ERROR] No Db Handler';
		}else{
			$this->error[$i]['nb'] = $this->error['nb'] = mysql_errno($this->conn);
			$this->error[$i]['str']= $this->error['str'] = mysql_error($this->conn);
		}
		$this->last_error = $this->error[$i];
		$this->verbose($this->error[$i]['str'], $this->error[$i]['nb']);
		$i++;
	}
	
	function array_to_str($var,$sep=','){
		if(is_string($var)){
			return $var;
		}elseif(is_array($var)){
			return implode($sep,$var);
		}else{
			return FALSE;
		}
	}
	
	function verbose($string, $err_no=0){
		if(@$this->beverbose) {
			/* Code to log the db errors to a file instead of displaying them in the browser. */		
			$fp = @fopen(AF_ERROR_LOG,'a');
			if($fp)
			{
				$str = "\n".'TIMESTAMP :  '.date('m/d/Y h:i:s');
				$str .= "\n".'IP :  '.$_SERVER['REMOTE_ADDR'];
				$str .= "\n".'PAGE :  '.'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
				$str .= "\n".'ERROR NO  :  '.$err_no;
				$str .= "\n".'Error DESCRIPTION  :  '.$string."\n";
				
				fwrite($fp, $str);
				fclose($fp);
			}			
		}
	}

	/*********************************** DB Connection Functions *****************************************/
	function queryList($sql, $link, $page, $rowsPerPage='10', $pageLimit='10') {
		$result		= mysql_query($sql);
		$totalRows	= @mysql_num_rows($result);
		
		$totalPages = ceil($totalRows / $rowsPerPage);
		$page = $page*1; $rowsPerPage = $rowsPerPage*1; $pageLimit = $pageLimit*1; 
		if(!is_int($rowsPerPage) || $rowsPerPage < 1) { 
			$rowsPerPage = 10; 
		}
		if(!is_int($pageLimit)   || $pageLimit   < 1) { 
			$pageLimit   = 10; 
		}
		if($page > $totalPages) { 
			$page = $totalPages; 
		}
		if(!is_int($page) || $page < 1) { 
			$page = 1; 
		}
		if($totalPages > $pageLimit ) { 
			$value = $pageLimit; 
		} 
		else { 
			$value = $totalPages; 
		}
		if($page > $pageLimit) { 
			$i = $page - $pageLimit; 
			$value = $pageLimit+$i; 
		}
		$pages = "";
		
		if($page >= 1){
			if($page!=1)
			{
				$pages .= '&nbsp;[ <a href="#" onclick="javascript: document.getElementById(\'global_form\').method=\'post\';document.getElementById(\'global_form\').action=\''.$link.'&page='.(1).'\';document.getElementById(\'global_form\').submit();" class="info">First</a> ] &nbsp;'; 
			}
			if($page>1)
			{
				$pages .= '&nbsp;[ <a href="#" onclick="javascript: document.getElementById(\'global_form\').method=\'post\';document.getElementById(\'global_form\').action=\''.$link.'&page='.($page-1).'\';document.getElementById(\'global_form\').submit();" class="info">Previous</a> ] &nbsp;';
			}
		}
		while ($i < $value){
			$i++;
			if ($i == $page){
				$pages .= "<b>[ $i ]</b> ";
			} else { 
				if($i <= $totalPages) { 
					$pages .= '<a href="#" onclick="javascript: document.getElementById(\'global_form\').method=\'post\';document.getElementById(\'global_form\').action=\''.$link.'&page='.$i.'\';document.getElementById(\'global_form\').submit();" class="info">'.$i.'</a> '; 
				}
			}
		}
		if($i <= $totalPages){
			if($totalPages != $page){
				$pages .= '&nbsp;[ <a href="#" onclick="javascript: document.getElementById(\'global_form\').action=\''.$link.'&page='.($page+1).'\';document.getElementById(\'global_form\').submit();" class="info">Next</a> ]';
				$pages .= '&nbsp;[ <a href="#" onclick="javascript: document.getElementById(\'global_form\').method=\'post\';document.getElementById(\'global_form\').action=\''.$link.'&page='.($totalPages).'\';document.getElementById(\'global_form\').submit();" class="info">Last</a> ]';
			}
		}
		$this->result = $pages;
		$this->start  = (($page-1) * $rowsPerPage) + 1;
		$this->total  = $totalRows;
		$this->pages  = $totalPages;
		$this->sql	  = $sql." LIMIT ".($page-1)*$rowsPerPage.",".$rowsPerPage;
		if($page==$totalPages) { 
			$this->stop = ($page-1)*$rowsPerPage+($totalRows-(($page-1)*$rowsPerPage)); 
		}
		else { 
			$this->stop = $page * $rowsPerPage; 
		}
		
	} // end of query()dfgdg
	
	function queryListing($sql, $link, $page, $rowsPerPage='10', $pageLimit='10') {
		$result		= mysql_query($sql);
		$totalRows	= @mysql_num_rows($result);
		$totalPages = ceil($totalRows / $rowsPerPage);
		$page = $page*1; $rowsPerPage = $rowsPerPage*1; $pageLimit = $pageLimit*1; 
		if(!is_int($rowsPerPage) || $rowsPerPage < 1) { 
			$rowsPerPage = 10; 
		}
		if(!is_int($pageLimit)   || $pageLimit   < 1) { 
			$pageLimit   = 10; 
		}
		if($page > $totalPages) {
			$page = $totalPages; 
		}
		if(!is_int($page) || $page < 1) { 
			$page = 1; 
		}
		if($totalPages > $pageLimit ) { 
			$value = $pageLimit; 
		} 
		else { 
			$value = $totalPages; 
		}
		if($page > $pageLimit) { 
			$i = $page - $pageLimit; 
			$value = $pageLimit+$i; 
		}
		$pages = "";
		while ($i < $value){
			$i++;
			if ($i == $page){
				$pages .= "<b>[ $i ]</b> ";
			} 
			else { 
				if($i <= $totalPages) { $pages .= '<a href="#" onclick="javascript:submition('.($i).')">'.$i.'</a> '; }
			}
		}
		$this->result = $pages;
		$this->start  = (($page-1) * $rowsPerPage) + 1;
		$this->total  = $totalRows;
		$this->pages  = $totalPages;
		$this->sql	  = $sql." LIMIT ".($page-1)*$rowsPerPage.",".$rowsPerPage;
		if($page==$totalPages) { 
			$this->stop = ($page-1)*$rowsPerPage+($totalRows-(($page-1)*$rowsPerPage)); 
		}
		else { 
			$this->stop = $page * $rowsPerPage; 
		}
	} // end of query()
	
	function countRow($table,$cond)	{
		return @mysql_num_rows(mysql_query("select * from ".$table." where ".$cond)); 
	}

	
	function getAFCountry(&$country_name,&$country_code) {
		$country_name = array();
		$country_code = array();
		
		$sql="select * from xebura_country";
				
		if(! $country_list = $this->query_to_array($sql))
			return FALSE;
			
		foreach($country_list as $cvalue){ 
		  $country_name[] = ucwords($cvalue["af_country_name"]);
			$country_code[] = $cvalue["af_country_code"];
		}
	}
	
	function getRecordCount($table,$whereClause='') {
		if(! $this->db ){
			if(! ($this->autoconnect && $this->check_conn('check')))
				return FALSE;
		}
		
		if($whereClause != '') {
			$whereClause = ' WHERE '.$whereClause;
		}
		
		$result = mysql_query("select count(*) as cnt from $table $whereClause",$this->conn);
		
		if($result) {
			$count = mysql_fetch_array($result); 
			$num = $count['cnt'];
		}
		else {
			$num = 0;
		}
		
		return $num;
	}
	
	function execQuery($table, $fields='*', $whereClause='', $orderClause='', $limitClause=''){
		if(! $this->db ){
			if(! ($this->autoconnect && $this->check_conn('check')))
				return FALSE;
		}
		
		if(is_array($fields)) {
			$strFields = implode(',',$fields);
		} else {
			$strFields = $fields;
		}
		
		if($whereClause != '') {
			$whereClause = ' WHERE '.$whereClause;
		}
		
		if(! $this->last_qres = mysql_query("select $strFields from $table $whereClause",$this->conn))
			$this->set_error();
		
		$ftrow = array();
		$ftrow = @mysql_fetch_array($this->last_qres);
		return $ftrow;
	}
	
	
	
	function getNumRows($result){
		if(! $this->db ){
			if(! ($this->autoconnect && $this->check_conn('check')))
				return FALSE;
		}
		
		if($result) {
			$ftrow = @mysql_num_rows($result);
			return $ftrow;
		}
		else {
			return 0;
		}
	}
	
	function fetchQueryArray($result){
		if(! $this->db ){
			if(! ($this->autoconnect && $this->check_conn('check')))
				return FALSE;
		}
		
		if($result) {
			$ftarr = @mysql_fetch_array($result);
			return $ftarr;
		}
		else {
			return FALSE;
		}
	}
	
	function fetchQueryRow($result){
		if(! $this->db ){
			if(! ($this->autoconnect && $this->check_conn('check')))
				return FALSE;
		}
		
		if($result) {
			$ftarr = @mysql_fetch_row($result);
			return $ftarr;
		}
		else {
			return FALSE;
		}
	}
	
	function fetchQueryAssoc($result){
		if(! $this->db ){
			if(! ($this->autoconnect && $this->check_conn('check')))
				return FALSE;
		}
		
		if($result) {
			$ftarr = @mysql_fetch_assoc($result);
			return $ftarr;
		}
		else {
			return FALSE;
		}
	}
	
	function fetchQueryFields($result){
		if(! $this->db ){
			if(! ($this->autoconnect && $this->check_conn('check')))
				return FALSE;
		}
		
		if($result) {
			$ftarr = @mysql_num_fields($result);
			return $ftarr;
		}
		else {
			return FALSE;
		}
	}	
	
	function getQueryField($result,$cnt){
		if(! $this->db ){
			if(! ($this->autoconnect && $this->check_conn('check')))
				return FALSE;
		}
		
		if($result) {
			$ftarr = @mysql_field_name($result,$cnt);
			return $ftarr;
		}
		else {
			return FALSE;
		}
	}	
	
	function escape_chars($str) {
  if((ini_get('magic_quotes_gpc') == 1 ) || (ini_get('magic_quotes_gpc') == 'On' )) {
   return $str;
  }
  else {
   $str = addslashes($str);
   return $str;
  }
 }

	function get_zip_codes_in_radious($ZIPCode, $radious,$radiusTo = ''){
		//var $resultZipCodes;

		//$result = mysql_fetch_array("SELECT Latitude,Longitude FROM xebura_ZIPCODES WHERE ZIPCode = '$ZIPCode'");

			$rs = $this->query("SELECT Latitude,Longitude FROM xebura_ZIPCODES WHERE ZIPCode = '$ZIPCode'");
//echo '<br>ZIPCode ---> '.$ZIPCode;

			if($this->getNumRows($rs) > 0)
			{
							$values = $this->fetchQueryArray($rs);
							$LAT = $values['Latitude'];
							$LON = $values['Longitude'];
			//echo '<br>Latitude ---> '.$LAT;
			//echo '<br>Longitude---> '.$LON;


					
					//$myrow = mysql_fetch_array($result);
					//$Latitude = $myrow["Latitude"];
					//$Longitude = $myrow["Longitude"];

			//echo '<br>L1 ---> '.$myrow[0];

					if(!empty($radiusTo))
						$zcdRadius = new RadiusAssistant($LAT,$LON,$radiusTo);
					else
						$zcdRadius = new RadiusAssistant($LAT,$LON,$radious);
					$minLat = $zcdRadius->MinLatitude();
					$maxLat = $zcdRadius->MaxLatitude();
					$minLong = $zcdRadius->MinLongitude();
					$maxLong = $zcdRadius->MaxLongitude();

					//mySQL Query
					$sql = "SELECT * FROM xebura_ZIPCODES WHERE
						Latitude >= $minLat
						AND Latitude <= $maxLat
						AND Longitude >= $minLong
						AND Longitude <= $maxLong";

			//echo '<br>Final query---> '.$sql;

						$rs1 = $this->query($sql);
						$totalZipsFound = $this->getNumRows($rs1);
						$cnt=0;
			//echo '<br>Number of rows---> '.$this->getNumRows($rs1);
						//if($this->getNumRows($rs1) > 0)
						//{
						//	$values = $this->fetchQueryArray($rs);
						//}
						$distance_radious_arr = array();
						$zcdDistance = new DistanceAssistant;
						$flag = false;
						while ($myrow = mysql_fetch_array($rs1)){
							$Distance = $zcdDistance->Calculate($LAT,$LON,$myrow["Latitude"],$myrow["Longitude"]);

							if(empty($radiusTo)) {
								if ($Distance <= $radious){
									//this ZIP Code is within $Miles of $ZIPCode
									//Display appropriate information to the user...
									//printf ("%s - %s<br>\n", $myrow["City"], $Distance);
									if($flag)
										$resultZipCodes .= ',';
									$resultZipCodes .= $myrow["ZIPCode"];
									$distance_radious_arr[$myrow["ZIPCode"]] = $Distance;
									$flag = true;
								}
							}
							else {
								
								if (doubleval($Distance) >= doubleval($radious) and doubleval($Distance) <= doubleval($radiusTo)){
									//this ZIP Code is within $Miles of $ZIPCode
									//Display appropriate information to the user...
									//printf ("%s - %s<br>\n", $myrow["City"], $Distance);
									if($flag)
										$resultZipCodes .= ',';
									$resultZipCodes .= $myrow["ZIPCode"];
									$distance_radious_arr[$myrow["ZIPCode"]] = $Distance;
									$flag = true;
								}
								
							}
						}

			//echo '<br>resultZipCodes---> '.$resultZipCodes;
			}
			
			if(!empty($distance_radious_arr) and is_array($distance_radious_arr)) {
				$resultZipCodes ='';
				asort($distance_radious_arr);
				foreach ($distance_radious_arr as $key => $value) {
					$resultZipCodes.= $key . ",";
				}
			}
			
			/*echo '<pre>';
			print_r($distance_radious_arr);
			echo '</pre>';
			echo $resultZipCodes;*/
		return trim($resultZipCodes,',');
		//return '544';
	}

}
?>