<?php
 $plug_cmds = array('звонки','зв');
$plug_smalldescription = 'Звонки-звоночки хуёчки';
$plug_mainfunc = function ($kb_msg){
		apisay('1) 9:00-9:45 || 9:50-10:35 
2) 10:45-11:30||12:00-12:45 Обед 11:30-12:00 
3) 12:55-13:40||13:45-14:30 
4) 14:40-15:25||15:30-16:15',$kb_msg[3], $kb_msg[1]);
	};
register_command(array(
	'cmds' => $plug_cmds,
	'cmdfunc' => $plug_mainfunc,
	'smalldescription' => $plug_smalldescription
));
