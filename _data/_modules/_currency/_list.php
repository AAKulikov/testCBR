<?php

/*
	вывод жанных по валютам
*/

$res='<h1>Курс валют ';

if(!empty($_COOKIE['action'])){
	$res.='<button class="action" id="clear-button" data-action="">Сбросить</button>';
}

$res.='</h1>';


//списко валют
$query_list	='SELECT `code`, `name` FROM `lib_currency`
WHERE 
	status="1"		
ORDER BY 
	`id` ASC';

$sql_list = mysqli_query($linkDB, $query_list);
	while($row_list = mysqli_fetch_assoc($sql_list)){
		$list.='<option value="'.$row_list['code'].'" data-val="'.$row_list['code'].'">'.$row_list['name'].'</option>';
	}

//формы запросов
$res.='
<div id="select-form">
	<h4><span class="show" data-box="select-data" data-close="graph-data">Выбор по дате (и) или валюте</span></h4>
	<form id="select-data">
		<div class="flex-between-top-form">
			<div class="form-input">
				<span class="legend">дата</span>
				<input type="date" name="date-select"  value="'.date('Y-m-d', time()).'" class="required" placeholder="Укажите дату">
			</div>
			
			<div class="form-input">
				<span class="legend">валюта</span>
				<select name="currency" data-type="select" class="required">
					<option value="0">Все валюты</value>
					'.$list.'
				</select>
			</div>
			<div class="form-input">
				<span class="legend">&nbsp;</span>
				<button class="action" data-action="select-data">выбрать</button>
			</div>
		</div>
	</form>
</div>

<div id="graph-form">
	<h4><span class="show" data-box="graph-data" data-close="select-data">Динамика изменения курса</span></h4>
	<form id="graph-data">
		<div class="flex-between-top-form">
			<div class="form-input">
				<span class="legend">начальная дата</span>
				<input type="date" name="date-select-start" value="'.date('Y-m-d', strtotime(date('Y-m-d', time()).'- 15 days')).'"  class="required" placeholder="Укажите начальную дату графика">
			</div>
			
			<div class="form-input">
				<span class="legend">конечная дата</span>
				<input type="date" name="date-select-end" value="'.date('Y-m-d', time()).'"  class="required" placeholder="Укажите конечную дату графика">
			</div>
			
			<div class="form-input">
				<span class="legend">валюта</span>
				<select name="currency" data-type="select" data-error="Укажите валюту" class="required">
					<option value="">Выбрать валюту</value>
					'.$list.'
				</select>
			</div>
			
			<div class="form-input">
				<span class="legend">&nbsp;</span>
				<button class="action" data-action="graph-data">сформировать</button>
			</div>
		</div>
	</form>
	
</div>';


//вывод графика с экспортом

