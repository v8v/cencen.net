<?php

@session_start();

//权限控制
//require_once('classes/authority.class.php');

require_once('classes/websmarty.class.php');
$smarty	= new WebSmarty('web'); //确定模板子目录
$smarty->caching = true;

//用户类
require_once('classes/commons.class.php');

$commons = new Commons(product_sort);
$result=$commons->getSearchBySQL(" select * from product_sort order by seq asc");
$smarty->assign('product_sort', $result);


$commons = new Commons('dynamic_content');
$result=$commons->getSearchBySQL(" SELECT * FROM dynamic_content WHERE id=7 ");
$smarty->assign('dynamic_content', $result[0]);

$smarty->display("in_bannerleft.html");

?>