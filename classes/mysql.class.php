<?php
error_reporting(7);

class DB_MySQL  {

	var $servername= DB_HOST;
	var $dbname = DB_DATABASE;
	var $dbusername = DB_USER;
	var $dbpassword= DB_PASSWORD;
	
	var $id = 0;
	var $link_id = 0;
	var $query_id = 0;

	var $querycount = 0;
	var $result;
	var $record = array();
	var $rows;
	var $affected_rows = 0;
	var $insertid;

	var $errno;
	var $error;

	//调试程序的时候打开 
	var $debug = 0;


	var $querylog = array();

	function geterrdesc() {
		$this->error = @mysql_error($this->link_id);
		return $this->error;
	}

	function geterrno() {
		$this->errno = @mysql_errno($this->link_id);
		return $this->errno;
	}

	function connect(){
		global $usepconnect;
		if ($usepconnect==1){
			if (!$this->link_id = @mysql_pconnect($this->servername,$this->dbusername,$this->dbpassword)){
				$this->halt("数据库链接失败");
			}
		} else {
			if (!$this->link_id = @mysql_connect($this->servername,$this->dbusername,$this->dbpassword)){
				$this->halt("数据库链接失败");
			}
		}
		return $this->link_id;
	}

	function selectdb(){
		if(!mysql_select_db($this->dbname)){
			$this->halt("数据库链接失败");
		}
	}

	function query($query_string) {
		global $querytime,$showqueries,$debug,$exp;
		if ($showqueries==1) {

			global $script_start_time;
			$pageendtime=microtime();
			$starttime=explode(" ",$script_start_time);
			$endtime=explode(" ",$pageendtime);

			$beforetime=$endtime[0]-$starttime[0]+$endtime[1]-$starttime[1];
		}
		//echo $query_string.':'.$this->link_id.'<br>';
		$this->result = mysql_query($query_string,$this->link_id);

		if (!$this->result) {
			$this->halt("SQL 无效: ".$query_string);
		}
		$this->querycount++;

		if ($showqueries==1) {
			$pageendtime = microtime();
			$starttime = explode(" ",$script_start_time);
			$endtime = explode(" ",$pageendtime);
			$aftertime = $endtime[0]-$starttime[0]+$endtime[1]-$starttime[1];
			$querytime += $aftertime-$beforetime;
		}

		if ($debug==1) {
			if ($showqueries==1) {
				echo "<pre><font face=\"verdana, arial, helvetica ,宋体\" style=\"font-size=7pt\">Query: $query_string</font></pre>";
				echo "<font face=\"verdana, arial, helvetica ,宋体\" style=\"font-size=7pt\">
					Before time: $beforetime |
					After time: $aftertime |
					Query time: ".($aftertime-$beforetime)."
					Queries: ".$this->querycount."
					</font><hr size=1>";
			}
			if ($exp==1) {
				if(substr(trim(strtoupper($query_string)), 0, 6) == 'SELECT'){
					$explain = mysql_query("EXPLAIN $query_string", $this->link_id);
					echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"#666666\">
						<tr bgcolor=\"#EEEEEE\">
						<td><font face=\"verdana, arial, helvetica ,宋体\" style=\"font-size=7pt\">table</font></td>
						<td><font face=\"verdana, arial, helvetica ,宋体\" style=\"font-size=7pt\">type</font></td>
						<td><font face=\"verdana, arial, helvetica ,宋体\" style=\"font-size=7pt\">possible_keys</font></td>
						<td><font face=\"verdana, arial, helvetica ,宋体\" style=\"font-size=7pt\">key</font></td>
						<td><font face=\"verdana, arial, helvetica ,宋体\" style=\"font-size=7pt\">key_len</font></td>
						<td><font face=\"verdana, arial, helvetica ,宋体\" style=\"font-size=7pt\">ref</font></td>
						<td><font face=\"verdana, arial, helvetica ,宋体\" style=\"font-size=7pt\">rows</font></td>
						<td><font face=\"verdana, arial, helvetica ,宋体\" style=\"font-size=7pt\">Extra</font></td>
						</tr>\n";
					while ($data = mysql_fetch_array($explain)){
						echo "<tr bgcolor=\"#FFFFFF\">\n";

						for($i=0;$i<8;$i++) {
							echo "<td><font face=\"verdana, arial, helvetica ,宋体\" style=\"font-size=7pt\">$data[$i]</font></td>\n";
						}
						echo "</tr>\n";
					}
					echo "</table>";
				}
			}
		}

		return $this->result;
	}


