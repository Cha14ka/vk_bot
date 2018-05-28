<?php
$plug_cmds = array('стоп', 'stop', 'пока', 'bye');
$plug_smalldescription = 'Выключение бота';
$plug_mainfunc = function ($kb_msg, $kb_cmds){
		if (in_array($kb_msg[6]->from, KB_ADMINS)){
			apisay('ня.пока.',$kb_msg[3], $kb_msg[1]);
			print('['.date('H:i:s').'] Получена команда завершения. Выключаюсь.'.PHP_EOL);
			exit();
		} else {
			apisay('У вашего профиля нет доступа к системным командам.',$kb_msg[3], $kb_msg[1]);	
		}
	};

register_command(array(
	'cmds' => $plug_cmds,
	'cmdfunc' => $plug_mainfunc,
	'smalldescription' => $plug_smalldescription,
	'adminonly' => '1'
));