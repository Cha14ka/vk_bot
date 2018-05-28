<?php
 $plug_cmds = array('вики','wiki');
$plug_smalldescription = 'Поиск в википедии';
$plug_mainfunc = function ($kb_msg){
	$toho = $kb_msg[3];
	$torep = $kb_msg[1];
		$answer = explode(' ', $kb_msg[5]);
		unset($answer[0]);
		unset($answer[1]);
		$answer = implode(' ',$answer);
		$wiki = file_get_contents('https://ru.wikipedia.org/w/api.php?action=query&prop=revisions&rvprop=content&format=json&titles='.urlencode($answer));
		apisay('хуй',$toho,$torep);
		print_r(json_decode($wiki));
	};
register_command(array(
	'cmds' => $plug_cmds,
	'cmdfunc' => $plug_mainfunc,
	'smalldescription' => $plug_smalldescription
));
