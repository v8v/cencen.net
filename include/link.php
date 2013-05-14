<?php
@session_start();
require_once('classes/websmarty.class.php');
$smarty	= new WebSmarty('web'); //确定模板子目录
$smarty->caching = true;
//用户类
require_once('classes/commons.class.php');
$commons = new Commons('dynamic_content');
$result=$commons->getSearchBySQL(" SELECT * FROM dynamic_content WHERE id=7 ");
$smarty->assign('dynamic_content', $result[0]);
$smarty->display("link.html");
?>