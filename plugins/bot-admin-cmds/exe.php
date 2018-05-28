<?php
$plug_cmds = array('exe');
$plug_smalldescription = 'Выполнить код в execute';
$plug_mainfunc = function ($kb_msg, $kb_cmds){
		if (in_array($kb_msg[6]->from, KB_ADMINS)){
				$answer = explode(' ', $kb_msg[5]);
				unset($answer[0]);
				unset($answer[1]);
				$answer = implode(' ',$answer);
				$req = array(
		'v' => '5.68',
		'access_token' => KB_SETTINGS['token'],
		'code' => $answer
	);
	$get_params = http_build_query($req);
	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n".
"User-Agent: KateMobileAndroid/46-424 (Android 7.1; SDK 24; armeabi-v7a; Nexus 5; en)\r\n",
			'content' => $get_params
		)
	);
	$context  = stream_context_create($opts);
	$code = file_get_contents('https://api.vk.com/method/execute', false, $context);
	apisay(print_r(json_decode($code),true),$kb_msg[3],$kb_msg[1]);
		}else{
						apisay('У вашего профиля нет доступа к системным командам.',$kb_msg[3], $kb_msg[1]);	
					}
				unset($term);
	};

register_command(array(
	'cmds' => $plug_cmds,
	'cmdfunc' => $plug_mainfunc,
	'smalldescription' => $plug_smalldescription,
	'adminonly' => '1'
));