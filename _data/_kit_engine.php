<?php
/*
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
*/
define('DOCUMENT_ROOT_START', $_SERVER['DOCUMENT_ROOT']);
include(DOCUMENT_ROOT_START.'/_lib/_function.php');
include(DOCUMENT_ROOT_START.'/_lib/_sql.php');

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'){
	echo '<h1>404</h1>';
	die();
}

if($_POST['action']==='page'){
	$load_page=$_POST['id'];
	include(DOCUMENT_ROOT_START.'/_data/_modules/_currency/_list.php');
	$code='200';
	echo json_encode(array('code'=>$code, 'res'=>$res,));
}

if($_POST['action']==='filter'){
	$load_page=$_POST['id'];
	include(DOCUMENT_ROOT_START.'/_data/_modules/_currency/_list.php');
	$code='200';
	echo json_encode(array('code'=>$code, 'res'=>$res,));
}


if($_POST['action']==='json'){
	$data=$_POST['data'];
	$file_name=date('d-m-Y_H:i:s', time()).'.json';
	
	$fp = fopen(DOCUMENT_ROOT_START.'/storage/'.$file_name, 'w');
	fwrite($fp, $data);
	$res='Скачать файл <a href="/storage/'.$file_name.'" download>https://ikbot.ru/storage/'.$file_name.'</a>';
	
	$code='200';
	echo json_encode(array('code'=>$code, 'res'=>$res,));
}