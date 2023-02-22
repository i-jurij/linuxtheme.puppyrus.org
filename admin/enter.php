<?php
session_start();

if($_SESSION['admin']){
	header("Location: /admin/admin.php");
	exit;
}

$admin = 'admin';
$pass = 'e79d4100f9c7e7591b830800e27e7ab8';

if($_POST['submit']){
	if($admin == $_POST['user'] AND $pass == md5($_POST['pass'])){
		$_SESSION['admin'] = $admin;
		header("Location: /admin/admin.php");
		exit;
	}else echo '<p>Логин или пароль неверны!</p>';
}
?>

<link rel="stylesheet" href="/css/style.css">

<nav class="bg na">
<p><a href="/">На Главную</a> или <a href="/admin/admin.php">Войти</a></p>
</nav>
<br>
<h3>Введите имя пользователя и пароль:</h3>

<div class="di">
<form class="for" method="post">
	Username: <input type="text" name="user" /><br />
	Password: <input type="password" name="pass" /><br />
	<input type="submit" name="submit" value="Войти" />
</form>
</div>
