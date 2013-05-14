<?php
/**
 * 使用实例:
 * $p = new show_web_page;		//建立新对像
 * $p->file="ttt.php";		//设置文件名，默认为当前页
 * $p->pvar="pagecount";	//设置页面传递的参数，默认为p
 * $p->setvar(array("a" => '1', "b" => '2'));	//设置要传递的参数,要注意的是此函数必须要在 set 前使用，否则变量传不过去，可以使用$p->setvar($_GET)
 * $p->set(20,2000,1);		//设置相关参数，共三个，分别为'页面大小'、'总记录数'、'当前页(如果为空则自动读取GET变量)'
 * $p->output(0);			//输出,为0时直接输出,否则返回一个字符串
 * $p->output(1);			//返回另一种分页格式（只显示上一页和下一页）的html代码
 * $p->output['1']  //只显示上一页下一页链接的http字符串
 * echo $p->limit();		//输出Limit子句。在sql语句中用法为 $sql="SELECT * FROM TABLE LIMIT ".$p->limit();
 *
 */


class show_web_page {

	/**
	 * 页面输出结果
	 *
	 * @var string
	 */
	var $output;

	/**
	 * 使用该类的文件,默认为 PHP_SELF
	 *
	 * @var string
	 */
	var $file;

	/**
	 * 页数传递变量，默认为 'p'
	 *
	 * @var string
	 */
	var $pvar = "p";

	/**
	 * 页面大小
	 *
	 * @var integer
	 */
	var $psize;

	/**
	 * 当前页面
	 *
	 * @var ingeger
	 */
	var $curr;

	/**
	 * 要传递的变量数组
	 *
	 * @var array
	 */
	var $varstr;

	/**
	 * 总页数
	 *
	 * @var integer
	 */
	var $tpage;

	/**
	 * 分页设置
	 *
	 * @access public
	 * @param int $pagesize 页面大小
	 * @param int $total    总记录数
	 * @param int $current  当前页数，默认会自动读取
	 * @return void
	 */
	function set($pagesize=20,$total,$current=false) {
		global $HTTP_SERVER_VARS,$HTTP_GET_VARS;

		$this->tpage = ceil($total/$pagesize);//总页数
		if (!$current) {$current = $HTTP_GET_VARS[$this->pvar];}
		if ($current>$this->tpage) {$current = $this->tpage;}
		if ($current<1) {$current = 1;}

		$this->curr  = $current;
		$this->psize = $pagesize;
		
		//共有183条记录，当前第 1 页，共 10 页

		if (!$this->file) {$this->file = $HTTP_SERVER_VARS['PHP_SELF'];}

		if ($this->tpage > 1) {
			$this->output['1'].='共有'.$total.'条记录，当前第'.$current.'页，共'.$this->tpage.'页            ';
			
			//首页
			$this->output['1'].=' <a href="'.$this->file.'?'.$this->pvar.'='.(1).($this->varstr).'"><font color="4d4d4d">首页</font></a>';
			
			//上一页
			if ($current>1) {
				$this->output['1'].=' <a href="'.$this->file.'?'.$this->pvar.'='.($current-1).($this->varstr).'"><font color="4d4d4d">上一页</font></a>';
			}else{
				$this->output['1'].=' <a disabled="disabled">上一页</a>';
			}

            //下一页
			if ($current<$this->tpage) {
				$this->output['1'].=' <a href="'.$this->file.'?'.$this->pvar.'='.($current+1).($this->varstr).'"><font color="4d4d4d">下一页</font></a>';
			}else{
				$this->output['1'].=' <a disabled="disabled">下一页</a>';
			}
			//尾页
		    $this->output['1'].=' <a href="'.$this->file.'?'.$this->pvar.'='.($this->tpage).($this->varstr).'"><font color="4d4d4d">尾页</font></a>';
			//$this->output['1'].=' 转到';
			//$this->output['1'].=' <input type="text" name="p" id="p"  value="" style="width:30px; height:12px; font-size:12px; border:solid 1px #7aaebd;"/>页';
			//$this->output['1'].=' <input id="button1_search" name="button1_search"  type="submit" value="转"></input> ';
		}
	}
	
/**
	 * 分页设置英文
	 *
	 * @access public
	 * @param int $pagesize 页面大小
	 * @param int $total    总记录数
	 * @param int $current  当前页数，默认会自动读取
	 * @return void
	 */
	function set_en($pagesize=20,$total,$current=false) {
		global $HTTP_SERVER_VARS,$HTTP_GET_VARS;

		$this->tpage = ceil($total/$pagesize);//总页数
		if (!$current) {$current = $HTTP_GET_VARS[$this->pvar];}
		if ($current>$this->tpage) {$current = $this->tpage;}
		if ($current<1) {$current = 1;}

		$this->curr  = $current;
		$this->psize = $pagesize;
		
		//共有183条记录，当前第 1 页，共 10 页

		if (!$this->file) {$this->file = $HTTP_SERVER_VARS['PHP_SELF'];}

		if ($this->tpage > 1) {
			//$this->output['1'].='共有'.$total.'条记录，当前第'.$current.'页，共'.$this->tpage.'页            ';
			$this->output['1'].='Total：'.$total.'  ，    '.$current.'／'.$this->tpage.' Page           ';
			
			//首页
			$this->output['1'].='&nbsp;&nbsp;<a href="'.$this->file.'?'.$this->pvar.'='.(1).($this->varstr).'"> <font color="4d4d4d">First</font></a>&nbsp;&nbsp;';
			
			//上一页
			if ($current>1) {
				$this->output['1'].=' <a href="'.$this->file.'?'.$this->pvar.'='.($current-1).($this->varstr).'"> <font color="4d4d4d">Previous</font> </a>&nbsp;&nbsp;';
			}else{
				$this->output['1'].=' <a disabled="disabled"> Previous </a>&nbsp;&nbsp;';
			}

            //下一页
			if ($current<$this->tpage) {
				$this->output['1'].=' <a href="'.$this->file.'?'.$this->pvar.'='.($current+1).($this->varstr).'"> <font color="4d4d4d">Next</font> </a>&nbsp;&nbsp;';
			}else{
				$this->output['1'].=' <a disabled="disabled"> Next </a>&nbsp;&nbsp;';
			}
			//尾页
		    $this->output['1'].=' <a href="'.$this->file.'?'.$this->pvar.'='.($this->tpage).($this->varstr).'"> <font color="4d4d4d">End</font> </a>';
			//$this->output['1'].=' 转到';
			//$this->output['1'].=' <input type="text" name="p" id="p"  value="" style="width:30px; height:12px; font-size:12px; border:solid 1px #7aaebd;"/>页';
			//$this->output['1'].=' <input id="button1_search" name="button1_search"  type="submit" value="转"></input> ';
		}
	}

	/**
	 * 要传递的变量设置
	 *
	 * @access public
	 * @param array $data   要传递的变量，用数组来表示，参见上面的例子
	 * @return void
	 */	
	function setvar($data) {
		foreach ($data as $k=>$v) {
			if ($k==$this->pvar)
			{
				continue;
			}
			$this->varstr.='&amp;'.$k.'='.urlencode($v);
		}
	}

	/**
	 * 分页结果输出
	 *
	 * @access public
	 * @param int id 风格编号
	 * @return string
	 */
	function output($id) {
		return $this->output[$id];
	}

	/**
	 * 生成Limit语句
	 *
	 * @access public
	 * @return string
	 */
	function limit() {
		return (($this->curr-1)*$this->psize).','.$this->psize;
	}

} //End Class
?>