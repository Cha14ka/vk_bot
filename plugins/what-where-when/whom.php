<?php
$plug_cmds = array('кого');
$plug_smalldescription = '[Временно в ремонте]';
$plug_mainfunc = function ($kb_msg, $kb_cmds){
		$text = $kb_msg[5];
		$answer = explode(' ',$text);
		unset($answer[0]);
		unset($answer[1]);
		unset($answer[2]);//name
		$answer = implode(' ',$answer);
		if ($kb_msg[3] < 2000000000){
			apisay('В личной переписке это не работает. Лишь в конфе',$toho,$kb_msg[1]);
		}else{
			$resapi = $kb_msg[3]-2000000000;
		
			$req = array(
				'v' => '5.68',
				'chat_id' => $resapi,
				'access_token' => KB_TOKEN
			);
			$list = json_decode(file_get_contents('https://api.vk.com/method/messages.getChatUsers?'.http_build_query($req)));
			//print_r($list);
			$rand = rand(0,count($list->response)-1);
			//echo($list->response[1]);
			$req = array(
				'v' => '5.68',
				'user_ids' => $list->response[$rand],
				'name_case' => 'acc'
			);
			$get_p = http_build_query($req);
			$name = json_decode(file_get_contents('https://api.vk.com/method/users.get?'. $get_p));

			if (rand(0,1)=='0'){
				apisay('Есть вероятность что '.explode(' ', $kb_msg[5])[2].' '.$answer.' '.$name->response[0]->first_name.' '.$name->response[0]->last_name,$kb_msg[3]);
			}else{
				apisay('Я уверена '.explode(' ', $kb_msg[5])[2].' у нас '.$answer.' '.$name->response[0]->first_name.' '.$name->response[0]->last_name,$kb_msg[3]);
			}
		}
		unset($text,$answer,$resapi,$req,$list,$rand,$get_p,$name);
};

register_command(array(
		'cmds' => $plug_cmds,
		'cmdfunc' => $plug_mainfunc,
		'smalldescription' => $plug_smalldescription,
		'adminonly' => '1',
		'enabled' => '0'
));