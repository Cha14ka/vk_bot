<?php
$plug_cmds = array('скажи', 'озвучь', 'зачитай');
$plug_smalldescription = 'Зачитывает голосом ваш текст переданный в аргументе';
$plug_mainfunc = function ($kb_msg, $kb_cmds){
			$text = explode(' ', $kb_msg[5]);
			unset($text[0]);
			unset($text[1]);
			$text = str_replace('<br>','', implode(' ', $text));
			$ya_key = 'c8694d7c-afff-48c1-9701-b10def466526';
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, 'https://tts.voicetech.yandex.net/generate?&format=mp3&quality=hi&emotion=evil&key='.$ya_key.'&text='.urlencode($text));
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			$out = curl_exec($curl);
			curl_close($curl);
			file_put_contents('./plugins/voicesynth/tts.mp3',$out);
			$req = array(
		'v' => '5.68',
		'type' => 'audio_message',
		'peer_id' => $kb_msg[3],
		'access_token' => KB_TOKEN
	);
	$get_params = http_build_query($req);
$url = json_decode(file_get_contents('https://api.vk.com/method/docs.getMessagesUploadServer?'. $get_params));
			$ch = curl_init();curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$parameters = array(
			'file' => new CURLFile('./plugins/voicesynth/tts.mp3')
		);
		curl_setopt($ch, CURLOPT_URL, $url->response->upload_url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$curl_result = curl_exec($ch);
		curl_close($ch);
		$res = json_decode($curl_result);
			$req = array(
				'v' => '5.68',
				'title' => 'vox_mes',
				'file' => $res->file,
				'access_token' => KB_TOKEN
			);
			$get_params = http_build_query($req);
			$res = json_decode(file_get_contents('https://api.vk.com/method/docs.save?'. $get_params));
			$req = array(
				'v' => '5.68',
				'peer_id' => $kb_msg[3],
				'attachment' => 'doc'.$res->response[0]->owner_id.'_'.$res->response[0]->id,
				'access_token' => KB_TOKEN,
			);
			$get_params = http_build_query($req);
			$res = json_decode(file_get_contents('https://api.vk.com/method/messages.send?'. $get_params));
			unlink('./plugins/voicesynth/tts.mp3');
			//apisayPOST((microtime(true) - $start).' ms',$toho,$torep);
};

register_command(array(
		'cmds' => $plug_cmds,
		'cmdfunc' => $plug_mainfunc,
		'smalldescription' => $plug_smalldescription
));