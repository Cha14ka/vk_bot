<?php
$plug_cmds = array('видео');
$plug_smalldescription = 'Выводит видео по запросу';
$plug_mainfunc = function ($kb_msg, $kb_cmds){
		$info = '';
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
		$req = array(
			'v' => '5.68',
			'q' => $vid,
			'count' => 10,
			'adult' => 0,
			'forward_messages' => $kb_msg[1],
			'access_token' => KB_TOKEN
		);
		$list = json_decode(file_get_contents('https://api.vk.com/method/video.search?'.http_build_query($req)));
		if ($list->response->count != 0){
			for ($k=0; $k != count($list->response->items); $k++){
				$info .=  'video'.$list->response->items[$k]->owner_id.'_'.$list->response->items[$k]->id.',';
			}
			//print_r($list);
			$req = array(
				'v' => '5.68',
				'peer_id' => $kb_msg[3],
				'access_token' => KB_TOKEN,
				'forward_messages' => $kb_msg[1],
				'message' => 'Видео',
				'attachment' => $info
			);
			file_get_contents('https://api.vk.com/method/messages.send?'.http_build_query($req));
		}else{
			apisay('Видео по запросу "'.$vid.'" не найдены.',$kb_msg[3],$kb_msg[1]);	
		}
		unset($info,$text,$answer,$vid,$req,$list);
};

register_command(array(
		'cmds' => $plug_cmds,
		'cmdfunc' => $plug_mainfunc,
		'smalldescription' => $plug_smalldescription
));