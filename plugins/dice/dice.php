<?php
$plug_cmds = array('кость', 'кости');
$plug_smalldescription = 'Бросить кости';
$plug_mainfunc = function ($kb_msg){
	echo 3;
	apisay('Кбшечка бросает кость.... И выпадает '.rand(1,6), $kb_msg[3], $kb_msg[1]);	
};

register_command(array(
		'cmds' => $plug_cmds,
		'cmdfunc' => $plug_mainfunc,
		'smalldescription' => $plug_smalldescription
));
