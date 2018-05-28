<?php
$plug_cmds = array('2k17', '2к17');
$plug_smalldescription = 'Переводчик с русского языка в язык людей поколения "КЕК".';
$plug_mainfunc = function ($kb_msg){
	include_once 'Lingua_Stem_Ru.php';
	$trace = false;
	
	$tmp = explode(' ', $kb_msg[5]);
	unset($tmp[0], $tmp[1]);
	$kb_msg[5] = implode(' ', $tmp);
		if($trace == true) print('0. '.$kb_msg[5].PHP_EOL);
	
	//Очистка от спец символов
	$kb_msg[5] = str_replace(array('!','?','.','"','[',']','(',')','{','}','&',':',';',',','—','-'), '', $kb_msg[5]);
	$kb_msg[5] = str_replace(PHP_EOL, ' ', $kb_msg[5]);
	$kb_msg[5] = str_replace('%', ' процентов ', $kb_msg[5]);
	
	if($trace == true) print('1. '.$kb_msg[5].PHP_EOL);
	
	//Перевод в нижний регистр
	$kb_msg[5] = strtolower_utf8($kb_msg[5]);
	
	if($trace == true) print('2. '.$kb_msg[5].PHP_EOL);
	
	//Исправление слов
	$speller = json_decode(file_get_contents('http://speller.yandex.net/services/spellservice.json/checkText?text='.implode('+', explode(' ', $kb_msg[5]))));
	foreach($speller as $error){
		if(!empty($error->s[0]))
			$kb_msg[5] = str_replace($error->word, $error->s[0], $kb_msg[5]);
	}
	$kb_msg[5] = strtolower_utf8($kb_msg[5]);
	
	if($trace == true) print('3. '.$kb_msg[5].PHP_EOL);
	
	//Восстановление корня
	$stemmer = new Lingua_Stem_Ru();
	foreach(explode(' ', $kb_msg[5]) as $word){
		$kb_msg[5] = str_replace($word, $stemmer->stem_word($word), $kb_msg[5]);
	}
	
	if($trace == true) print('4. '.$kb_msg[5].PHP_EOL);
	apisay($kb_msg[5], $kb_msg[3], $kb_msg[1]);
};

register_command(array(
		'cmds' => $plug_cmds,
		'cmdfunc' => $plug_mainfunc,
		'smalldescription' => $plug_smalldescription
));
