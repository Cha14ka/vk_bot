#!/usr/bin/php
<?php
//error_reporting(E_ALL);
set_error_handler('error_handler');
$date = date('d.m.Y');
$time = date(':i:s');
$tfix = date('H');
print('VKBot version 3. '.$tfix.$time.' '.$date.'г.'.PHP_EOL);
$stats = json_decode(file_get_contents('statistics.json'));
$stats->start_time = time();
file_put_contents('./statistics.json',json_encode($stats));
file_put_contents('./error.log', '');

define('KB_SETTINGS', parse_ini_file('./settings.ini'));
define('KB_TOKEN', KB_SETTINGS['token']);
define('KB_ADMINS', explode(PHP_EOL, file_get_contents('./admins.txt')));
define('KB_BL', explode(PHP_EOL, file_get_contents('./blacklist.txt')));

//Connect plugins
$kb_cmds = array();
$kb_cmdslist = array();
foreach(array_diff(scandir('./plugins/'), array('..', '.')) as $dir){
	if(is_dir('./plugins/'.$dir)){
		print('['.date('H:i:s').'] Загружается плагин "'.$dir.'"..... ');
		if((include_once glob('./plugins/'.$dir.'/'.$dir.'.php')[0]) == TRUE){
			print('Загружен'.PHP_EOL);
		}else{
			print('Не загружен'.PHP_EOL);	
		}
	}
}

//require('./iii.php');

//Declare global functions
function register_command($cmd_arr){
	if(empty($cmd_arr['cmds']) || empty($cmd_arr['cmdfunc'])) return 1;
	//if(array_diff($kb_cmdslist, $cmd_arr['cmds']) != $cmd_arr['cmds']) return 2;
	global $kb_cmds, $kb_cmdslist;
	$kb_cmdslist = array_merge($kb_cmdslist, $cmd_arr['cmds']);
	return $kb_cmds[] = array(
		0 => $cmd_arr['cmds'],
		1 => $cmd_arr['cmdfunc'],
		2 => (!empty($cmd_arr['smalldescription'])) ? $cmd_arr['smalldescription'] : '',
		3 => (!empty($cmd_arr['visible'])) ? $cmd_arr['visible'] : '1',
		4 => (!empty($cmd_arr['enabled'])) ? $cmd_arr['enabled'] : '1',
		5 => (!empty($cmd_arr['adminonly'])) ? $cmd_arr['adminonly'] : '0'
	);
}
function apisay($text, $id, $fm=null){
	//$text = str_replace('.', '[*]', $text);
	$text = str_replace('ftp','http',$text);
	$text = str_replace('&#','',$text);
	$html = array('&#',';','&','vto','vtope','vkbots','vkbot');
	$text = str_replace($html,' ',$text);
	$text = str_replace('#', '&#35;', $text);
	$text = preg_replace('@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@', '', $text);
	//print('['.date('H:i:s').'] Ответ в '.$id.'. Текст ответа: '.$text.PHP_EOL);
	$request = array(
		'v' => '5.68',
		'peer_id' => $id,
		'access_token' => KB_SETTINGS['token'],
		'forward_messages' => $fm,
		'message' => $text
	);
	$get_params = http_build_query($request);
	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => 'Content-type: application/x-www-form-urlencoded',
			'content' => $get_params
		)
	);
	$context  = stream_context_create($opts);
	file_get_contents('https://api.vk.com/method/messages.send', false, $context);
	return true;
	unset($opts,$context,$get_params,$request);
}

function apiget($mask){
	return file_get_contents('msgs.db');
}

function vkAPI($method, $parameters){
	
}

function strtolower_utf8($string){ 
	$convert_to = array( 
		"a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", 
    	"v", "w", "x", "y", "z", "à", "á", "â", "ã", "ä", "å", "æ", "ç", "è", "é", "ê", "ë", "ì", "í", "î", "ï", 
    	"ð", "ñ", "ò", "ó", "ô", "õ", "ö", "ø", "ù", "ú", "û", "ü", "ý", "а", "б", "в", "г", "д", "е", "ё", "ж", 
    	"з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "ч", "ш", "щ", "ъ", "ы", 
    	"ь", "э", "ю", "я" 
  	); 
  	$convert_from = array( 
    	"A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", 
    	"V", "W", "X", "Y", "Z", "À", "Á", "Â", "Ã", "Ä", "Å", "Æ", "Ç", "È", "É", "Ê", "Ë", "Ì", "Í", "Î", "Ï", 
    	"Ð", "Ñ", "Ò", "Ó", "Ô", "Õ", "Ö", "Ø", "Ù", "Ú", "Û", "Ü", "Ý", "А", "Б", "В", "Г", "Д", "Е", "Ё", "Ж", 
    	"З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ", "Ъ", "Ъ", 
    	"Ь", "Э", "Ю", "Я" 
  	); 

  	return str_replace($convert_from, $convert_to, $string); 
}

function error_handler($severity, $message, $filename, $lineno) {
  	if (error_reporting() == 0) {
    	return;
  	}
  	if (error_reporting() & $severity) {
    	file_put_contents('./error.log', 'ErrorCode: '.$severity.' Error: '.$message.' File: '.$filename.' Line: '.$lineno.PHP_EOL, FILE_APPEND);
		print('['.date('H:i:s').'] [ ОШИБКА ] Подробности смотрите в error.log'.PHP_EOL);
  	}
}



$child = popen('php longpollserver.php > longpollserver.log', 'r');

