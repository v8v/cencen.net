<?php
/*
程序作用：数据库扩展类
*/

require_once('mysql.class.php');
class Database extends DB_MySQL {

	/**
	 * 要操作的表
	 *
	 * @var str
	 */
	var $table;  

	function init($table)
	{
		//$this->servername = "localhost";
		//$this->dbname = "weiya_b001";
		//$this->dbusername = "root";
		//$this->dbpassword = "123!@#";

		$this->connect();
		$this->selectdb();
		
		//设定要操作的表
		$this->table = $table;
		$this->query("SET NAMES 'utf8'");
	}

	/**
	 * 从数组抽取数据插入数据库
	 *
	 * @param str $table
	 * @param array $arr
	 */
	function _insertFromArray($arr = ''){
		$arr = $arr ? $arr : $_POST;
		if(!empty($arr)){
			foreach($arr as $key => $value){
				$keys .= $key.',';
				$values .= "'".$value."',";
			}
			//去掉后面的逗号
			$keys = substr($keys,0,-1);
			$values = substr($values,0,-1);
			$query = "INSERT INTO ".$this->table." (".$keys.") VALUES (".$values.")";
			
			$this->query($query);
		}
	}

	/**
	 * 从数组抽取数据更新数据库
	 *
	 * @param str $table
	 * @param str $condition
	 * @param array $arr
	 */
	function _updateFromArray($condition, $arr = '')
	{
		$arr = $arr ? $arr : $_POST;
		if(!empty($arr)){
			$query = "UPDATE ".$this->table." SET ";
			foreach($arr as $key => $value){
				$query .= $key." = '".$value."',";
			}
			//去掉后面的逗号
			$query = substr($query,0,-1);
			$query .= " ".$condition;
			$this->query($query);
		}
	}

	/**
	 * 要分页的列表
	 *
	 * @param str $clause sql语句的字句，如where
	 * @param int $sizeOfpage 分页显示时，每页的记录数
	 * @return array
	 */
	function _pageList($clause = '', $sizeOfPage)
	{
		$query = "SELECT COUNT(*) FROM ".$this->table." ".$clause;
		$result = $this->fetch_one_array($query);
		$count = $result['0'];

		$p = new show_page;
		$p->setvar($_GET);
		$p->set($sizeOfPage, $count, $_GET['p']);
		$query = "SELECT * FROM ".$this->table." ".$clause." LIMIT ".$p->limit();
		unset($result);
		$result['content'] = $this->fetch_all_array($query);
		$result['page']	= $p->output['0'];
		return $result;
	}

	/**
	 * 添加
	 *
	 */
	function add()
	{
		$this->_insertFromArray();
	}

	
	/**
	 * 删除
	 * @param int $id
	 */
	function del($id)
	{
		$query = "DELETE FROM ".$this->table." WHERE id = ".$id;
		$this->query($query);
	}
	/**
	 * 
	 * @param $id
	 */
	function getInfoById($id)
	{
		$result = $this->fetch_one_array("SELECT * FROM ".$this->table." WHERE id = ".$id);
		return $result;
	}
	/**
	 * 编辑
	 * @param int $id
	 */
	function edit($id)
	{
		$this->_updateFromArray('WHERE id = '.$id);
	}

	/**
	 * 列表
	 *
	 * @param int $sizeOfpage 分页显示时，每页的记录数
	 * @return array
	 */
	function getList($sizeOfPage = 20)
	{
		$result = $this->_pageList('', $sizeOfPage);
		return $result;
	}

}
?>