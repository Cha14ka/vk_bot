<?php
$plug_cmds = array('реэмоджи', 'reemoji');
$plug_smalldescription = 'Перевести язык эмоджи в фразу';
$plug_mainfunc = function ($kb_msg){
	$toho = $kb_msg[3];
	$torep = $kb_msg[1];
	$answer = $kb_msg[5];
	$answer = explode(' ',$answer);
	unset($answer[0]);
	unset($answer[1]);
	$answer = implode(' ',$answer);
	$answer = urlencode($answer);
	$url = 'https://translate.yandex.net/api/v1/tr.json/translate?id=b5c0902b.5a638058.9df0e44c-0-0&srv=tr-text&lang=emj-ru&reason=auto&text='.$answer;
	$get = json_decode(file_get_contents($url));
	//apisay(print_r($get,true),$toho,$torep);
	apisay($get->text[0],$toho,$torep);
};

register_command(array(
		'cmds' => $plug_cmds,
		'cmdfunc' => $plug_mainfunc,
		'smalldescription' => $plug_smalldescription
));
