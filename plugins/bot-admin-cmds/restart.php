<?php
$plug_cmds = array('рестарт');
$plug_smalldescription = 'Перезапуск бота';
$plug_mainfunc = function ($kb_msg, $kb_cmds){
		if (in_array($kb_msg[6]->from, KB_ADMINS)){
					apisay('Начинаю перезапуск системы. Надеюсь я не умру',$kb_msg[3], $kb_msg[1]);
					shell_exec('sh ./plugins/bot-special-cmds/restart.sh '.getmypid().' &'); 
					exit();
				}
				else{
						apisay('У вашего профиля нет доступа к системным командам.',$kb_msg[3], $kb_msg[1]);	
					}
	};

register_command(array(
	'cmds' => $plug_cmds,
	'cmdfunc' => $plug_mainfunc,
	'smalldescription' => $plug_smalldescription,
	'enabled' => '0'
));