<?php
  require_once('initialize.php');
  
//	$commons = new Commons('dynamic_content');

//	$result=$commons->getSearchBySQL(" SELECT * FROM dynamic_content WHERE id=1 ");

//	$smarty->assign('dynamic_content', $result[0]);

	$smarty->display("index.html");

?>
