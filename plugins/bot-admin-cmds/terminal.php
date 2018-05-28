<?php
$plug_cmds = array('терм', 'term', 'cmd');
$plug_smalldescription = 'Выводит ответ консольной команды переданный в аргументе';
$plug_mainfunc = function ($kb_msg, $kb_cmds){
		if (in_array($kb_msg[6]->from, KB_ADMINS)){
				$answer = explode(' ', $kb_msg[5]);
				unset($answer[0]);
				unset($answer[1]);
				$answer = implode(' ',$answer);
				//$html = array('&quot;');
				$answer = str_replace('&quot;', '"',$answer);
$answer = str_replace('<br>', PHP_EOL,$answer);
				exec($answer,$term);
				//apisay(implode('<br>',$term),$kb_msg[3], $kb_msg[1]);
				$req = array(
					'v' => '5.68',
					'peer_id' => $kb_msg[3],
					'access_token' => KB_SETTINGS['token'],
					'forward_messages' => $kb_msg[1],
					'message' => implode('<br>',$term)
				);
				$get_params = http_build_query($req);
				$opts = array('http' =>
					array(
						'method'  => 'POST',
						'header'  => 'Content-type: application/x-www-form-urlencoded',
						'content' => $get_params
					)
				);
				$context  = stream_context_create($opts);
				file_get_contents('https://api.vk.com/method/messages.send', false, $context);
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