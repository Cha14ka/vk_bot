<?php
$plug_cmds = array('цитата');
$plug_smalldescription = 'Генерирует цитату и пересланного сообщения';
$plug_mainfunc = function ($kb_msg, $kb_cmds){
		quote($kb_msg[1]);
				$req = array(
						'v' => '5.68',
						'access_token' => KB_TOKEN,
					);
					$get_params = http_build_query($req);
				$url = json_decode(file_get_contents('https://api.vk.com/method/photos.getMessagesUploadServer?'. $get_params));
				$ch = curl_init();curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				$parameters = array(
					'file1' => new CURLFile('./plugins/quote/quote.jpg')
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
					$res = json_decode(file_get_contents('https://api.vk.com/method/photos.saveMessagesPhoto?'. $get_params));
					$req = array(
						'v' => '5.68',
						'peer_id' => $kb_msg[3],
						'attachment' => 'photo'.$res->response[0]->owner_id.'_'.$res->response[0]->id,
						'access_token' => KB_TOKEN,
					);
					$get_params = http_build_query($req);
					$res = json_decode(file_get_contents('https://api.vk.com/method/messages.send?'. $get_params));
	};

register_command(array(
		'cmds' => $plug_cmds,
		'cmdfunc' => $plug_mainfunc,
		'smalldescription' => $plug_smalldescription
));

 function quote($text){
	$request = array(
		'v' => '5.68',
		'message_ids' => $text,
		'access_token' => KB_TOKEN
	);
	$get_params = http_build_query($request);
	$resnew = json_decode(file_get_contents('https://api.vk.com/method/messages.getById?'. $get_params));
	$id = $resnew->response->items[0]->fwd_messages[0]->user_id;
	$request = array(
		'v' => '5.68',
		'user_ids' => $id,
		'access_token' => KB_TOKEN,
		'fields' => 'photo_max'
	);
	$get_params = http_build_query($request);
	$result = json_decode(file_get_contents('https://api.vk.com/method/users.get?'. $get_params));
	$text = '';
	for ($k=0; $k != count($resnew->response->items[0]->fwd_messages);$k++){
		$text .= $resnew->response->items[0]->fwd_messages[$k]->body.PHP_EOL;
	}
	$url = $result->response[0]->photo_max;
	if ( stristr($url,'.png') ){
		$url = 'https://pp.userapi.com/c841121/v841121720/387fc/6_jOR0p0L58.jpg';
	}
	$name = $result->response[0]->first_name.' '.$result->response[0]->last_name;
	$font = './plugins/quote/arial.ttf';
	$font_size = 28;
	$ret = wordwrap($text,80,PHP_EOL);
	$y = (30*(count(explode(PHP_EOL,$ret))))+50;
	//echo(count(explode(PHP_EOL,$ret)));
	$im = imagecreatetruecolor(600, $y);
	//echo('||'.$y);
	$avatar = imagecreatefromjpeg($url);
	$imginfo = getimagesize($url);
	$gray = imagecolorallocate($im,211, 211, 211);
	$black = imagecolorallocate($im, 0, 0, 0);
	$white = imagecolorallocate($im,255, 255, 255);
	imagefilledrectangle($im,0,0,600,$y,$white);
	imagefilledrectangle($im,0,0,600,50,$gray);
	imagecopyresampled($im, $avatar, 0, 0, 0, 0, 50, 50,$imginfo[0], $imginfo[1]);
	$im = imagecreatetruecolor(600, $y);
	$avatar = imagecreatefromjpeg($url);
	$imginfo = getimagesize($url);
	$gray = imagecolorallocate($im,211, 211, 211);
	$black = imagecolorallocate($im, 0, 0, 0);
	$white = imagecolorallocate($im,255, 255, 255);
	imagefilledrectangle($im,0,0,600,$y,$white);
	imagefilledrectangle($im,0,0,600,50,$gray);
	imagecopyresampled($im, $avatar, 0, 0, 0, 0, 50, 50,$imginfo[0], $imginfo[1]);
	//$font = './impact.ttf';
	imagettftext($im, 28, 0, 58, 40, $black, $font,$name);

	imagettftext($im, 18, 0, 6, 80, $black, $font,$ret);
	imagejpeg($im,'./plugins/quote/quote.jpg');
	imagedestroy($im);
}