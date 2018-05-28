<?php
$plug_cmds = array('date', 'time', 'время', 'дата', 'подскажи_время');
$plug_smalldescription = 'Вывод текущего времени';
$plug_mainfunc = function ($kb_msg, $kb_cmds){
		$date = date('d.m.Y');
		$time = date(':i:s');
		$tfix = date('H');
		apisay('Сейчас '.$tfix.$time.' '.$date.'г.', $kb_msg[3], $kb_msg[1]);
};

register_command(array(
		'cmds' => $plug_cmds,
		'cmdfunc' => $plug_mainfunc,
		'smalldescription' => $plug_smalldescription
));