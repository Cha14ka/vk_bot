<?php
$plug_cmds = array('помощь', 'команды', 'help','хелп');
$plug_smalldescription = 'Выводит эту памятку';
$plug_mainfunc = function ($kb_msg, $kb_cmds){
	$text = $kb_msg[5];
	$answer = explode(' ',$text);
	unset($answer[0]);
	unset($answer[1]);	
	$answer = implode(' ',$answer);
	if(!in_array($kb_msg[6]->from, KB_ADMINS)){
		$list = '[ ПОМОЩЬ ]'.PHP_EOL;
	}else{
		$list = '[ АДМИН ПОМОЩЬ ]'.PHP_EOL;
	}
	foreach($kb_cmds as $cmd){
		if((($cmd[5] == '0') and ($cmd[4] == '1') and ($cmd[3] == '1')) or in_array($kb_msg[6]->from, KB_ADMINS))
			$list .= KB_SETTINGS['maincmd'][0].' '.$cmd[0][0].' - '.$cmd[2].PHP_EOL;
	}
	$list .=  PHP_EOL.'[ РАЗРАБОТЧИКИ ]'.PHP_EOL.
			 'Максим Закиров - папа, разработчик плагинов, создатель.'.PHP_EOL.
			 'Михаил Ларченко - мама, разработка ядра, рефакторинг кода.';
	apisay($list,$kb_msg[3],$kb_msg[1]);
	unset($text,$answer,$list);
};

register_command(array(
		'cmds' => $plug_cmds,
		'cmdfunc' => $plug_mainfunc,
		'smalldescription' => $plug_smalldescription
));
