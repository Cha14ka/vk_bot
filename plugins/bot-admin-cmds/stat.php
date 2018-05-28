<?php
$plug_cmds = array('стат');
$plug_smalldescription = 'Статистика бота';
$plug_mainfunc = function ($kb_msg, $kb_cmds){
	exec('free -m',$cpu);
	$cpu = explode(' ',$cpu[1]);
	for($i=1; $i <= count($cpu); $i++){
		if ($cpu[$i]==''){
			unset($cpu[$i]);	
		}
		}
	$keys = array_keys($cpu);
	$text = 'Оперативная память: '.$cpu[$keys[2]].' Мб /'.$cpu[$keys[1]].' Мб';
	$stats = json_decode(file_get_contents('statistics.json'));
	$date_start = date_create();
	date_timestamp_set($date_start, $stats->start_time);
	$text = $text.'<br>Время работы: '.date_diff(date_create(), $date_start)->format('%D дней %H часов %I минут %S секунд');
	$text = $text.'<br>Вызовов кб за всё время: '.$stats->calls_counter->alltime;
	$text = $text.'<br>Вызовов кб за сегодня: '.$stats->calls_counter->day->count;
	$text = $text.'<br>Средняя скорость обработки запроса: '.$stats->mid;
	apisay($text, $kb_msg[3], $kb_msg[1]);
	unset($cpu);
	unset($data,$p,$timefix,$cpu,$text,$cpu1,$cpu2);
	};

register_command(array(
	'cmds' => $plug_cmds,
	'cmdfunc' => $plug_mainfunc,
	'smalldescription' => $plug_smalldescription,
	'adminonly' => '1'
));