	function fetch_array($queryid) {

		$this->record = mysql_fetch_array($queryid);
		if (empty($queryid)){
			$this->halt("Query id 无效:".$queryid);
		}
		return $this->record;
	}

	function next_record() {
		if (!$this->result) {
			$this->halt("next_record called with no query pending.");
			return 0;
		}

		$this->record = @mysql_fetch_array($this->result);
		$this->rows   += 1;
		$this->errno  = mysql_errno();
		$this->error  = mysql_error();
		$stat = is_array($this->record);
		return $stat;

	}


	function f($Name) {
		if (isset($this->record[$Name])) {
			return $this->record[$Name];
		}
	}

	function listdb($tablename,$recordnum,$condition = "") 
	{
		$sql = "select * from ".$tablename." ".$condition." limit ".$recordnum;
		$result = $this->query($sql);
		$rows = array();
		while($record = $this->fetch_array($result)){
			$rows[] = $record;
		}
		return $rows;
	}

	function fetch_row($queryid) {

		$this->record = mysql_fetch_row($queryid);
		if (empty($queryid)){
			$this->halt("queryid 无效:".$queryid);
		}
		return $this->record;
	}

	function fetch_one_array($query) {

		$this->result =  $this->query($query);
		if (empty($this->result)){
			$this->halt("Query id 无效:".$query);
		}
		$this->record = $this->fetch_array($this->result);
		return $this->record;

	}

	function fetch_all_array($query) {

		$this->result =  $this->query($query);
		if (empty($this->result)){
			$this->halt("Query id 无效:".$query);
		}
		//echo "ok";
		while($result_bit=$this->fetch_array($this->result))
		{
			$r[] = $result_bit;
		}
		$this->record = $r;
		return $this->record;

	}

	function num_rows($queryid) {

		$this->rows = mysql_num_rows($queryid);

		if (empty($queryid)){
			$this->halt("Query id 无效:".$queryid);
		}
		return $this->rows;
	}

	function affected_rows() {
		$this->affected_rows = mysql_affected_rows($this->link_id);
		return $this->affected_rows;
	}

	function free_result($result){
		if (!mysql_free_result($result)){
			$this->halt("fail to mysql_free_result");
		}
	}

	function insert_id(){
		$this->insertid = mysql_insert_id();
		if (!$this->insertid){
			$this->halt("fail to get mysql_insert_id");
		}
		return $this->insertid;
	}

	function close() {
		@mysql_close($this->link_id);
	}


	function halt($msg){

		global $technicalemail,$debug;

		$message = "<html>\n<head>\n";
		$message .= "<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\">\n";
		$message .= "<STYLE TYPE=\"text/css\">\n";
		$message .=  "<!--\n";
		$message .=  "body,td,p,pre {\n";
		$message .=  "font-family : Verdana, Arial, Helvetica, sans-serif;font-size : 12px;\n";
		$message .=  "}\n";
		$message .=  "</STYLE>\n";
		$message .= "</head>\n";
		$message .= "<body bgcolor=\"#EEEEEE\" text=\"#000000\" link=\"#006699\" vlink=\"#5493B4\">\n";
		$message .= "<font size=10><b>Stuhome</b></font>\n<hr NOSHADE SIZE=1>\n";


		$content = "<p>数据库出错:</p><pre><b>".htmlspecialchars($msg)."</b></pre>\n";
		$content .= "<b>Mysql error description</b>: ".$this->geterrdesc()."\n<br>";
		$content .= "<b>Mysql error number</b>: ".$this->geterrno()."\n<br>";
		$content .= "<b>Date</b>: ".date("Y-m-d @ H:i")."\n<br>";
		$content .= "<b>Script</b>: http://".$_SERVER[HTTP_HOST].getenv("REQUEST_URI")."\n<br>";
		$content .= "<b>Referer</b>: ".getenv("HTTP_REFERER")."\n<br><br>";

		if ($this->debug == 1) {
			$message .= $content;
		}
		$message .= "<p>请尝试刷新你的浏览器,如果仍然无法正常显示,请联系<a href=\"mailto:$technicalemail\">管理员</a>.</p>";
		$message .= "</body>\n</html>";
		echo $message;

		$headers = "From: Stuhome Mailer <$technicalemail>\r\n";

		$content = strip_tags($content);
		@mail($technicalemail,"数据库出错",$content,$headers);

		exit;
	}
}
?>
