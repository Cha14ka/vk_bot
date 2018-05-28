<?php
$plug_cmds = array('кальк', 'кал','к');
$plug_smalldescription = 'Калькулятор';
$plug_mainfunc = function ($kb_msg){
	$text = explode(' ',$kb_msg[5]);
	unset($text[0],$text[1]);
	$text = implode(' ',$text);
	if (stristr($text,'^')){
		apisay('Увы бот слишком слабый и не умеет в степени',$kb_msg[3],$kb_msg[1]);	
	}else{
		$calc = file_get_contents('https://www.calcatraz.com/calculator/api?c='.urlencode($text));
		if (stristr($calc,'answer')){
			apisay('Я слишком тупая для таких сложный примеров. Иди сам уебан шевели своими мозгами и реши это мне',$kb_msg[3],$kb_msg[1]);
		}else{
		apisay($text.' = '.$calc,$kb_msg[3], $kb_msg[1]);}	
	}
};

register_command(array(
		'cmds' => $plug_cmds,
		'cmdfunc' => $plug_mainfunc,
		'smalldescription' => $plug_smalldescription
));
