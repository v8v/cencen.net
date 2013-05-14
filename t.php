<?php
  @session_start();
  require_once('classes/websmarty.class.php');
  $smarty = new WebSmarty('web');
  $smarty -> caching = true;
  
?>

<!doctype html>
<html><head>
<meta charset="utf-8">
<title>无标题文档</title>
</head>
<link rel="stylesheet" type="text/css" href="ui/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="ui/css/pfdindisplaypro.css">
<style>

h1,h2 {
	font-family: PFDinDisplayPro, sans-serif;
	color: black;
}
</style>
<body>

<div class='container'>
<h2>CenCen.Net</h2>
<hr />
</div>

</body>
</html>