$msgs_stack = fopen('msgs.db', 'r');
$starttime = time();
$antidos = array();
print('['.date('H:i:s').'] Инициализация обработчика завершена.'.PHP_EOL);
while(TRUE) {
	while(TRUE) {
		usleep(10);
		if(feof($msgs_stack))
			rewind($msgs_stack);
		if($msgs_stack_msg = fgets($msgs_stack)){
			if($msgs_stack_msg[0] == '['){
				$kb_msg = json_decode($msgs_stack_msg);
				break;
			}
		}
	}
	file_put_contents('msgs.db', str_replace($msgs_stack_msg, '', file_get_contents('msgs.db')));
	
	//Обработка команды
	
	$processingtime = microtime(true);
	print('['.date('H:i:s').'] Упоминание бота в '.$kb_msg[3].'. Текст команды: '.$kb_msg[5].PHP_EOL);
	
	$curtime = time();
	if(!array_key_exists($kb_msg[3], $antidos)){
		$antidos[$kb_msg[3]] = [$curtime, 1, false];
	}else{
		if($antidos[$kb_msg[3]][2] === true){
			if(($curtime - $antidos[$kb_msg[3]][0]) < 120){
				continue;
			}else{
				$antidos[$kb_msg[3]] = [time(), 1, false];
			}
		}else{
			if($antidos[$kb_msg[3]][1] >= 4){
				$antidos[$kb_msg[3]][2] = true;
				$antidos[$kb_msg[3]][0] = $curtime;
				print('['.date('H:i:s').'] Блокировка чата '.$kb_msg[3].'.'.PHP_EOL);
				apisay('Этот чат был заблокирован на две минуты за многократный вызов в течении короткого времени.', $kb_msg[3]);
				continue;
			}else{
				$antidos[$kb_msg[3]][1]++;
			}
		}
	}
	foreach($antidos as $key => $dialog){
		if((($curtime - $dialog[0]) > 2) and (($curtime - $dialog[0]) != 0)){
			unset($antidos[$key]);
		}
	}

	/*$censored_words = array('цп', 'дп', 'cp', '')
	foreach(explode(' ', $kb_msg[5]) as $word){
		$word = strtolower_utf8($word);
		foreach()
		similar_text($word, , $percent);
		if($percent >= 80){
			$kb_msg[5] = str_replace($command, $cmd, $kb_msg[5]);
		}
	}*/
	
	foreach($kb_cmdslist as $cmd){
		$command = strtolower_utf8(explode(' ', $kb_msg[5])[1]);
		similar_text($command, $cmd, $percent);
		if($percent >= 80){
			$kb_msg[5] = str_replace($command, $cmd, $kb_msg[5]);
		}
	}
	
	if (in_array(strtolower_utf8(explode(' ', $kb_msg[5])[1]), $kb_cmdslist)) {
		foreach($kb_cmds as $cmd){
			if(in_array(strtolower_utf8(explode(' ', $kb_msg[5])[1]), $cmd[0])){
				if($cmd[4] == '1'){
					if(!(in_array($kb_msg[6]->from, KB_ADMINS) and !($cmd[5] == '1')) or (in_array($kb_msg[6]->from, KB_ADMINS) and !($cmd[5] == '1'))){
						$cmd[1]($kb_msg, $kb_cmds);
					}else{
						apisay('У вас нет прав доступа к команде "'.$cmd[0][0].'".', $kb_msg[3]);
					}
				}else{
					apisay('Команда "'.$cmd[0][0].'" отключена. Приносим свои извинения.', $kb_msg[3]);
				}
			}
		}
	} else {
		$randtext = array(
			1 => 'Данная функция не была найдена в моей инструкции.',
			2 => 'Возможно мой создатель ещё не сделал эту команду.',
			3 => 'Мы все надеемся что эта команда скоро появится.',
			4 => 'А ты точно уверен в своих желаниях?',
			5 => 'Скорее всего ты хотел написать что я няша.',
			6 => 'Вопряки всем стереотипам, у Type KB очень маленькая база данных для ответов.',
			7 => 'Попробуй написать без ошибок. Лучше всего загляни в помощь.',
			8 => 'Ой, а такой команды нет. Приношу свои извинения.',
			9 => 'Ошибка stop 0x00000000001, команда не найдена!',
			10 => 'KERNEL PANIC!!!'
			);
		apisay($randtext[rand(1,count($randtext))], $kb_msg[3], $kb_msg[1]);
		unset($randtext);
		/*$an = explode(' ',$kb_msg[5]);
		unset($an[0]);
		$an = implode(' ',$an);
		apisay(nullmsg($an,$kb_msg[6]->from), $kb_msg[3], $kb_msg[1]);*/
	}
	
	$processingtime = microtime(true)-$processingtime;
	
	print('['.date('H:i:s').'] Команда выполнена за '.$processingtime.'с'.PHP_EOL);
	$stats = json_decode(file_get_contents('statistics.json'));
	$stats->calls_counter->alltime++;
	if($stats->calls_counter->day->update_time != date('dmY')){
		$stats->calls_counter->day->count = 1;
		$stats->calls_counter->day->update_time = date('dmY');
	}else{
		$stats->calls_counter->day->count++;
	}
	$stats->mid = ($stats->mid + $processingtime) / 2;
	file_put_contents('statistics.json',json_encode($stats));
}

fclose($msgs_stack);


/*
vto&#38;#46;pe

1 - номер сообщения
3 - номер беседы (-200000000)
5 - текст
6 - от кого
*/


