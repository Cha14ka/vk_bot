<?php
$plug_cmds = array('хули');
$plug_smalldescription = ' ';
$plug_mainfunc = function ($kb_msg){
	$toho = $kb_msg[3];
	$torep = $kb_msg[1];
	apisay('во 1) хуле ты мне сделаешь вовторых пошел нахуй втетьих 3)что ты мне сделаешь, я в другом городе за мат извени',$toho,$torep);
};

register_command(array(
		'cmds' => $plug_cmds,
		'cmdfunc' => $plug_mainfunc,
		'smalldescription' => $plug_smalldescription,
		'visible' => 0,
    'enable'=> 0
));
