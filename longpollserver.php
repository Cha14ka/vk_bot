<?php
define('KB_SETTINGS', parse_ini_file('./settings.ini'));
define('KB_ADMINS', explode(PHP_EOL, file_get_contents('./admins.txt')));
define('KB_BLACKLIST', explode(PHP_EOL, file_get_contents('./blacklist.txt')));

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

function getLPServer(){
	$request = array(
		'v' => '5.68',
		'lp_version' => '2',
		'access_token' => KB_SETTINGS['token']
	);
	$get_params = http_build_query($request);
	$result = json_decode(file_get_contents('https://api.vk.com/method/messages.getLongPollServer?'. $get_params));
	$ls[] = $result->response->key;
	$ls[] = $result->response->server;
	$ls[] = $result->response->ts;
	$url = 'https://'.$ls[1].'?act=a_check&key='.$ls[0].'&ts='.$ls[2].'&wait=25&mode=2&version=2';
	return $url;
	unset($request,$get_params,$ls);
}
$serverurl = getLPServer();
$newLS = time()+30;
$check[]='';

while(TRUE){
	usleep(10);
	if (time() >= $newLS){
		$serverurl = getLPServer();
		$time = date('H:i:s');
		echo('['.$time.'] Сервер успешно обновлён'.PHP_EOL);
		$newLS = time()+30;
		unset($check);
		$check[]='';
	}
	
	$result = file_get_contents($serverurl);
	if(!empty($result))
		$result = json_decode($result);
	
	if(!empty($result->updates))
		foreach($result->updates as &$item){
			if(!in_array($item[1], $check))
				if (!empty($item[5])){
					if (empty($item[6]->from))
						$item[6]->from = $item[3];
					$item[5] .= ' ';

					if (!in_array($item[6]->from, KB_BLACKLIST) && in_array(strtolower_utf8(explode(' ', $item[5])[0]), KB_SETTINGS['maincmd'])){
						file_put_contents('./msgs.db', json_encode($item).PHP_EOL, FILE_APPEND);
						print('['.date('H:i:s').'] Упоминание бота в '.$item[3].'. Текст команды: '.$item[5].PHP_EOL);
						$check[]=$item[1];
					}
				}	
		}
}
