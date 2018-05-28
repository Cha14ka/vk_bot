<?php
$plug_cmds = array('когда');
$plug_smalldescription = 'Пишет когда случится событие написанное в аргументе';
$plug_mainfunc = function ($kb_msg, $kb_cmds){
		$text = $kb_msg[5];
		$answer = explode(' ',$text);
		unset($answer[0]);
		unset($answer[1]);
		$answer = implode(' ',$answer);
		$answer = str_replace('?','',$answer);
		$answer = str_replace(')','',$answer);
		$answer = str_replace('меня','тебя',$answer);
				$month = array('января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
		$random = rand(0,100);
		$when = 'Считаю что '.$answer.' примерно '.rand(1,30).' '.$month[rand(0,11)].' '.rand(date('Y'),2060).' г.';
		if ($random > 80){
			$when = 'Считаю что '.$answer.' совсем скоро';
		}
		if ($random < 20){
			$when = 'Считаю что '.$answer.' никогда';
		}
		apisay($when, $kb_msg[3], $kb_msg[1]);
		unset($text,$answer,$month,$random,$when);
};

register_command(array(
		'cmds' => $plug_cmds,
		'cmdfunc' => $plug_mainfunc,
		'smalldescription' => $plug_smalldescription
));