if($_COOKIE['action']=='graph-data'){
	/*
		делаем запрос выводим
	*/
	//	$sql_dt="and `filing_date`>='".sql($_COOKIE['filter_ticket_start'])." 00:00:00' AND `filing_date`<='".sql($_COOKIE['filter_ticket_start'])." 23:59:59'";
	$res.='<div class="filter-data"><h2>Динамика изменения курса</h2></div>';
	$res.='<h3>начальная дата: '.rus_date('d F Y', strtotime(sql($_COOKIE['date-select-start']))).', конечная дата: '.rus_date('d F Y', strtotime(sql($_COOKIE['date-select-end']))).', валюта: '.sql($_COOKIE['currency']).'</h3>';
	
	$tempDate = new DateTime(sql($_COOKIE['date-select-start']));
	$tempDateLast = new DateTime(sql($_COOKIE['date-select-end']));
	$differenceDay=$tempDateLast->diff($tempDate)->days;
	
	if($differenceDay<16){
	
		$query	='SELECT `code`, `exchange`, `money_id`, `dt` FROM `currency_rate`
			WHERE 
				`dt`>="'.sql($_COOKIE['date-select-start']).'"
			AND
				`dt`<="'.sql($_COOKIE['date-select-end']).'"
			AND `code`="'.sql($_COOKIE['currency']).'"
			ORDER BY `id` ASC ';
		
		$sql = mysqli_query($linkDB, $query);
		
		$count = mysqli_num_rows(mysqli_query($linkDB, $query));
		if($count>0){
				$query_name	='SELECT `name`, `engName`, `nominal` FROM `lib_currency`
					WHERE 
						`code`="'.sql($_COOKIE['currency']).'"		
					ORDER BY 
						`id` DESC 
					LIMIT 1';
					$sql_name = mysqli_query($linkDB, $query_name);
					
				$row_name = mysqli_fetch_assoc($sql_name);
			
			
				$json[]= array(
						'source' 			=>'ikbot.ru', 
						'datetime-export' 	=>date('d-m-Y H:i:s'),
						'currency' 			=>$row_name['name']
					);
				
				while($row = mysqli_fetch_assoc($sql)){
				$json[]= array(
						'date' 	=>$row['dt'], 
						'value' =>$row['exchange']
					);
				
				$labels.= '\''.date('d/m', strtotime($row['dt'])).'\',';
				$series.=''.$row['exchange'].',';
			}
			$res.='
			
				<div class="big-table"><div class="ct-chart"></div></div>
				<script>
				var data = {
					 
					  labels: ['.$labels.'],
					  
					  series: [
						['.$series.']
					  ]
					};

					var options = {
					  width: 900,
					  height: 400
					};

					
					new Chartist.Line(\'.ct-chart\', data, options);
				</script>
				<form>
					<textarea id="json" class="hide">'.json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT).'</textarea>
					<button id="export-json">Выгрузить в JSON</button>
					<div id="show-link-json"></div>
				</form>
			';
		} else {
			$res.='<h4>По указанным данным нет информации</h4>';
		}
	} 
		else {
			$res.='<h4>График можно построить на 15 дней</h4>';
		}
	 
}

//вывод отфильтрованных данных 

else if($_COOKIE['action']=='select-data'){
	$res.='<div class="filter-data"><h2>Выбор по дате (и) или валюте</h2></div>';
	/*	
		делаем запрос выводим
	*/
	
	
	if(sql($_COOKIE['currency'])=='0'){
		$showCode='все';
		$currencySql='';
	} else {
		$showCode=sql($_COOKIE['currency']);
		$currencySql='and `code`="'.sql($_COOKIE['currency']).'"';
	}
		
	$res.='<h3>дата: '.rus_date('d F Y', strtotime(sql($_COOKIE['date-select']))).', валюта: '.$showCode.'</h3>';
	
	$query	='SELECT `code`, `exchange`, `money_id`, `dt` FROM `currency_rate`
		WHERE 
			`dt`="'.sql($_COOKIE['date-select']).'"
		'.$currencySql.'
		ORDER BY `id` ASC ';
	
	$sql = mysqli_query($linkDB, $query);
	
	$count = mysqli_num_rows(mysqli_query($linkDB, $query));
	if($count>0){
		$res.='<div class="big-table"><table><tr><th width="5%">Код</th><th width="35%">Валюта</th><th width="20%">Курс</th><th width="20%">Номинал</th></tr><tbody>';	
		
		
		while($row = mysqli_fetch_assoc($sql)){
			
			$query_name	='SELECT `name`, `engName`, `nominal` FROM `lib_currency`
				WHERE 
					`id`="'.$row['money_id'].'"		
				ORDER BY 
					`id` DESC 
				LIMIT 1';
				$sql_name = mysqli_query($linkDB, $query_name);
				
				$row_name = mysqli_fetch_assoc($sql_name);
			
			$res.='<tr>
					<td><small>'.$row['code'].'</small></td>
					<td><h4>'.$row_name['name'].'</h4>'.$row_name['engName'].'</td>
					<td><strong>'.$row['exchange'].'</strong></td>
					<td>'.$row_name['nominal'].'</td>
				</tr>';
		}
		$res.='</tbody></table></div>';
	} else {
		$res.='<h4>По указанным данным нет информации</h4>';
	}
	
	
}

//вывод первой таблицы с постраничной навигацией
else {
	
	//выбор всех записей для разбивки на страницы
	$query_all = 'SELECT `id` FROM `lib_currency` WHERE status="1"';
	$count = mysqli_num_rows(mysqli_query($linkDB, $query_all));
	if(!empty($load_page)){
		$post_page=$load_page;
	} else {
		$post_page='1';
	}

	$vpages='8';
	$start_pos = ($post_page-1) * $vpages;
	$res.='<div class="big-table"><table><tr><th width="5%">Код</th><th width="35%">Валюта</th><th width="20%">Дата</th><th width="20%">Курс</th><th width="20%">Номинал</th></tr><tbody>';	
		
		//выбор данныз для вывод на старнице
		$query	='SELECT * FROM `lib_currency`
		WHERE 
			status="1"		
		ORDER BY 
			`id` ASC 
		LIMIT '.$start_pos.', '.$vpages.' ';

		$sql = mysqli_query($linkDB, $query);
		
			while($row = mysqli_fetch_assoc($sql)){
				
				$query_rate	='SELECT `exchange` FROM `currency_rate`
				WHERE 
					`money_id`="'.$row['id'].'"		
				ORDER BY 
					`id` DESC 
				LIMIT 1';
				$sql_rate = mysqli_query($linkDB, $query_rate);
				
				$row_rate = mysqli_fetch_assoc($sql_rate);
				
				$res.='<tr>
					<td><small>'.$row['code'].'</small></td>
					<td><h4>'.$row['name'].'</h4>'.$row['engName'].'</td>
					<td>'.rus_date('d F Y', strtotime($row['lastUpdateData'])).'</td>
					<td><strong>'.$row_rate['exchange'].'</strong></td>
					<td>'.$row['nominal'].'</td>
				</tr>';
		}
		$res.='</tbody></table></div>';

		//постраничная навигация 
		$show_link=3;
		$last_text='<';
		$next_text='>';

		$pages_count = ceil($count / $vpages);
		if ($pages_count == 1) return false;
		if ($post_page > $pages_count) $post_page = $pages_count;
		$begin=$post_page-intval($show_link/2);
		
		$res.=  '<ul class="pagination">';
		if ($begin>2 && !isset($show_dots)) 
			$res.=  '<li class="page" data-page="1"> <a href="#nogo">'.$last_text.'</a></li> ';
		for ($j=0;$j<=$show_link;$j++)
		{
			$i=$begin+$j;
			if ($i<1) continue;
			if (!isset($show_dots)&& $begin>1)
			{
				$res.= '<li class="page" data-page="'.$i.'"> <a href="#nogo">...</a></li> ';
				$show_dots='';
			}
			if ($i>$pages_count) break;
			if ($i==$post_page) $res.= '<li class="active"><span>'.$i.'</span></li>';
			else $res.= '<li class="page" data-page="'.$i.'"><a href="#nogo">'.$i.'</a></li> ';
			
			if ($j==$show_link && $i<$pages_count)
			$res.= '<li class="page" data-page="'.($i+1).'"><a href="#nogo">...</a></li> ';
		}
		if ($begin+$show_link+1<$pages_count)
		$res.=  '<li class="page" data-page="'.$pages_count.'"><a href="#nogo">'.$next_text.'</a></li>';
		$res.= '</ul>';
}	