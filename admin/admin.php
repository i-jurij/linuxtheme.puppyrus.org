<?php
//define("ADM", true);
//Устанавливаем кодировку и вывод всех ошибок
header('Content-Type: text/html; charset=UTF-8');
require "auth.php";
?>
<link rel="stylesheet" href="/css/style.css">
<div>

<nav class="bg na">
<a href="/">Главная</a> | 
<a href="/admin/admin.php?do=logout">Выход</a>
</nav>

<div class="bp bg">
<h3>Загрузка архива темы и скриншота</h3>
<form class="for" method="post" enctype="multipart/form-data">

<div class="di">
<label class="lab" for="file">
<p class="pp">Архив: </p>
<span class="inf">контейнер: tar.gz,<br>
размер: до 100Мб, <br>
пример имени: adwaita-gtk3.20.tar.gz)
</span>
<input class="fileContainer" type="file" name="archive">
</label>
</div>

<div class="di">
<label class="lab" for="file"><p class="pp">Скриншот:</p>
<span class="inf">
формат: png,<br> 
размеры до 1440x1440
</span>
</label>
<input type="file" name="picture">
<!--максимально допустимый размер файла для загрузки в байтах (100Mb) dopolnitelno ogranichit razmer v php.ini (upload_max_size) -->
<input type="hidden" name="MAX_FILE_SIZE" value="104857600" /> 
</div>
<div class="label">
<input type="submit" name="upload" value="Загрузить">
<input type="reset">
</div>
</form>
</div>


<?php

function createThumbnail($filename,$final_width_of_image,$path_to_image_directory, $path_to_thumbs_directory) {	
	if(preg_match('/[.](jpg)$/', $filename)) {
		$im = imagecreatefromjpeg($path_to_image_directory . $filename);
	} else if (preg_match('/[.](gif)$/', $filename)) {
		$im = imagecreatefromgif($path_to_image_directory . $filename);
	} else if (preg_match('/[.](png)$/', $filename)) {
		$im = imagecreatefrompng($path_to_image_directory . $filename);
	}
	
	$ox = imagesx($im);
	$oy = imagesy($im);
	
	$nx = $final_width_of_image;
	$ny = floor($oy * ($final_width_of_image / $ox));
	
	$nm = imagecreatetruecolor($nx, $ny);
	
	imagecopyresized($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);
	
	if(!file_exists($path_to_thumbs_directory)) {
	  if(!mkdir($path_to_thumbs_directory)) {
           die("Что-то пошло не так при создании превью.");
	  } 
       }

	imagepng($nm, $path_to_thumbs_directory . $filename);
	$tn = '<p class="pp"><img class="imgg" src="' . $path_to_thumbs_directory . $filename . '" alt="image" />';
//	$tn = '<a href="' . $path_to_thumbs_directory . $filename . '">Посмотреть'.basename($filename).'<a>';
	$tn .= 'Превью создано</p>';
	echo $tn;
}


//максимальное количество загружаемых на сервер файлов за один раз
ini_set('max_file_uploads', '2');
////максимальный размер файлов 100Mb
ini_set('upload_max_filesize', '100M');

$final_width_of_image = 320;
//папка для загрузки архивов
$dir_archive = '../downloads';
//папка для загрузки изображений
$dir_screenshot = '../images/screen/';
//папка для превью
$dir_pre = '../images/screen/prev/';

