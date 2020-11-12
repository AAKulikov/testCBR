<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<?
		if($UserCss!='computer'){
			echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no", minimum-scale="1">';
		} else {
			echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
		}
		?>
		
		<title><?echo $page_data['title'];?></title>
		<meta name="Description" content="<?echo $page_data['descr'];?>" />
		<meta name="Keywords" content="<?echo $page_data['kw'];?>" />
		<meta name="format-detection" content="telephone=no">
		
		<meta property="og:type" content="website">    
		<meta property="og:url" content="<?echo '//'.SERVER_NAME.''.REQUEST_URI?>">
		<meta property="og:title" content="<?echo $page_data['title'];?>">
		<meta property="og:description" content="<?echo $page_data['descr'];?>">
		
		<meta name="msapplication-TileColor" content="#ffc40d">
		<meta name="theme-color" content="#ffffff">
		
		<link rel="stylesheet" href="/tpl/css/<?echo $UserCss;?>.css?d=<?php echo time()?>">
		<script src="https://code.jquery.com/jquery-3.4.1.min.js?ver=1" crossorigin="anonymous"></script>
		<script src="/tpl/js/cookie.js?ver=1"></script>
		
		<link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
		<script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
		
	</head>
<body>