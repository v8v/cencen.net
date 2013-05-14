<?php
/*
程序作用：扩展后的smarty
 */
require_once('Smarty-3.1.12/libs/Smarty.class.php');
//require_once('Smarty-2.6.26/libs/Smarty.class.php');
class MySmarty extends Smarty {
	var $left_delimiter	= '{#';
	var $right_delimiter= '#}';
	var $force_compile= true;


	function MySmarty($entry)
	{
		$this->Smarty();
		$this->template_dir = '../templates/'.$entry;
		$this->compile_dir  = '../cache/'.$entry;
		$this->cache_dir    = '../cache/'.$entry;
		if(!is_dir($this->compile_dir))
		{
			mkdir($this->compile_dir);
		}
		if(!is_dir($this->cache_dir))
		{
			mkdir($this->cache_dir);
		}
	}
}
?>