<?php
$plug_cmds = array('дебаг', 'debug', 'отладка', 'db');
$plug_smalldescription = 'Debug command';
$plug_mainfunc = function ($kb_msg, &$kb_cmds){
	switch (explode(' ', $kb_msg[5])[2]) {
		case 'выведи':
			apisay(print_r(${explode(' ', $kb_msg[5])[3]}, true), $kb_msg[3]);
			break;
		case 'set':
			foreach($kb_cmds as $cmd){
				if(in_array(strtolower_utf8(explode(' ', $kb_msg[5])[1]), $cmd[0])){
					$kb_cmds[(integer) explode(' ', $kb_msg[5])[4]] = explode(' ', $kb_msg[5])[5];
				}
			};
			break;
	};
	
};

register_command(array(
	'cmds' => $plug_cmds,
	'cmdfunc' => $plug_mainfunc,
	'smalldescription' => $plug_smalldescription,
	'adminonly' => '1'
));