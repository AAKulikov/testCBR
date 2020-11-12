<?php

function onlyNum($arg) {
        $arg = preg_replace("/\D/","",$arg);
        return $arg;
}

function onlyNumPoint($arg) {
        $arg = preg_replace("/[^,.0-9]/", '', $arg);
        return $arg;
}

function sql($arg) {
	$arg = trim($arg);
	$arg = strip_tags($arg);
	$arg = stripslashes($arg);
	return $arg;
}

function RemoveEmptyArray($array)
{
	$result = array();
    foreach ($array as $key => $value){
        if ($value != '')
            $result[] = $value;
    }
    return $result;
}

function getSslPage($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function curl_get($host, $referer = null){
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_REFERER, $referer);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; U; ru) Presto/2.9.168 Version/11.51");
    curl_setopt($ch, CURLOPT_URL, $host);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $html = curl_exec($ch);
    curl_close($ch);
    return $html;
}

function curlPost($host, $referer = null, $post_data){
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_REFERER, $referer);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; U; ru) Presto/2.9.168 Version/11.51");
    curl_setopt($ch, CURLOPT_URL, $host);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $html = curl_exec($ch);
    curl_close($ch);
    return $html;
}


    function convertArray($array){
        global $resArray; 
        if(is_array($array)){
            foreach($array as $below){
                $res = convertArray($below); 

            }
        }else{
            $resArray[] = $array; 
        }
        return $resArray; 
    }