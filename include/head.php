<?php
@session_start();
require_once('classes/websmarty.class.php');
$smarty	= new WebSmarty('web'); //确定模板子目录
$smarty->caching = true;
require_once('classes/commons.class.php');
//$commons = new Commons('dynamic_content');
//$result=$commons->getSearchBySQL(" SELECT * FROM dynamic_content WHERE id=5 ");
//$smarty->assign('dynamic_content', $result[0]);


//滚动图片
$commons = new Commons(tb_images);
$result=$commons->getSearchByWhere(" category=2 order by seq asc");//类别1
$smarty->assign('items', $result);
//滚动图片

//banner 大图片
//$commons = new Commons(tb_images);
//$result=$commons->getInfoById(2);//类别1
//if(!empty($result))
//{
//	$index_site_img=$result["img"];
//	$smarty->assign('index_site_img', $index_site_img);
//}
//banner 大图片



$smarty->display("head.html");
?>