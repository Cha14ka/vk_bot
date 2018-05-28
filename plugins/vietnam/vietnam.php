<?php
$plug_cmds = array('вьетнам');
$plug_smalldescription = 'Накладывает на вашу картинку эффект вьетнамского флешбека';
$plug_mainfunc = function ($kb_msg, $kb_cmds){
		$req = array(
		'v' => '5.68',
		'message_ids' => $kb_msg[1],
		'access_token' => KB_TOKEN,
	);
	$get_params = http_build_query($req);
	$res = json_decode(file_get_contents('https://api.vk.com/method/messages.getById?'. $get_params));
	if (empty($res->response->items[0]->attachments[0]->photo)){
		apisay('Вы забыли приложить фотографию к своему запросу.',$kb_msg[3], $kb_msg[1]);
		return;
	}else{
		$pic_data = $res->response->items[0]->attachments[0]->photo;
	}
	unset($pic_data->width);
	unset($pic_data->height);
	unset($pic_data->text);
	unset($pic_data->date);
	unset($pic_data->access_key);
	$obj_array = get_object_vars($pic_data);
	$keys1 = array_keys($obj_array);
	$img_url = $obj_array[$keys1[count($obj_array)-1]];
	$back = imagecreatefrompng('./plugins/vietnam/vietnam.png');
	$im = imagecreatefromjpeg($img_url);
	imagealphablending($im, true);
	$imginfo = getimagesize($img_url);
	$vv = getimagesize('./plugins/vietnam/vietnam.png');
	imagecopyresampled($im, $back, 0, 0, 0, 0, $imginfo[0], $imginfo[1],$vv[0], $vv[1]);
	imagejpeg($im,'./plugins/vietnam/test.jpg');
	imagedestroy($im);
	unset($back,$vv,$imginfo,$im);
	$req = array(
		'v' => '5.68',
		'access_token' => KB_TOKEN,
	);
	$get_params = http_build_query($req);
$url = json_decode(file_get_contents('https://api.vk.com/method/photos.getMessagesUploadServer?'. $get_params));
$ch = curl_init();curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$parameters = [
    'file1' => new CURLFile('./plugins/vietnam/test.jpg') 
];
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
	$img_data = file_get_contents('./plugins/vietnam/test.jpg');
	file_put_contents('./plugins/vietnam/old_pics/'.rand(0,99999999).'.jpg',$img_data);
	unset($text,$img_size,$img_x,$img_y,$req,$get_params,$res,$pic_data,$obj_array,$img_url,$url,$ch,$parameters,$curl_result,$img_data);
};

register_command(array(
		'cmds' => $plug_cmds,
		'cmdfunc' => $plug_mainfunc,
		'smalldescription' => $plug_smalldescription
));