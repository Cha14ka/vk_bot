<?php
$plug_cmds = array('кек', 'kek');
$plug_smalldescription = 'Отзеркаливаение правой стороны картинки (с ключом "лол" отзеркалит левую сторону)';
$plug_mainfunc = function ($kb_msg){
	
	//-----------------------------------------------------
	
			$req = array(
		'v' => '5.68',
		'message_ids' => $kb_msg[1],
		'access_token' => KB_TOKEN,
	);
	$get_params = http_build_query($req);
	$res = json_decode(file_get_contents('https://api.vk.com/method/messages.getById?'. $get_params));
	if (empty($res->response->items[0]->attachments[0]->photo)){
		apisay('Вы забыли приложить фотографию к своему запросу.',$kb_msg[3],$kb_msg[1]);	
	}
	else{
	$pic_data = $res->response->items[0]->attachments[0]->photo;
	unset($pic_data->width);
	unset($pic_data->height);
	unset($pic_data->text);
	unset($pic_data->date);
	unset($pic_data->access_key);
	$obj_array = get_object_vars($pic_data);
	$keys1 = array_keys($obj_array);
	$img_url = $obj_array[$keys1[count($obj_array)-1]];
		
	$parsed = explode(' ', $kb_msg[5]);
	if(empty($parsed[2]))
		$parsed[2] = '';
	if ( strtolower_utf8($parsed[2]) !='лол'){
		keklol($img_url);
	}else{
		keklol2($img_url);
	}
		
	$req = array(
		'v' => '5.68',
		'access_token' => KB_TOKEN,
	);
	$get_params = http_build_query($req);
$url = json_decode(file_get_contents('https://api.vk.com/method/photos.getMessagesUploadServer?'. $get_params));
$ch = curl_init();curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$parameters = [
    'file1' => new CURLFile('./plugins/kek/test.jpg') 
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
	$img_data = file_get_contents('./plugins/kek/test.jpg');
	file_put_contents('./plugins/kek/old_pics/'.rand(0,99999999).'.jpg',$img_data);
	}unset($text,$img_size,$img_x,$img_y,$req,$get_params,$res,$pic_data,$obj_array,$img_url,$url,$ch,$parameters,$curl_result,$img_data);
};

register_command(array(
		'cmds' => $plug_cmds,
		'cmdfunc' => $plug_mainfunc,
		'smalldescription' => $plug_smalldescription
));

function keklol($url){
	$imginfo = getimagesize($url);
	$img1 = imagecreatefromjpeg($url);
	$im = $img1;
	$w = imagesx($img1); 
	$h = imagesy($img1); 
	$img2 = imagecreatetruecolor( $w, $h ); 

	imagelayereffect( $img2, IMG_EFFECT_REPLACE); 
	for( $i = 0; $i < $w; $i++ ) {  
		imagecopy( $img2, $img1, $i, 0, $w - $i - 1, 0, 1, $h );  
	}
	imagecopy($im,$img2,0,0,0,0,$imginfo[0]/2, $imginfo[1]);
	imagejpeg($im,'./plugins/kek/test.jpg');
	imagedestroy($im);
	imagedestroy($img2);
	unset($back,$vv,$imginfo,$im);
}
function keklol2($url){
	$imginfo = getimagesize($url);
	$img1 = imagecreatefromjpeg($url);
	$im = $img1;
	$w = imagesx($img1); 
	$h = imagesy($img1); 
	$img2 = imagecreatetruecolor( $w, $h ); 

	imagelayereffect( $img2, IMG_EFFECT_REPLACE); 
	for( $i = 0; $i < $w; $i++ ) {  
		imagecopy( $img2, $img1, $i, 0, $w - $i - 1, 0, 1, $h );  
	}
	imagecopy($img2,$im,0,0,0,0,$imginfo[0]/2, $imginfo[1]);
	imagejpeg($img2,'./plugins/kek/test.jpg');
	imagedestroy($im);
	imagedestroy($img2);
	unset($back,$vv,$imginfo,$im);
}