<?php
$plug_cmds = array(
	'сайт'
);
$plug_smalldescription = 'Скриншот сайта';
$plug_mainfunc =
function ($kb_msg, $kb_cmds)
	{
	$text = $kb_msg[5];
	$answer = explode(' ',$text);
	unset($answer[0]);
	unset($answer[1]);
	$answer = implode(' ',$answer);
	$answer = str_replace(' ','',$answer);
	$pic = file_get_contents('http://mini.s-shot.ru/1024x768/900/jpeg/?'.$answer);
	file_put_contents('./plugins/site/site.jpg',$pic);
	$req = array(
		'v' => '5.68',
		'access_token' => KB_TOKEN,
	);
	$get_params = http_build_query($req);
	$url = json_decode(file_get_contents('https://api.vk.com/method/photos.getMessagesUploadServer?' . $get_params));
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$parameters = array(
		//'file1' => new CURLFile('./plugins/site/site.jpg')
		'file1' => new CURLFile('./plugins/site/disable.jpg')
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
		'album_id' => '231057848',
		'server' => $res->server,
		'photo' => $res->photo,
		'hash' => $res->hash,
		'access_token' => KB_TOKEN,
	);
	$get_params = http_build_query($req);
	$res = json_decode(file_get_contents('https://api.vk.com/method/photos.saveMessagesPhoto?' . $get_params));
	$req = array(
		'v' => '5.68',
		'peer_id' => $kb_msg[3],
		'message' => 'Временно не доступно',
		'attachment' => 'photo' . $res->response[0]->owner_id . '_' . $res->response[0]->id,
		'access_token' => KB_TOKEN,
	);
	$get_params = http_build_query($req);
	$res = json_decode(file_get_contents('https://api.vk.com/method/messages.send?' . $get_params));
	};
register_command(array(
	'cmds' => $plug_cmds,
	'cmdfunc' => $plug_mainfunc,
	'smalldescription' => $plug_smalldescription
));

