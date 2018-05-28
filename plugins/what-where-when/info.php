<?php
$plug_cmds = array('инфа', 'вероятность');
$plug_smalldescription = 'Пишет вероятность собтия из аргумента';
$plug_mainfunc = function ($kb_msg, $kb_cmds){
		$text = $kb_msg[5];
		$answer = explode(' ',$text);
		$answer = str_replace('?','',$answer);
		$answer = str_replace(')','',$answer);
		unset($answer[0]);
		unset($answer[1]);
		$answer = implode(' ',$answer);
		apisay('Вероятность того что '.$answer.' равна '.rand(0,146).'%',$kb_msg[3],$kb_msg[1]);
		unset($text,$answer);
};

register_command(array(
		'cmds' => $plug_cmds,
		'cmdfunc' => $plug_mainfunc,
		'smalldescription' => $plug_smalldescription
));