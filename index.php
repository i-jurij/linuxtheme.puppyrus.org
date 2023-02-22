<?php
session_start();
define("INDEX", true);
header('Content-Type: text/html; charset=utf-8');

//poluchajem dostup k funkzijam, metodam i td ---------------
include './php/gallery.php';
//----------------------------------------------------------------

/*
//Kontroller ------------------------------------------------
if(!isset($_GET['one'])&&!isset($_GET['two'])&&!isset($_GET['thre'])) {
	$one = 'home';
   include 'controllers/home.php';
}
else {
	$one = addslashes(strip_tags(trim($_GET['one'])));
	if(file_exists('controllers/'.$one.'.php')) {
   	include 'controllers/'.$one.'.php' ;
   }
   else {
		header("HTTP/1.1 404 Not Found");
		$one = 'home';
		include 'controllers/home.php';
		$read='ОШИБКА 404! Данной страницы не существует'; 
		$content = '';  
   }
}
*/
// vyvod stranizy iz shablona -------------------------------
include './tpl/index.tpl' ;

exit ;

?>
