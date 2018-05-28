<?php
//кб повтори pornhub&vk.com#46;.com
//кб повтори pornhub&vk.com&.com
$plug_cmds = array('повтори');
$plug_smalldescription = 'Пишет текст переданный в аргументе';
$plug_mainfunc = function ($kb_msg, $kb_cmds){
		$text = $kb_msg[5];
		$answer = explode(' ',$text);
		unset($answer[0]);
		unset($answer[1]);
		$answer = implode(' ',$answer);
/*$answer = urldecode($answer);
		$answer = preg_replace('@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@', '', $answer);
		$answer = str_replace('&','|',$answer);
		$answer = explode('46;',$answer)[0];
		$answer = str_replace('#46;.','',$answer);
		$answer = str_replace('#46;','',$answer);*/
		apisay($answer, $kb_msg[3], $kb_msg[3]);	
		//apisay('Команда отключена',$toho, $torep);
		unset($text,$answer);
};

register_command(array(
		'cmds' => $plug_cmds,
		'cmdfunc' => $plug_mainfunc,
		'smalldescription' => $plug_smalldescription
));
