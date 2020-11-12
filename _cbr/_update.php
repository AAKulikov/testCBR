<?php
setlocale(LC_ALL, 'ru_RU.UTF8', 'rus_RUS.UTF8', 'russian'); 
session_start();


/*
	Ежедневные обновления
*/

define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
include(DOCUMENT_ROOT.'/_cbr/_tech/_function.php');
include(DOCUMENT_ROOT.'/_cbr/_tech/_sql.php');

/*
	шаблон запроса
	http://www.cbr.ru/scripts/XML_dynamic.asp?date_req1=01/01/1997&date_req2=11/11/2020&VAL_NM_RQ=R01233	
*/

$select	='SELECT 
		*
	FROM 
		`lib_currency`
	WHERE `status`="1"';
	
	$sql = mysqli_query($linkDB, $select);
	
	while($row = mysqli_fetch_assoc($sql)){
		
		echo '<h1>'.$row['id'].'</h1>';
		
		
		$nexDay=date('d/m/Y/', strtotime($row['lastUpdateData'].' +1 day '));
		$toDay=date('d/m/Y/', time());
		
	
		$url='http://www.cbr.ru/scripts/XML_dynamic.asp?date_req1='.$nexDay.'&date_req2='.$toDay.'&VAL_NM_RQ='.$row['code'];
		
		$load = curl_get($url , 'http://google.com');
		
		$xml = simplexml_load_string($load);
		$data = json_encode($xml);	
		$json=json_decode($data,TRUE);
			
			
			
			 
			$clearData=array_chunk(convertArray($json['Record']), 4);
			$count=count($clearData);
			$dtLoad=$count-1;
			
			
			for($i=0;$i<=$dtLoad;$i++){
				
				$dt= date('Y-m-d', strtotime($clearData[$i][0]));
				$exchange= str_replace(',','.',$clearData[$i][3]);
				$nominal= $clearData[$i][2];
				
				if($exchange!=''){
					$query = "INSERT INTO `currency_rate` SET
						`money_id`		='".$row['id']."', 
						`code`			='".$row['code']."', 
						`dt`			='".$dt."', 
						`exchange`		='".$exchange."',
						`nominal`		='".$nominal."'";
				
					echo $query.'<br />';

					$result = mysqli_query($linkDB,$query);	
				
					$lastDate=date('Y-m-d', strtotime($clearData[$dtLoad][0]));
				} 
				
			
			}
			
			
			if($lastDate!=''){
				$tempDate = new DateTime();
				$tempDateLast = new DateTime($lastDate);
				
				$differenceDay=$tempDateLast->diff($tempDate)->days;
				
				//выясняем статус если обновлялось больше чем 60 дней назад, ставим архивный статус
				
				if($differenceDay>59){
					$status='0';
				} else {
					$status='1';
				}
				
				//обновляем
				
				$sql_update="UPDATE `lib_currency` SET 
						status='".$status."',
						lastUpdateData='".$lastDate."'
						WHERE code='" . $row['code'] . "' LIMIT 1";			
				
				echo $sql_update.'<br />';
				$result = mysqli_query($linkDB,$sql_update) or die($sql_update);
				
				unset($resArray);
			}
	
	}