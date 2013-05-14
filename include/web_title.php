<?php

@session_start();

//权限控制
//require_once('classes/authority.class.php');

require_once('classes/websmarty.class.php');
$smarty	= new WebSmarty('web'); //确定模板子目录
$smarty->caching = true;

/*
require_once('classes/commons.class.php');
$commons = new Commons(product_sort);
$result=$commons->getSearchByWhere(" parentid=0 ");//经营产品类别
$smarty->assign('product_sort', $result);
*/
require_once('classes/commons.class.php');
$commons = new Commons(webconf);
$result=$commons->getInfoById(1);//经营产品类别
$smarty->assign('webconf', $result);


$smarty->display("web_title.html");
?>