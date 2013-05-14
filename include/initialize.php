<?php
@session_start();
require_once('classes/websmarty.class.php');
require_once('classes/paging_product.class.php');
$smarty	= new WebSmarty('default'); //确定模板子目录
$smarty->caching = true;
require_once('classes/commons.class.php');