<?php
define('DOCUMENT_ROOT_START', $_SERVER['DOCUMENT_ROOT']);
include(DOCUMENT_ROOT_START.'/_lib/_common.php');

if(empty($_GET['path'])){
	
	$page_data['title']='CBR';
	$page_data['descr']='CBR';
	$page_data['kw']='CBR';		
	
	include(DOCUMENT_ROOT.'/tpl/_header.php');
	include(DOCUMENT_ROOT.'/tpl/'.$UserCss.'/_header.php');
	
	//загрузка данных
	include(DOCUMENT_ROOT.'/_data/_modules/_currency/_list.php');
	echo $res;

	include(DOCUMENT_ROOT.'/tpl/'.$UserCss.'/_footer.php');
	include(DOCUMENT_ROOT.'/tpl/_footer.php');
	die();
	
} else {
	echo '<h1>404</h1>';
	die();
}