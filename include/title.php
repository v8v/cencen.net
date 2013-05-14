
<?php

require_once('initialize.php');


$commons = new Commons(webconf);


$result=$commons->getInfoById(1);//经营产品类别


$smarty->assign('webconf', $result);

$smarty->display("title.html");


?>