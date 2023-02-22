<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Gallery of themes for linux distro.</title>
<link rel="stylesheet" href="/css/style.css">
<link rel="shortcut icon" href="/images/icon.png" type="image/x-icon">

</head>
<body>

<div class="wrapper">
	<!-- menu -->
	<nav class="navv">
		<h2>Галерея тем для дистрибутивов Linux.</h2>
		<a href="http://linuxtheme.sytes.net">LinuxTheme</a> / 
		<a href="/#open-modal">HowTo</a> / 
		<a href="/#open-modal1">Ссылки</a>
	</nav>
	<!-- end menu -->	

	<div class="container">
	
		<?php include 'howto.tpl'; include 'links.tpl'; ?>

		<?php if (empty($varcontent)) {?><?php } else echo $varcontent; ?>
		<?php if (!isset($func_content)) {?><?php } else $func_content() ?>

	</div>
</div><!-- end wrapper -->

<footer>
<hr>
<span style="font-size: 0.8em;">Сайт от I-Jurij. Email: <img src="images/e.png" alt="email" style="vertical-align: middle;" />
<?php  if (date("Y")==2017){echo '2017';}else{print '2017-'.date("Y");}; ?></span>
</footer>

</body>
</html>
