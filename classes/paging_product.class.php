<?php
/*
程序作用：用户类
*/

//初始化系统变量
require_once('database.class.php');
require_once('webpage.class.php');

class PagingProduct extends Database
{	
	var $table;  
	
	function PagingProduct($table)
	{
		$this->init($table);
		$this->table = $table;
	}

	/**
	 * 未审核用户列表
	 *
	 * @param int $sizeOfpage 分页显示时，每页的记录数
	 * @return array
	 */
	function getList($sizeOfPage = 20)
	{
		$result = $this->_pageList("WHERE isPass = 0", $sizeOfPage);
		return $result;
	}

	/**
	 * 用户列表
	 *
	 * @param int $sizeOfpage 分页显示时，每页的记录数
	 * @return array
	 */
	function getBadList($sizeOfPage = 20)
	{
		$result = $this->_pageList("WHERE badBill > ".BAD_BILL_LIMIT, $sizeOfPage);
		return $result;
	}

	/**
	 * 搜索产品
	 *
	 * @param str $keyword
	 * @param int $sizeOfPage
	 * @return array
	 */
	function getSearch($keyword, $sizeOfPage = 20)
	{
		//echo "<script>alert('$sizeOfPage')</script>";
		try { 
		$clause = "WHERE 1 =1 ";
		$clause .= $keyword;
		//echo "<script>alert('1')</script>";
		//查询数据
		$query = " SELECT COUNT(1) FROM ".$this->table." " ;
		
		$query .= $clause;
		
		$result = $this->fetch_one_array($query);
		$count = $result['0'];
		
		$p = new show_web_page;
		
		$p->setvar($_GET);
		$p->set($sizeOfPage, $count, $_GET['p']);
		
		$query = " SELECT * FROM ".$this->table." ";
		$query .= $clause." ORDER BY id  desc LIMIT ".$p->limit();
		//echo "<script>alert('$query')</script>";
		unset($result);
		
		$result['content'] = $this->fetch_all_array($query);
		$result['page']	= $p->output['1'];
		}catch (Exception $e) {  
		 print $e->getMessage();   
		}
		return $result;
	}
	
/**
	 * 搜索产品
	 *
	 * @param str $keyword
	 * @param int $sizeOfPage
	 * @return array
	 */
	function getSearch_en($keyword, $sizeOfPage = 20)
	{
		$clause = "WHERE 1 =1 ";
		//$clause .= empty($keyword) ? "" : "AND partno LIKE '%".$keyword."%'";
		$clause .= $keyword;
		
		//查询数据
		$query = " SELECT COUNT(1) FROM ".$this->table." ";
		$query .= $clause;
		
		$result = $this->fetch_one_array($query);
		$count = $result['0'];
		
		$p = new show_web_page;
		$p->setvar($_GET);
		$p->set_en($sizeOfPage, $count, $_GET['p']);
		
		$query = " SELECT * FROM ".$this->table." ";
		$query .= $clause." ORDER BY id desc LIMIT ".$p->limit();
		unset($result);
		$result['content'] = $this->fetch_all_array($query);
		$result['page']	= $p->output['1'];
		return $result;
	}
	
    /**
	 * 获取类别列表
	 * @return array
	 */
	function getProductSortList($parid)
	{
		$query = " SELECT * FROM product_sort where parentid=".$parid;
		
		$result['content'] = $this->fetch_all_array($query);
		//$bb=count($result);
		//echo "<script>alert('$bb');</script>";
		return $result;
	}
	
	
	/**
	 * 单个产品信息
	 *
	 * @param int $id 产品Id
	 * @return array
	 */
	function getInfoById($id)
	{
		$result = $this->fetch_one_array("SELECT * FROM ".$this->table." WHERE id = ".$id);
		return $result;
	}
}

?>