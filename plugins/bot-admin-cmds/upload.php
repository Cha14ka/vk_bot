<?php
$plug_cmds = array('скинь', 'ul');
$plug_smalldescription = 'Отправляет файл указанный в аргументе';
$plug_mainfunc = function ($kb_msg, $kb_cmds){
		if (in_array($kb_msg[6]->from, KB_ADMINS)){
			$answ = explode(' ', $kb_msg[5]);
				 			$req = array(
		'v' => '5.68',
		'peer_id' => $kb_msg[1],
		'access_token' => KB_TOKEN
	);
	$get_params = http_build_query($req);
$url = json_decode(file_get_contents('https://api.vk.com/method/docs.getMessagesUploadServer?'. $get_params));
			$ch = curl_init();curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$parameters = array(
			'file' => new CURLFile($answ[2])
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
				'title' => $answ[2],
				'file' => $res->file,
				'access_token' => KB_TOKEN
			);
			$get_params = http_build_query($req);
			$res = json_decode(file_get_contents('https://api.vk.com/method/docs.save?'. $get_params));
			//apisay(print_r($res,true),$kb_msg[3],$kb_msg[1]);
			$req = array(
				'v' => '5.68',
				'peer_id' => $kb_msg[3],
				'attachment' => 'doc'.$res->response[0]->owner_id.'_'.$res->response[0]->id,
				'access_token' => KB_TOKEN,
			);
			$get_params = http_build_query($req);
			$res = json_decode(file_get_contents('https://api.vk.com/method/messages.send?'. $get_params));
				}
				else{
					apisay('А ну убери свои ручонки от админ команд, кусок дерьма',$kb_msg[3], $kb_msg[1]);	
				}
	};

register_command(array(
	'cmds' => $plug_cmds,
	'cmdfunc' => $plug_mainfunc,
	'smalldescription' => $plug_smalldescription,
	'adminonly' => '1'
));