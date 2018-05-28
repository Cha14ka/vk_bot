<?php
$plug_cmds = array('что','че','чё');
$plug_smalldescription = 'Распознать аудиофайл';
$plug_mainfunc = function ($kb_msg){
	$toho = $kb_msg[3];
	$torep = $kb_msg[1];
	$text = $kb_msg[5];
	$answer = explode(' ',$text);
	unset($answer[0]);
	unset($answer[1]);
	$text = implode(' ',$answer);
	$req = array(
		'v' => '5.68',
		'message_ids' => $torep,
		'access_token' => KB_TOKEN,
	);
	$get_params = http_build_query($req);
	$res = json_decode(file_get_contents('https://api.vk.com/method/messages.getById?'. $get_params));
	file_put_contents('./plugins/speechapi/array.txt',print_r($res,true));
	if (empty($res->response->items[0]->fwd_messages[0]->attachments[0]->doc->url)){
		apisay('Приложите аудиозапись к сообщению',$toho,$torep);
	}
	else{
	$file = $res->response->items[0]->fwd_messages[0]->attachments[0]->doc->url;
	file_put_contents('./plugins/speechapi/generate.ogg',file_get_contents($file));
	if (!function_exists('curl_file_create')) {
		function curl_file_create($filename, $mimetype = '', $postname = '') {
			return "@$filename;filename="
				. ($postname ?: basename($filename))
				. ($mimetype ? ";type=$mimetype" : '');
		}
	}

	$file = './plugins/speechapi/generate.ogg';
	$key = '414187c5-d463-45fb-a83c-d27e57095878';
		$min = 0;$max = 30;$count = 64;
		//-------------
		$result=array();
		if($min>$max) return $result;
		$count=min(max($count,0),$max-$min+1);
		while(count($result)<$count) {
			$value=rand($min,$max-count($result));
			foreach($result as $used) if($used<=$value) $value++; else break;
			$result[]=dechex($value);
			sort($result);
		}
		shuffle($result);
		$uuid = $result;
		//-------------
		$uuid=implode($uuid);    $uuid=substr($uuid,1,32);
		$curl = curl_init();
		$url = 'https://asr.yandex.net/asr_xml?'.http_build_query(array(
			'key'=>$key,
			'uuid' => $uuid,
			'topic' => 'queries',
			'lang'=>'ru-RU'
		));
		curl_setopt($curl, CURLOPT_URL, $url.'&disableAntimat=true');
		$data=file_get_contents(realpath($file));
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: audio/ogg; codecs=opus'));
		//curl_setopt($curl, CURLOPT_VERBOSE, true);
		$response = curl_exec($curl);
		$err = curl_errno($curl);
		curl_close($curl);
		/*if ($err)
			throw new exception("curl err $err");*/
		//apisay($response,$toho,$torep);
		$p = xml_parser_create();
		xml_parse_into_struct($p, $response, $vals, $index);
		xml_parser_free($p);
		//print_r($vals);
		//apisay($response,$toho,$torep);
		if (!empty($vals[1]['value'])){
			$response = $vals[1]['value'];
			apisay('В этом аудио сказано: <br>'.$response,$toho,$torep);
		}else{
			apisay('Яндекс неосиляторы',$toho,$torep);	
		}
	}
};

register_command(array(
		'cmds' => $plug_cmds,
		'cmdfunc' => $plug_mainfunc,
		'smalldescription' => $plug_smalldescription
));