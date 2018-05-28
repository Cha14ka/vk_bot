<?php
$plug_cmds = array('лот','лотерея');
$plug_smalldescription = 'Лотерея теперь лишь в группе';
$plug_mainfunc = function ($kb_msg){
	$toho = $kb_msg[3];
	$torep = $kb_msg[1];
	apisay('Лотерея больше не поддерживается в этом боте из-за сильных нагрузок<br>Теперь она доступна лишь в сообщениях этого сообщества: vk.com/kbot_official<br>Все ваши деньги уже там.',$toho,$torep);
};

register_command(array(
		'cmds' => $plug_cmds,
		'cmdfunc' => $plug_mainfunc,
		'smalldescription' => $plug_smalldescription,
		'visible' => 0
));
