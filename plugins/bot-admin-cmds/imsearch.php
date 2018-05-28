<?php
$plug_cmds = array('лспоиск');
$plug_smalldescription = 'Находит сообщения по запросу';
$plug_mainfunc = function ($kb_msg, $kb_cmds){
		$info='';
		$text = $kb_msg[5];
		$answer = explode(' ',$text);
		unset($answer[0]);
		unset($answer[1]);
		$vid = implode(' ',$answer);
		$req = array(
			'v' => '5.68',
			'q' => $vid,
			'count' => 100,
			'access_token' => KB_TOKEN
		);
		$list = json_decode(file_get_contents('https://api.vk.com/method/messages.search?'.http_build_query($req)));
		if ($list->response->count != 0){
		for ($k=0; $k != count($list->response->items); $k++){
			$info .= $list->response->items[$k]->id.',';
		}
	
		$req = array(
			'v' => '5.68',
			'peer_id' => $kb_msg[3],
			'access_token' => KB_TOKEN,
			'forward_messages' => $info,
			'message' => 'Сообщения по запросу "'.$vid.'"'
		);
		file_get_contents('https://api.vk.com/method/messages.send?'.http_build_query($req));
		}else{
		apisay('Сообщения по запросу "'.$vid.'" не найдены.',$kb_msg[3],$kb_msg[1]);	
		}
		unset($text,$answer,$vid,$req,$list);
};

register_command(array(
	'cmds' => $plug_cmds,
	'cmdfunc' => $plug_mainfunc,
	'smalldescription' => $plug_smalldescription,
	'adminonly' => '1'
));