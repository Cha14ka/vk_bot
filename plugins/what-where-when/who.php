<?php
$plug_cmds = array('кто');
$plug_smalldescription = 'Пишет кто является свойством из аргумента';
$plug_mainfunc = function ($kb_msg, $kb_cmds){
		$text = $kb_msg[5];
		$answer = explode(' ',$text);
		unset($answer[0]);
		unset($answer[1]);
		$answer = implode(' ',$answer);
		$answer = str_replace('?','',$answer);
		$answer = str_replace(')','',$answer);
		$answer = str_replace(' я ',' ты ',$answer);
		$answer = str_replace(' мне ',' тебе ',$answer);
		//$answer = implode(' ',$answer);
			if ($kb_msg[3] < 2000000000){
			if (rand(0,1)=='0'){
				apisay('Есть вероятность что '.$answer.' - Вы', $kb_msg[3], $kb_msg[1]);
			}else{
				apisay('Я уверена '.$answer.' у нас Вы', $kb_msg[3], $kb_msg[1]);
			}
			
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
			$request = array(
				'v' => '5.68',
				'user_ids' => $list->response[$rand]
			);
			$get_params = http_build_query($request);
			$result = json_decode(file_get_contents('https://api.vk.com/method/users.get?'. $get_params));
			$name = $result;
			if (rand(0,1)=='0'){
				apisay('Есть вероятность что '.$answer.' - '.$name->response[0]->first_name.' '.$name->response[0]->last_name,$kb_msg[3]);
			}else{
				apisay('Я уверена '.$answer.' у нас '.$name->response[0]->first_name.' '.$name->response[0]->last_name,$kb_msg[3]);
			}
				unset($resapi);
		}
		unset($text,$answer,$req,$list,$name,$rand);
};

register_command(array(
		'cmds' => $plug_cmds,
		'cmdfunc' => $plug_mainfunc,
		'smalldescription' => $plug_smalldescription
));