if (($_SERVER['REQUEST_METHOD']==='POST') && isset($_POST['upload'])) {
	//gruzim archiv
	$filename = basename($_FILES['archive']['name']);
  	$ext = substr($filename, strrpos($filename, '.') + 1);
	if ($_FILES['archive']['error']==UPLOAD_ERR_OK && ($ext=="gz")) { 
		$dir_archives = $dir_archive . '/' . $_FILES['archive']['name']; 
		if (move_uploaded_file($_FILES['archive']['tmp_name'], $dir_archives)) { 
			echo '<p class="pp">Архив загружен</p>'; 
//upload image			
				if ( $_FILES['picture']['error'] == UPLOAD_ERR_OK && $_FILES['picture']['type'] == 'image/png' ) { 
		$name = explode("-",$_FILES['archive']['name'])[0].'.png';
		$dir_screenshots = $dir_screenshot.$name; 
		$dir_prevs = $dir_pre.$name; 
		if (move_uploaded_file($_FILES['picture']['tmp_name'], $dir_screenshots)) { 
			$size = getimagesize($dir_screenshots);
			if ($size[0] == 0 || $size[0] > 1440 || $size[1]>1440) { 
				echo '<p class="pp">Размер скриншота в пикселях больше установленного</p>';
				unlink($dir_screenshots);
				exit;
			}
			
			createThumbnail($name,$final_width_of_image, $dir_screenshot, $dir_pre);	
			
			echo '<p class="pp">Скриншот загружен</p>';
		} 	
		else {
				echo '<p class="pp">Скриншот не загружен</p>';
		}
	} 
	else {
		switch ($_FILES['picture']['error']) {
			case UPLOAD_ERR_FORM_SIZE:
			case UPLOAD_ERR_INI_SIZE:
			echo '<p class="pp">Размер скриншота в Мб больше допустимого (см. admin.php и pnp.ini)</p>';
			brake;
			case UPLOAD_ERR_NO_FILE:
			echo '<p class="pp">Скриншот не выбран!</p><br />';
			break;
			default:
			echo '<p class="pp">Что-то пошло не так при загрузке скриншота</p>';
		}
	}
//end upload image
		} 
		else {
			echo '<p class="pp">Архив не загружен</p>';
		}
	} 
	else {
		switch ($_FILES['archive']['error']) {
			case UPLOAD_ERR_FORM_SIZE:
			case UPLOAD_ERR_INI_SIZE:
			echo '<p class="pp">Размер архива в Мб больше допустимого (см. admin.php и pnp.ini)</p>';
			break;
			case UPLOAD_ERR_NO_FILE:
			echo '<p class="pp">Архив не выбран!</p>';
			break;
			default:
			echo '<p class="pp">Что-то пошло не так при загрузке архива</p>';
		}	
	}
	
//	header("Location: $PHP_SELF");
	unset($_POST); 
}

// DELETE -----------------------------------------------------

// start form for delete
$dh = opendir("../downloads");
echo '
	<div class="bp bg">
	<h3>Удаление темы (архив, скриншот, превью)</h3>';	
while($filename = readdir($dh)) {
	if( basename($filename)==='.' || basename($filename)==='..' ){ continue; }
	echo '
	<form class="inp" name="deleter" method="post">
	<input name="dh" type="hidden" value="'.$dh.'">
	<input type="hidden" name="archfordel" value="'.$filename.'">
	<span style="display: block; padding: 0.2em; background: #000;">'
	.$filename.' 
	</span>
	<input type="submit" name="archdelete" value="Удалить">
	</form>';
}
echo '</div>';
closedir($dh);
//end form for delete

if(($_SERVER['REQUEST_METHOD']==='POST') && isset($_POST['archdelete']) ) {
	//if (file_exists($path)) unlink($path);
	//proverka na windows, jesli da - del file
		$filename = htmlspecialchars($_POST['archfordel']);
		$arch = '../downloads/'.$filename;
	  	$screen = '../images/screen/'.explode("-",$filename)[0].'.png';
  		$prev = '../images/screen/prev/'.explode("-",$filename)[0].'.png';
	
	if ( isset($_ENV['WINDIR']) ) {
  		@exec('del '. $arch);
  		@exec('del '. $screen);
  		@exec('del '. $prev);
 		
  		if (file_exists($arch)) {
			die('<p class="pp">Ошибка! Архив не удален!</p>') ;
  		}
  		elseif (file_exists($screen))  {
  			die('<p class="pp">Ошибка! Скриншот не удален!</p>');
  		}
  		elseif (file_exists($prev)) {
  			die('<p class="pp">Ошибка! Превью не удалено!</p>');
  		}
  		else {
  			echo '<p class="pp">Файлы удалены</p>';
  		}
	}
	 
	//udaliajem v linux (unix)	
	else {
		@unlink($arch) ;
		@unlink($screen) ;
		@unlink($prev) ;
 		if (file_exists($arch)) {
			die('<p class="pp">Ошибка! Архив не удален!</p>') ;
  		}
  		elseif (file_exists($screen))  {
  			die('<p class="pp">Ошибка! Скриншот не удален!</p>');
  		}
  		elseif (file_exists($prev)) {
  			die('<p class="pp">Ошибка! Превью не удалено!</p>');
  		}
  		else {
  			echo '<p class="pp">Файлы удалены</p>';
  		}
	}

	unset($_POST,$arch,$screen,$prev,$filename); 
//	header("Location: $PHP_SELF");
//	header("Location: admin.php");
}

?>

</div>

