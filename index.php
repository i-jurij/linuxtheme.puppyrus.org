<?php
session_start();
define("INDEX", true);
header('Content-Type: text/html; charset=utf-8');

//poluchajem dostup k funkzijam, metodam i td ---------------
include './php/gallery.php';
//----------------------------------------------------------------

// vyvod stranizy iz shablona -------------------------------
include './tpl/index.tpl' ;

exit ;

?>
