<?php
$plug_cmds = array('фото');
$plug_smalldescription = 'Выводит фото по запросу';
$plug_mainfunc = function ($kb_msg, $kb_cmds){
		$info='';
		$answer = explode(' ', $kb_msg[5]);
				unset($answer[0]);
				unset($answer[1]);
				$vid = implode(' ',$answer);
				$vid = str_replace('ftp','http',$vid);
				$vid = str_replace('&#','',$vid);
				$html = array('&#',';','&','vto','vtope','vkbots','vkbot');
				$vid = str_replace($html,' ',$vid);
				$vid = str_replace('#', '&#35;', $vid);
				$vid = preg_replace('@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@', '', $vid);
	$vid = explode('-o ',$vid);
	$offset = $vid[1];
	$vid = $vid[0];
		$req = array(
			'v' => '5.68',
			'q' => $vid,
			'count' => 100,
			'offset' => (int)$offset,
			'access_token' => KB_TOKEN
		);
		$list = json_decode(file_get_contents('https://api.vk.com/method/photos.search?'.http_build_query($req)));
	//print_r($list);
		if ($list->response->count != 0){
			for ($k=0; $k != count($list->response->items); $k++){
				$info .= 'photo'.$list->response->items[$k]->owner_id.'_'.$list->response->items[$k]->id.',';
			}
			
			$req = array(
				'v' => '5.68',
				'peer_id' => $kb_msg[3],
				'access_token' => KB_TOKEN,
				'forward_messages' => $kb_msg[1],
				'message' => 'Фотки по запросу фото '.$vid,
				'attachment' => $info
			);
			file_get_contents('https://api.vk.com/method/messages.send?'.http_build_query($req));
		}else{
			apisay('Фотки по запросу "'.$vid.'" не найдены.',$kb_msg[3],$kb_msg[1]);	
		}
		unset($text,$answer,$vid,$req,$list);
	};

register_command(array(
		'cmds' => $plug_cmds,
		'cmdfunc' => $plug_mainfunc,
		'smalldescription' => $plug_smalldescription
));