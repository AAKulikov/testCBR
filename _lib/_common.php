<?php

setlocale(LC_ALL, 'ru_RU.UTF8', 'rus_RUS.UTF8', 'russian'); 
session_start();

define('REQUEST_URI', $_SERVER['REQUEST_URI']);
define('PROTOCOL_URI', 'https://');
define('SERVER_NAME', $_SERVER['SERVER_NAME']);
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
define('HTTP_USER_AGENT', $_SERVER['HTTP_USER_AGENT']);

if (isset($_SERVER['HTTP_REFERER'])) {
	define('HTTP_REFERER', $_SERVER['HTTP_REFERER']);
}


include(DOCUMENT_ROOT.'/_lib/_function.php');
include(DOCUMENT_ROOT.'/_lib/_sql.php');
	
$cookie_array=array('phone', 'computer', 'tablet');

if(
	empty($_COOKIE['device']) or 
	!isset($_COOKIE['device']) or 
	empty($_COOKIE['style']) or 
	!isset($_COOKIE['style']) or 
	!in_array($_COOKIE['device'], $cookie_array)){
	
	include(DOCUMENT_ROOT.'/_lib/_deviceDetect.php');
	$detect = new Mobile_Detect;
	
	if ( $detect->isMobile() ) {
		$type_device='phone';
	}
	
	if($detect->isTablet()){
		$type_device='computer';
	}
	
	if( !$detect->isMobile() && !$detect->isTablet() ){
		$type_device='computer';
	}
	
	setcookie('device', $type_device, time() + 3600*24*365, '/');
	setcookie('style', $type_device, time() + 3600*24*365, '/');
	
	$UserDevice=$type_device;
	
} else {
	
		$UserDevice=$_COOKIE['device'];
		
}
	
$UserCss=$_COOKIE['style'];	

if(!in_array($_COOKIE['style'], $cookie_array)){
	
	header('Location: '.PROTOCOL_URI.''.SERVER_NAME.''.REQUEST_URI);

}