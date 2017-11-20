#!/usr/bin/php
<?php
global $kb_name;
//error_reporting(0);

$kb_name = array('кб','Кб','кБ','КБ','хуй','Хуй','Бот','бот','шлюха','Шлюха','км','Км','кМ','КМ',
'кл','Кл','кЛ','КЛ');
function stats($mode=''){
	$stats = json_decode(file_get_contents('info.json'));
	if ($mode != 'nope'){
		$stats->info->full = $stats->info->full+1;
		$stats->info->tmp = $stats->info->tmp+1;
		file_put_contents('info.json',json_encode($stats));
	}
	return $stats;
}
function readln(){
	return fgets(STDIN);	
}
function checkname($str,$kb_name){
	if (in_array($str,$kb_name))
		return true;
}
function addtalk($str){
	//print_r($str);
	$json = json_decode(file_get_contents('talk/json.txt'));
	$json = get_object_vars($json);
	$json[$str[0]][]=$str[1];
	file_put_contents('talk/json.txt',json_encode($json));
	return true;
}
function usrname($id){
	$request = array(
		'v' => '5.68',
		'user_ids' => $id
	);
	$get_params = http_build_query($request);
	$result = json_decode(file_get_contents('https://api.vk.com/method/users.get?'. $get_params));
	return $result;
	unset($request,$get_params);
}
function filename($name){
	$name = explode('.',$name);
	return $name;
}
function apisayPOST($text,$id,$fm=null){
	$token = file_get_contents('./token.txt');
	$request = array(
		'v' => '5.68',
		'peer_id' => $id,
		'access_token' => $token,
		'message' => $text
	);
	$get_params = http_build_query($request);
	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => 'Content-type: application/x-www-form-urlencoded',
			'content' => $get_params
		)
	);
	$context  = stream_context_create($opts);
	file_get_contents('https://api.vk.com/method/messages.send', false, $context);
	return true;
	unset($opts,$context,$get_params,$request);
}
function apisay($str,$toho,$fm){
	sendmsg($str,$toho,$fm);	
	return true;
}
function sendmsg($text,$id,$fm){
	$token = file_get_contents('./token.txt');
	$request = array(
		'v' => '5.68',
		'peer_id' => $id,
		'access_token' => $token,
		'message' => $text
	);
	$get_params = http_build_query($request);
	$result = json_decode(file_get_contents('https://api.vk.com/method/messages.send?'. $get_params));
	return $result;
	unset($request,$get_params);
}
function sendmsgOLD($text,$id){
	$token = file_get_contents('./token.txt');
	$request = array(
		'v' => '5.68',
		'peer_id' => $id,
		'access_token' => $token,
		'message' => $text
	);
	$get_params = http_build_query($request);
	$result = json_decode(file_get_contents('https://api.vk.com/method/messages.send?'. $get_params));
	return $result;	
	unset($request,$get_params);
}
 function filecheck($filename) {
	$tmpf = explode(".", $filename);
    return array_pop(explode(".", $filename));
	 unset($tmpf);
  }
 function quote($text){
	$token = file_get_contents('./token.txt');
	$request = array(
		'v' => '5.68',
		'message_ids' => $text,
		'access_token' => $token
	);
	$get_params = http_build_query($request);
	$resnew = json_decode(file_get_contents('https://api.vk.com/method/messages.getById?'. $get_params));
	$id = $resnew->response->items[0]->fwd_messages[0]->user_id;
	$request = array(
		'v' => '5.68',
		'user_ids' => $id,
		'access_token' => $token,
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
	$font = './arial.ttf';
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
	imagejpeg($im,'quote.jpg');
	imagedestroy($im);
}
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
	imagejpeg($im,'test.jpg');
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
	imagejpeg($img2,'test.jpg');
	imagedestroy($im);
	imagedestroy($img2);
	unset($back,$vv,$imginfo,$im);
}
function vietnam($url){

$back = imagecreatefrompng('./vietnam.png');
$im = imagecreatefromjpeg($url);
imagealphablending($im, true);
$imginfo = getimagesize($url);
$vv = getimagesize('./vietnam.png');
imagecopyresampled($im, $back, 0, 0, 0, 0, $imginfo[0], $imginfo[1],$vv[0], $vv[1]);
imagejpeg($im,'test.jpg');
imagedestroy($im);
	unset($back,$vv,$imginfo,$im);
}
if (!file_exists('./token.txt')){
	echo('Данные о пользователе не найдены. Пожалуйста создайте файл token.txt с вашим токеном в папке с ботом.'.PHP_EOL);
	exit();
}
else{
	$token = file_get_contents('./token.txt');
}
function getLS($token){
	$request = array(
		'v' => '5.68',
		'lp_version' => '2',
		'access_token' => $token
	);
$get_params = http_build_query($request);
$result = json_decode(file_get_contents('https://api.vk.com/method/messages.getLongPollServer?'. $get_params));
$ls[] = $result->response->key;
$ls[] = $result->response->server;
$ls[] = $result->response->ts;
$url = 'https://'.$ls[1].'?act=a_check&key='.$ls[0].'&ts='.$ls[2].'&wait=25&mode=2&version=2';
return $url;
unset($request,$get_params,$ls);
}
$serverurl = getLS($token);
$newLS = time()+30;
$check[]='';
$starttime = time();
for(;;){
	if (time() >= $newLS){
		$serverurl = getLS($token);
		$time = date('H:i:s');
		echo('['.$time.'] Сервер успешно обновлён'.PHP_EOL);
		$newLS = time()+30;
	}
	$result = json_decode(file_get_contents($serverurl));
	for($i=0; $i != count($result->updates)+1;$i++){
		if (!empty($result->updates[$i][5])){
			if (empty($result->updates[$i][6]->from)){
				$result->updates[$i][6]->from = '';
			}
			$id = $result->updates[$i][6]->from;
			$blacklist = array('455111697','361510476','314172008','363874359','374413841','450397225');
			if (!in_array($id,$blacklist) and !in_array($result->updates[$i][1],$check)){
						$text = $result->updates[$i][5];
						$answ = explode(' ',$text);
						if (empty($answ[1])){
							$answ[1]='';	
						}
					$toho = $result->updates[$i][3];
					$torep = $result->updates[$i][1];
					$kb_text = $result->updates[$i][5];
					$kb_text = explode(' ',$kb_text);
					$kb_alt = $kb_text;
					unset($kb_alt[0]);
					$kb_alt = implode(' ',$kb_alt);
					unset($kb_text[0]);
					unset($kb_text[1]);
					$kb_text = implode(' ',$kb_text);
					$blockcmd = array('цп','ЦП','Цп','цП','Детское порно','Десткое Порно','детское порно');
					$kb_text=str_replace($blockcmd,'Роскомнадзор-тян',$kb_text);
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
					if (in_array($answ[0],$kb_name)){
						stats();
					}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
					$kb_none = array('инфа','скажи','кто','посчитай','помощь',
								'date','видео','доки','живи','крик','Крик','вьетнам','time','гиф','vox','когда','ты','вероятность','gif','вц','вцитатник',
								'цтоп','цлайк','вцт','стата','статистика','статы','бура','r34','34','р34','кого','статус','цитата','вгц','преакт','трейс',
								'кость','кости','гцтоп','аудио','музыка','диалоги','ттс','повтори','лс','расп','рестарт','время',
								'терминал','lp','маста','проверка','сервера','ргб','rgb','цвет','подробнее','скачать','скинь','терм',
								'версия','скачай','звонки','стик','кек','двач','f','юз'
							   );
					if ($answ[0]=='лимцова' or $answ[0]=='Лимцова' or $answ[0]=='ЛИМЦОВА'){
						$randtext = array(
							1 => 'Я тут. Вы что то хотели мой господин?',
							2 => 'Со мной всё впорядке. Не волнуйтесь.',
							3 => 'Все настройки в норме. Отклонений не найдено.',
							4 => 'Да, я Карина Матоева. А что?'
							);
						apisay($randtext[rand(1,count($randtext))],$toho,$torep);
						unset($randtext);
					}
					if (in_array($answ[0],$kb_name) and !in_array($answ[1],$kb_none)){
						$randtext = array(
							1 => 'Данная функция не была найдена в моей инструкции.',
							2 => 'Возможно мой создатель ещё не сделал эту команду.',
							3 => 'Мы все надеемся что эта команда скоро появится.',
							4 => 'А ты точно уверен в своих желаниях?',
							5 => 'Скорее всего ты хотел написать что я няша.',
							6 => 'Вопряки всем стереотипам, у Type KB очень маленькая база данных для ответов.',
							7 => 'Попробуй написать без ошибок. Лучше всего загляни в помощь.',
							8 => 'Ой, а такой команды нет. Приношу свои извинения.',
							9 => 'Ошибка stop 0x00000000001, команда не найдена!',
							10 => 'KERNEL PANIC!!!'
							);
						apisayPOST($randtext[rand(1,count($randtext))],$toho,$torep);
						unset($randtext);
					}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
					if (in_array($answ[0],$kb_name) and ($answ[1] == 'инфа' or $answ[1]=='вероятность')){
						$text = $result->updates[$i][5];
						$answer = explode(' ',$text);
						$answer = str_replace('?','',$answer);
						$answer = str_replace(')','',$answer);
						unset($answer[0]);
						unset($answer[1]);
						$answer = implode(' ',$answer);
						sendmsg('Вероятность того что '.$answer.' равна '.rand(0,146).'%',$result->updates[$i][3],$result->updates[$i][1]);
						echo('Упоминание кб в '.$result->updates[$i][3].PHP_EOL);
						unset($text,$answer);
					}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
					if (in_array($answ[0],$kb_name) and ($answ[1]=='кости' or $answ[1]=='кость')){
						apisay('Кбшечка бросает кость.... И выпадает '.rand(1,6),$toho,$torep);	
					}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
				
					if (in_array($answ[0],$kb_name) and $answ[1] == 'когда'){
						$text = $result->updates[$i][5];
						$answer = explode(' ',$text);
						unset($answer[0]);
						unset($answer[1]);
						$answer = implode(' ',$answer);
						$answer = str_replace('?','',$answer);
						$answer = str_replace(')','',$answer);
						$answer = str_replace('меня','тебя',$answer);
						$month = array('января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
						$random = rand(0,100);
						$when = 'Считаю что '.$answer.' примерно '.rand(1,30).' '.$month[rand(0,count($month))].' '.rand(2017,2060).' г.';
						if ($random > 80){
							$when = 'Считаю что '.$answer.' совсем скоро';
						}
						if ($random < 20){
							$when = 'Считаю что '.$answer.' никогда';
						}
						apisay($when,$toho,$torep);
						echo('Упоминание кб в '.$result->updates[$i][3].PHP_EOL);
						unset($text,$answer,$month,$random,$when);
					}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
					if (in_array($answ[0],$kb_name) and $answ[1] == 'повтори'){
						$text = $result->updates[$i][5];
						$answer = explode(' ',$text);
						unset($answer[0]);
						unset($answer[1]);
						$answer = implode(' ',$answer);
						sendmsgOLD($answer,$result->updates[$i][3]);	
						//apisay('Команда отключена',$toho, $torep);
						echo('Упоминание кб в '.$result->updates[$i][3].PHP_EOL);
						unset($text,$answer);
					}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
					if (in_array($answ[0],$kb_name) and $answ[1] == 'кто'){
						$text = $result->updates[$i][5];
						$answer = explode(' ',$text);
						unset($answer[0]);
						unset($answer[1]);
						$answer = implode(' ',$answer);
						$answer = str_replace('?','',$answer);
						$answer = str_replace(')','',$answer);
						if ($result->updates[$i][3] < 2000000000){
							apisay('В личной переписке это не работает. Лишь в конфе',$toho,$result->updates[$i][1]);
							
						}else{
							$resapi = $result->updates[$i][3]-2000000000;
						
						$req = array(
							'v' => '5.68',
							'chat_id' => $resapi,
							'access_token' => $token
						);
						$list = json_decode(file_get_contents('https://api.vk.com/method/messages.getChatUsers?'.http_build_query($req)));
						//print_r($list);
						$rand = rand(0,count($list->response)-1);
						//echo($list->response[1]);
						$name = usrname($list->response[$rand]);
						if (rand(0,1)=='0'){
							sendmsgOLD('Есть вероятность что '.$answer.' - '.$name->response[0]->first_name.' '.$name->response[0]->last_name,$toho);
						}else{
							sendmsgOLD('Я уверена '.$answer.' у нас '.$name->response[0]->first_name.' '.$name->response[0]->last_name,$toho);
						}
						echo('Упоминание кб в '.$result->updates[$i][3].PHP_EOL);
							unset($resapi);
					}
						unset($text,$answer,$req,$list,$name,$rand);
					}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
				if (in_array($answ[0],$kb_name) and ($answ[1] == 'помощь' or $answ[1] == 'команды' or $answ[1] == 'help')){
						$text = $result->updates[$i][5];
						$answer = explode(' ',$text);
						unset($answer[0]);
						unset($answer[1]);	
						$answer = implode(' ',$answer);
						$list = array(
							'---------------------------------------------------------------------',
							$kb_name[0].' помощь - список команд',
							$kb_name[0].' инфа - вероятность написанного текста',
							$kb_name[0].' повтори - написать фразу от лица бота',
							$kb_name[0].' скажи - сказать фразу голосом бота',
							$kb_name[0].' видео "название" - вывод 10 видео по данному поиску',
							$kb_name[0].' доки "название" - вывод 10 документов по данному поиску',
							$kb_name[0].' гиф "название" - выводит гифки по данному поиску',
							$kb_name[0].' кто XXXXXX - рандомно выбирает участника',
							$kb_name[0].' date - запрос вывода даты',
							$kb_name[0].' time - запрос вывода времени',
							$kb_name[0].' вьетнам "приклейтед пикчи вк" - Создание мема про вьетнам',
							$kb_name[0].' когда - предсказывание даты написанного текста',
							$kb_name[0].' кого [имя] [действие] - Что хочет наш объект в имени сделать с участником беседы',
							$kb_name[0].' 34 [тег] - Поиск Rule34 изображений по указанному тегу',
							$kb_name[0].' кек — зеркалит картинку',
							$kb_name[0].' кек лол — зеркалит в другую сторону',
							$kb_name[0].' двач "доска" — рандомный тред с указанной доски на дваче',
							'---------------------------------------------------------------------'
						);
					apisayPOST(implode(PHP_EOL,$list),$result->updates[$i][3],$result->updates[$i][1]);
					echo('Упоминание кб в '.$result->updates[$i][3].PHP_EOL);
					unset($text,$answer,$list);
				}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
				if (in_array($answ[0],$kb_name) and $answ[1] == 'date'){
					$date = date('d.m.Y');
					apisay($date.' г.',$toho,$result->updates[$i][1]);	
					unset($date);
				}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
				if (in_array($answ[0],$kb_name) and $answ[1] == 'time'){
					$time = date(':i:s');
					$tfix = date('H')+3;
					apisay($tfix.$time,$toho,$result->updates[$i][1]);	
					unset($time,$tfix);
				}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
				if (in_array($answ[0],$kb_name) and $answ[1] == 'перешли'){
						$req = array(
							'v' => '5.68',
							'peer_id' => $result->updates[$i][3],
							'access_token' => $token,
							'forward_messages' => $answ[2]
						);
						file_get_contents('https://api.vk.com/method/messages.send?'.http_build_query($req));
					unset($req);
				}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
				if (in_array($answ[0],$kb_name) and $answ[1]=='ты' and $answ[2]=='няша'){
					$nya = array(
							'Как мило)','^^)','Это комплимент?','Ты мой милашка.','Аву лайкни еще :)','Нууу, ок)','Спасибо','Будь ты не так далеко, мы были бы вместе.'
							);
					apisay($nya[rand(0,count($nya))],$toho,$torep);
					echo('Упоминание кб в '.$result->updates[$i][3].PHP_EOL);
				}
				if (in_array($answ[0],$kb_name) and $answ[1]=='ты' and $answ[2] != 'няша'){
					apisay('Все аргументы кроме "няша" оскорбляют меня.',$toho,$torep);
					echo('Упоминание кб в '.$result->updates[$i][3].PHP_EOL);	
				unset($nya);
				}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
				if (in_array($answ[0],$kb_name) and $answ[1] == 'видео'){
						$info = '';
						$vid = $kb_text;
						$req = array(
							'v' => '5.68',
							'q' => $vid,
							'count' => 10,
							'adult' => 0,
							'forward_messages' => $result->updates[$i][1],
							'access_token' => $token
						);
						$list = json_decode(file_get_contents('https://api.vk.com/method/video.search?'.http_build_query($req)));
						if ($list->response->count != 0){
						for ($k=0; $k != count($list->response->items); $k++){
							$info .=  'video'.$list->response->items[$k]->owner_id.'_'.$list->response->items[$k]->id.',';
						}
						//print_r($list);
						$req = array(
							'v' => '5.68',
							'peer_id' => $result->updates[$i][3],
							'access_token' => $token,
							'forward_messages' => $result->updates[$i][1],
							'message' => 'Видео',
							'attachment' => $info
						);
						file_get_contents('https://api.vk.com/method/messages.send?'.http_build_query($req));
						}else{
						apisay('Видео по запросу "'.$vid.'" не найдены.',$toho,$torep);	
						}
						echo('Упоминание кб в '.$result->updates[$i][3].PHP_EOL);
					unset($info,$text,$answer,$vid,$req,$list);
					}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
					if (in_array($answ[0],$kb_name) and ($answ[1] == 'гиф' or $answ[1] == 'gif')){
						$info='';
						$vid = $kb_text;
						$req = array(
							'v' => '5.68',
							'q' => $vid,
							'count' => 100,
							'access_token' => $token
						);
						$list = json_decode(file_get_contents('https://api.vk.com/method/docs.search?'.http_build_query($req)));
						if ($list->response->count != 0){
							$fcount = 0;
						for ($k=0; $k != count($list->response->items); $k++){
													if ($fcount == 10){
							break;
						}
							if ($list->response->items[$k]->ext == 'gif'){
							$info .= 'doc'.$list->response->items[$k]->owner_id.'_'.$list->response->items[$k]->id.',';
							$fcount++;
						}
						}
						
						$req = array(
							'v' => '5.68',
							'peer_id' => $result->updates[$i][3],
							'access_token' => $token,
							'forward_messages' => $result->updates[$i][1],
							'message' => 'Гифки по запросу гиф '.$vid,
							'attachment' => $info
						);
						file_get_contents('https://api.vk.com/method/messages.send?'.http_build_query($req));
						}else{
						apisay('Гифки по запросу "'.$vid.'" не найдены.',$toho,$torep);
						}
						echo('Упоминание кб в '.$result->updates[$i][3].PHP_EOL);
						unset($text,$answer,$info,$vid,$req,$list);
					}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
					if (in_array($answ[0],$kb_name) and $answ[1] == 'доки'){
						$info='';
						$vid = $kb_text;
						$req = array(
							'v' => '5.68',
							'q' => $vid,
							'count' => 10,
							'access_token' => $token
						);
						$list = json_decode(file_get_contents('https://api.vk.com/method/docs.search?'.http_build_query($req)));
						if ($list->response->count != 0){
						for ($k=0; $k != count($list->response->items); $k++){
							$info .= 'doc'.$list->response->items[$k]->owner_id.'_'.$list->response->items[$k]->id.',';
						}
						
						$req = array(
							'v' => '5.68',
							'peer_id' => $result->updates[$i][3],
							'access_token' => $token,
							'forward_messages' => $result->updates[$i][1],
							'message' => 'Документы по запросу доки '.$vid,
							'attachment' => $info
						);
						file_get_contents('https://api.vk.com/method/messages.send?'.http_build_query($req));
						}else{
						apisay('Документы по запросу "'.$vid.'" не найдены.',$toho,$torep);	
						}
						echo('Упоминание кб в '.$result->updates[$i][3].PHP_EOL);
						unset($text,$answer,$vid,$req,$list);
					}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
					if (in_array($answ[0],$kb_name) and $answ[1] == 'лс'){
						$info='';
						$text = $result->updates[$i][5];
						$answer = explode(' ',$text);
						unset($answer[0]);
						unset($answer[1]);
						$vid = implode(' ',$answer);
						$req = array(
							'v' => '5.68',
							'q' => $vid,
							'count' => 100,
							'access_token' => $token
						);
						$list = json_decode(file_get_contents('https://api.vk.com/method/messages.search?'.http_build_query($req)));
						if ($list->response->count != 0){
						for ($k=0; $k != count($list->response->items); $k++){
							$info .= $list->response->items[$k]->id.',';
						}
						
						$req = array(
							'v' => '5.68',
							'peer_id' => $result->updates[$i][3],
							'access_token' => $token,
							'forward_messages' => $info,
							'message' => 'Сообщения по запросу лс '.$vid
						);
						file_get_contents('https://api.vk.com/method/messages.send?'.http_build_query($req));
						}else{
						apisay('Сообщения по запросу "'.$vid.'" не найдены.',$toho,$torep);	
						}
						echo('Упоминание кб в '.$result->updates[$i][3].PHP_EOL);
						unset($text,$answer,$vid,$req,$list);
					}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
if (in_array($answ[0],$kb_name) and $answ[1] == 'вьетнам'){
	$req = array(
		'v' => '5.68',
		'message_ids' => $torep,
		'access_token' => $token,
	);
	$get_params = http_build_query($req);
	$res = json_decode(file_get_contents('https://api.vk.com/method/messages.getById?'. $get_params));
	if (empty($res->response->items[0]->attachments[0]->photo)){
		apisay('Вы забыли приложить фотографию к своему запросу.',$toho,$torep);	
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
	vietnam($img_url);
	$req = array(
		'v' => '5.68',
		'access_token' => $token,
	);
	$get_params = http_build_query($req);
$url = json_decode(file_get_contents('https://api.vk.com/method/photos.getMessagesUploadServer?'. $get_params));
$ch = curl_init();curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$parameters = [
    'file1' => new CURLFile('test.jpg') 
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
		'access_token' => $token,
	);
	$get_params = http_build_query($req);
	$res = json_decode(file_get_contents('https://api.vk.com/method/photos.saveMessagesPhoto?'. $get_params));
	$req = array(
		'v' => '5.68',
		'peer_id' => $result->updates[$i][3],
		'attachment' => 'photo'.$res->response[0]->owner_id.'_'.$res->response[0]->id,
		'access_token' => $token,
	);
	$get_params = http_build_query($req);
	$res = json_decode(file_get_contents('https://api.vk.com/method/messages.send?'. $get_params));
	$img_data = file_get_contents('test.jpg');
	file_put_contents('./old_pics/'.rand(0,99999999).'.jpg',$img_data);
						echo('Упоминание кб инфа в '.$result->updates[$i][3].PHP_EOL);
	}unset($text,$img_size,$img_x,$img_y,$req,$get_params,$res,$pic_data,$obj_array,$img_url,$url,$ch,$parameters,$curl_result,$img_data);
}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
					if (in_array($answ[0],$kb_name) and $answ[1] == 'кого' ){
						$text = $result->updates[$i][5];
						$answer = explode(' ',$text);
						unset($answer[0]);
						unset($answer[1]);
						unset($answer[2]);//name
						$answer = implode(' ',$answer);
						if ($result->updates[$i][3] < 2000000000){
							apisay('В личной переписке это не работает. Лишь в конфе',$toho,$result->updates[$i][1]);
							
						}else{
							$resapi = $result->updates[$i][3]-2000000000;
						
						$req = array(
							'v' => '5.68',
							'chat_id' => $resapi,
							'access_token' => $token
						);
						$list = json_decode(file_get_contents('https://api.vk.com/method/messages.getChatUsers?'.http_build_query($req)));
						//print_r($list);
						$rand = rand(0,count($list->response)-1);
						//echo($list->response[1]);
						$req = array(
							'v' => '5.68',
							'user_ids' => $list->response[$rand],
							'name_case' => 'acc'
						);
						$get_p = http_build_query($req);
						$name = json_decode(file_get_contents('https://api.vk.com/method/users.get?'. $get_p));
							
						if (rand(0,1)=='0'){
							sendmsgOLD('Есть вероятность что '.$answ[2].' '.$answer.' '.$name->response[0]->first_name.' '.$name->response[0]->last_name,$toho);
						}else{
							sendmsgOLD('Я уверена '.$answ[2].' у нас '.$answer.' '.$name->response[0]->first_name.' '.$name->response[0]->last_name,$toho);
						}
						echo('Упоминание кб в '.$result->updates[$i][3].PHP_EOL);
					}
						unset($text,$answer,$resapi,$req,$list,$rand,$get_p,$name);
					}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
					if (in_array($answ[0],$kb_name) and ($answ[1] == 'r34' or $answ[1] == '34' or $answ[1] == 'р34')){
						$text = $result->updates[$i][5];
						$answer = explode(' ',$text);
						unset($answer[0]);
						unset($answer[1]);
						$answer = implode(' ',$answer);
						$answer = str_replace(' ','_',$answer);
						$data = file_get_contents('http://oj2wyzjtgqxhq6dy.cmle.ru/index.php?page=dapi&s=post&q=index&limit=100&tags='.$answer);
						$p = xml_parser_create();
						xml_parse_into_struct($p, $data, $vals, $index);
						xml_parser_free($p);
						//print_r($vals);
						$zcount=0;
						$mess = 'Дрочевня по тегу '.$answer;
						for(;;){
							$pic = $vals[rand(1,count($vals))]['attributes']['FILE_URL'];
							if (!empty($pic)){
								break;	
							}
							if ($zcount >= 20){
								$mess='Ничего не найдено :(';
								break;
							}
						$zcount++;
						}
						$pic = str_replace('//img.rule34.xxx/','http://oj2wyzjtgqxhq6dy.cmle.ru/',$pic);
						file_put_contents('./rule34/pic.'.filecheck($pic),file_get_contents($pic));
						$req = array(
							'v' => '5.68',
							'access_token' => $token,
						);
						$get_params = http_build_query($req);
						$url = json_decode(file_get_contents('https://api.vk.com/method/photos.getMessagesUploadServer?'. $get_params));
						$ch = curl_init();curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						$pics = scandir('./rule34/');
						unset($pics[0]);
						unset($pics[1]);
						$parameters = array(
							'file1' => new CURLFile('./rule34/'.$pics[2])
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
							'access_token' => $token
						);
						$postdata = http_build_query($req);
						$opts = array('http' =>
							array(
								'method'  => 'POST',
								'header'  => 'Content-type: application/x-www-form-urlencoded',
								'content' => $postdata
							)
						);

						$context  = stream_context_create($opts);
						$res = json_decode(file_get_contents('https://api.vk.com/method/photos.saveMessagesPhoto?'. $get_params, false, $context));
						$req = array(
							'v' => '5.68',
							'peer_id' => $result->updates[$i][3],
							'attachment' => 'photo'.$res->response[0]->owner_id.'_'.$res->response[0]->id,
							'message' => $mess,
							'forward_messages' => $torep,
							'access_token' => $token
						);
						$get_params = http_build_query($req);
						$res = json_decode(file_get_contents('https://api.vk.com/method/messages.send?'. $get_params));
						$pics = scandir('./rule34/');
						unset($pics[0]);
						unset($pics[1]);
						unlink('./rule34/'.$pics[2]);
						echo('Упоминание кб в '.$result->updates[$i][3].PHP_EOL);
						unset($text,$answer,$data,$p,$zcount,$mess,$pic,$val,$index,$p,$req,$get_params,$url,$ch,$pics,$parametres,$curl_result,$res,$postdata,$opts,$context);
					}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
				if (in_array($answ[0],$kb_name) and $answ[1]=='статус'){
					$start = microtime(true);
					exec('free -m',$cpu);
					$cpu = explode(' ',$cpu[1]);
					$text = 'Оперативная память: '.$cpu[22].' Мб /'.$cpu[12].' Мб';
					$stats = stats();
					$text = $text.'<br>Вызовов кб за всё время: '.$stats->info->full;
					$text = $text.'<br>Вызовов кб за сегодня: '.$stats->info->tmp;
					apisayPOST($text,$toho,$torep);
					$tdo= microtime(true)-$start;
					echo('Время выполнения: '.$tdo.PHP_EOL);
					unset($cpu);
					unset($data,$p,$timefix,$cpu,$text);
				}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
				if (in_array($answ[0],$kb_name) and $answ[1]=='диалоги'){
					if ($result->updates[$i][6]->from == '354255965'){
					$count = $answ[2];
					$req = array(
							'v' => '5.68',
							'peer_id' => $result->updates[$i][3],
							//'attachment' => 'photo'.$res->response[0]->owner_id.'_'.$res->response[0]->id,
							'message' => $mess,
							'preview_length' => 10,
							'count' => $count,
							'forward_messages' => $torep,
							'access_token' => $token
						);
					$get_params = http_build_query($req);
					$res = json_decode(file_get_contents('https://api.vk.com/method/messages.getDialogs?'. $get_params));
					//print_r($res);
					for($k=0; $k != $count; $k++){
						if ($res->response->items[$k]->message->title == ''){
							$name = usrname($res->response->items[$k]->message->user_id);
							$return .= '['.$k.'] '.$name->response[0]->first_name.' '.$name->response[0]->last_name.': '.$res->response->items[$k]->message->body.'<br>';
						}
						else{
							$return .= '['.$k.'] '.$res->response->items[$k]->message->title.': '.$res->response->items[$k]->message->body.'<br>';
						}
					}
					//var_dump($res);
					apisayPOST($return,$toho,$torep);
					unset($return);}else{
						apisay('А ну убери свои ручонки от админ команд, кусок дерьма',$toho,$torep);	
					}
				}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
		if ((checkname($answ[0],$kb_name) and $answ[1]=='скажи') or $answ[0]=='!ттс'){
			if ($answ[0] != '!ттс'){
				$kb_text = str_replace('<br>','',$kb_text);
			}
			
			if ($answ[0] == '!ттс'){
				$kb_text = str_replace('<br>','',$kb_alt);
			}
			$ya_key = 'c8694d7c-afff-48c1-9701-b10def466526';
			file_put_contents('./tts.mp3',file_get_contents('https://tts.voicetech.yandex.net/generate?&format=mp3&quality=hi&emotion=evil&key='.$ya_key.'&text='.urlencode($kb_text)));
			$req = array(
		'v' => '5.68',
		'type' => 'audio_message',
		'peer_id' => $torep,
		'access_token' => $token
	);
	$get_params = http_build_query($req);
$url = json_decode(file_get_contents('https://api.vk.com/method/docs.getMessagesUploadServer?'. $get_params));
			$ch = curl_init();curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$parameters = array(
			'file' => new CURLFile('tts.mp3')
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
				'access_token' => $token
			);
			$get_params = http_build_query($req);
			$res = json_decode(file_get_contents('https://api.vk.com/method/docs.save?'. $get_params));
			$req = array(
				'v' => '5.68',
				'peer_id' => $result->updates[$i][3],
				'attachment' => 'doc'.$res->response[0]->owner_id.'_'.$res->response[0]->id,
				'access_token' => $token,
			);
			$get_params = http_build_query($req);
			$res = json_decode(file_get_contents('https://api.vk.com/method/messages.send?'. $get_params));
			unlink('tts.mp3');
			//apisayPOST((microtime(true) - $start).' ms',$toho,$torep);
								echo('Упоминание кб инфа в '.$result->updates[$i][3].PHP_EOL);
		}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
			if (checkname($answ[0],$kb_name) and $answ[1]=='расп'){
				if($answ[2]=='1'){
					apisayPOST('Понедельник:<br><br>Математика(713)<br>Биология(608)<br>Обществознание(715)',$toho,$torep);
				}
				if($answ[2]=='2'){
					apisayPOST('Вторник:<br><br>Физика(601)/Индивид.проект(514)<br>Литература(710)<br>Физ-ра(С/з)<br>Математика(713)',$toho,$torep);
				}
				if($answ[2]=='3'){
					apisayPOST('Среда:<br><br>Информатика(520)/нет пары<br>Физика(601)<br>Математика(713)',$toho,$torep);
				}
				if($answ[2]=='4'){
					apisayPOST('Четверг:<br><br>История(618)<br>Химия(601)<br>Русский Язык(710)',$toho,$torep);
				}
				if($answ[2]=='5'){
					apisayPOST('Пятница:<br><br>История(618)/Литература(710)<br>ОБЖ(715)<br>Иностранный яз(714/515)<br>нет пары/Иностранный яз(714/515)',$toho,$torep);
				}
				if($answ[2]=='6'){
					apisayPOST('Суббота:<br><br>Физ-ра(С/з)/Информатика(606)<br>География(521)',$toho,$torep);
				}
				if(empty($answ[2]))
					apisayPOST('Вы забыли указать нужный параметр',$toho,$torep);
			}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
			if (checkname($answ[0],$kb_name) and $answ[1]=='рестарт'){
				if ($result->updates[$i][6]->from == '354255965'){
					apisay('Начинаю перезапуск системы. Надеюсь я не умру',$toho,$torep);
					system('sh ./restart.sh');
					exit();
				}
				else{
						apisay('У вашего профиля нет доступа к системным командам.',$toho,$torep);	
					}
			}
			if (checkname($answ[0],$kb_name) and $answ[1]=='время'){
				$tmptime = time()-$starttime;
				apisayPOST('Время с момента включения: '.$tmptime,$toho,$torep);
			}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
			if (checkname($answ[0],$kb_name) and ($answ[1]=='терм' or $answ[1]=='терминал')){
				if ($result->updates[$i][6]->from == '354255965'){
				exec($kb_text,$term);
				apisayPOST(implode('<br>',$term),$toho,$torep);}
					else{
						apisay('У вашего профиля нет доступа к системным командам.',$toho,$torep);	
					}
				unset($term);
			}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
			if (checkname($answ[0],$kb_name) and $answ[1]=='цитата'){
				quote($result->updates[$i][1]);
				$req = array(
						'v' => '5.68',
						'access_token' => $token,
					);
					$get_params = http_build_query($req);
				$url = json_decode(file_get_contents('https://api.vk.com/method/photos.getMessagesUploadServer?'. $get_params));
				$ch = curl_init();curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				$parameters = array(
					'file1' => new CURLFile('quote.jpg')
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
						'access_token' => $token,
					);
					$get_params = http_build_query($req);
					$res = json_decode(file_get_contents('https://api.vk.com/method/photos.saveMessagesPhoto?'. $get_params));
					$req = array(
						'v' => '5.68',
						'peer_id' => $result->updates[$i][3],
						'attachment' => 'photo'.$res->response[0]->owner_id.'_'.$res->response[0]->id,
						'access_token' => $token,
					);
					$get_params = http_build_query($req);
					$res = json_decode(file_get_contents('https://api.vk.com/method/messages.send?'. $get_params));
			}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
			if (checkname($answ[0],$kb_name) and ($answ[1]=='маста' or $answ[1]=='сервера')){
				$data = file_get_contents('http://194.67.198.141/api/ms_server.php');
				$data = json_decode($data);
				$info = '';
				for($k=0;$k != count($data);$k++){
					if (!empty($data[$k]->info)){
						/*echo '<pre>'; 
						print_r(get_object_vars($data[$k]->info));
						echo '</pre>';*/
						$info .= 'IP: '.$data[$k]->ip.':'.$data[$k]->port.'<br>';
						$keys = array_keys(get_object_vars($data[$k]->info));
						$obj = get_object_vars($data[$k]->info);
						for($l=0; $l != count($keys);$l++){
						$info .= $keys[$l].' = '.$obj[$keys[$l]].'<br>';
						}
						$info .= '<br>';
					}
				}
				apisayPOST($info,$toho,$torep);
			}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
			if (checkname($answ[0],$kb_name) and ($answ[1]=='цвет' or $answ[1]=='rgb' or $answ[1]=='ргб')){
				$im = imagecreatetruecolor(100, 100);
				$color = imagecolorallocate($im,$answ[2], $answ[3], $answ[4]);
				imagefilledrectangle($im,0,0,100,100,$color);
				imagejpeg($im,'rgb.jpg');
				imagedestroy($im);
				$req = array(
					'v' => '5.68',
					'access_token' => $token,
				);
				$get_params = http_build_query($req);
				$url = json_decode(file_get_contents('https://api.vk.com/method/photos.getMessagesUploadServer?'. $get_params));
				$ch = curl_init();curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				$parameters = array(
					'file1' => new CURLFile('rgb.jpg') 
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
					'access_token' => $token,
				);
				$get_params = http_build_query($req);
				$res = json_decode(file_get_contents('https://api.vk.com/method/photos.saveMessagesPhoto?'. $get_params));
				$req = array(
					'v' => '5.68',
					'peer_id' => $result->updates[$i][3],
					'attachment' => 'photo'.$res->response[0]->owner_id.'_'.$res->response[0]->id,
					'access_token' => $token,
				);
				$get_params = http_build_query($req);
				$res = json_decode(file_get_contents('https://api.vk.com/method/messages.send?'. $get_params));
			}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
			if (in_array($answ[0],$kb_name) and $answ[1] == 'скачай'){
				if ($result->updates[$i][6]->from == '354255965'){
				$req = array(
					'v' => '5.68',
					'message_ids' => $torep,
					'access_token' => $token,
				);
				$get_params = http_build_query($req);
				$res = json_decode(file_get_contents('https://api.vk.com/method/messages.getById?'. $get_params));
				$url = $res->response->items[0]->attachments[0]->doc->url;
				if (file_put_contents('./downloads/'.$res->response->items[0]->attachments[0]->doc->title,file_get_contents($url))){
					apisayPOST('Загрузка файла '.$res->response->items[0]->attachments[0]->doc->title.' завершена.',$toho,$torep);
				}
				else{
					apisayPOST('В чём то произошла ошибка. Жаль я хз в чём.',$toho,$torep);
				}
				}
				else{
					apisay('А ну убери свои ручонки от админ команд, кусок дерьма',$toho,$torep);	
				}
			}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
			if (in_array($answ[0],$kb_name) and $answ[1] == 'скинь'){
				if ($result->updates[$i][6]->from == '354255965'){
				 			$req = array(
		'v' => '5.68',
		'peer_id' => $torep,
		'access_token' => $token
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
				'access_token' => $token
			);
			$get_params = http_build_query($req);
			$res = json_decode(file_get_contents('https://api.vk.com/method/docs.save?'. $get_params));
			$req = array(
				'v' => '5.68',
				'peer_id' => $result->updates[$i][3],
				'attachment' => 'doc'.$res->response[0]->owner_id.'_'.$res->response[0]->id,
				'access_token' => $token,
			);
			$get_params = http_build_query($req);
			$res = json_decode(file_get_contents('https://api.vk.com/method/messages.send?'. $get_params));
				}
				else{
					apisay('А ну убери свои ручонки от админ команд, кусок дерьма',$toho,$torep);	
				}
			}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
			if (checkname($answ[0],$kb_name) and $answ[1]=='версия'){
				$version = 'Бот-Андроид класса KB No.3
				Версия: 1.0 (Стабильная)';
				apisayPOST($version,$toho,$torep);
			}
			if (checkname($answ[0],$kb_name) and $answ[1]=='звонки'){
				apisayPOST('1) 9:00-9:45 || 9:50-10:35 
2) 10:45-11:30||12:00-12:45 
Обед 11:30-12:00 
3) 12:55-13:40||13:45-14:30 
4) 14:40-15:25||15:30-16:15',$toho,$torep);
			}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
			if (in_array($answ[0],$kb_name) and $answ[1] == 'стик'){
					$req = array(
		'v' => '5.68',
		'message_ids' => $torep,
		'access_token' => $token,
	);
	$get_params = http_build_query($req);
	$res = json_decode(file_get_contents('https://api.vk.com/method/messages.getById?'. $get_params));

		$file = $res->response->items[0]->attachments[0]->doc->url;
		$file = file_get_contents($file);
		$filename = $res->response->items[0]->attachments[0]->doc->title;
		file_put_contents('downloads/'.$filename,$file);
	$req = array(
		'v' => '5.68',
		'type' => 'graffiti',
		'peer_id' => $torep,
		'access_token' => $token
	);
	$get_params = http_build_query($req);
$url = json_decode(file_get_contents('https://api.vk.com/method/docs.getMessagesUploadServer?'. $get_params));
			$ch = curl_init();curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$parameters = array(
			'file' => new CURLFile('downloads/'.$filename)
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
				'title' => $filename,
				'file' => $res->file,
				'access_token' => $token
			);
			$get_params = http_build_query($req);
			$res = json_decode(file_get_contents('https://api.vk.com/method/docs.save?'. $get_params));
			$req = array(
				'v' => '5.68',
				'peer_id' => $result->updates[$i][3],
				'attachment' => 'doc'.$res->response[0]->owner_id.'_'.$res->response[0]->id,
				'access_token' => $token,
			);
			$get_params = http_build_query($req);
			$res = json_decode(file_get_contents('https://api.vk.com/method/messages.send?'. $get_params));
			}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
if (in_array($answ[0],$kb_name) and $answ[1] == 'кек'){
	$req = array(
		'v' => '5.68',
		'message_ids' => $torep,
		'access_token' => $token,
	);
	$get_params = http_build_query($req);
	$res = json_decode(file_get_contents('https://api.vk.com/method/messages.getById?'. $get_params));
	if (empty($res->response->items[0]->attachments[0]->photo)){
		apisay('Вы забыли приложить фотографию к своему запросу.',$toho,$torep);	
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
	if ($answ[2]!='лол'){
		keklol($img_url);
	}else{
		keklol2($img_url);
	}
	$req = array(
		'v' => '5.68',
		'access_token' => $token,
	);
	$get_params = http_build_query($req);
$url = json_decode(file_get_contents('https://api.vk.com/method/photos.getMessagesUploadServer?'. $get_params));
$ch = curl_init();curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$parameters = [
    'file1' => new CURLFile('test.jpg') 
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
		'access_token' => $token,
	);
	$get_params = http_build_query($req);
	$res = json_decode(file_get_contents('https://api.vk.com/method/photos.saveMessagesPhoto?'. $get_params));
	$req = array(
		'v' => '5.68',
		'peer_id' => $result->updates[$i][3],
		'attachment' => 'photo'.$res->response[0]->owner_id.'_'.$res->response[0]->id,
		'access_token' => $token,
	);
	$get_params = http_build_query($req);
	$res = json_decode(file_get_contents('https://api.vk.com/method/messages.send?'. $get_params));
	$img_data = file_get_contents('test.jpg');
	file_put_contents('./old_pics/'.rand(0,99999999).'.jpg',$img_data);
						echo('Упоминание кб в '.$result->updates[$i][3].PHP_EOL);
	}unset($text,$img_size,$img_x,$img_y,$req,$get_params,$res,$pic_data,$obj_array,$img_url,$url,$ch,$parameters,$curl_result,$img_data);
}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
			if (checkname($answ[0],$kb_name) and $answ[1]=='двач'){
						if (!empty($answ[2])){
							if (file_get_contents('http://2ch.hk/'.$answ[2].'/index.json')){
								$thread = json_decode(file_get_contents('http://2ch.hk/'.$answ[2].'/index.json'));
								$count = count($thread->threads);
								$randt = rand(0,$count-1);
								$info = $thread->threads[$randt];
								$req = array(
									'v' => '5.68',
									'access_token' => $token,
								);
								$get_params = http_build_query($req);
								$url = json_decode(file_get_contents('https://api.vk.com/method/photos.getMessagesUploadServer?'. $get_params));
								$ch = curl_init();curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
								$infcount = count($info->posts[0]->files);
								$parameters='';
								if ($infcount > 5)
									$infcount=5;
									file_put_contents('2ch/pic1.jpg',file_get_contents('http://2ch.hk'.$info->posts[0]->files[0]->path));
									$parameters = array(
										'file1' => new CURLFile('2ch/pic1.jpg')
									);
								curl_setopt($ch, CURLOPT_URL, $url->response->upload_url);
								curl_setopt($ch, CURLOPT_POST, true);
								curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
								$curl_result = curl_exec($ch);
								curl_close($ch);
								$chtext = strip_tags($thread->threads[$randt]->posts[0]->comment,'<br>');
								$chtext = str_replace('&quot;','"',$chtext);
								$res = json_decode($curl_result);
								$req = array(
									'v' => '5.68',
									'album_id' => '231057848',
									'server' => $res->server,
									'photo' => $res->photo,
									'hash' => $res->hash,
									'access_token' => $token,
								);
								$get_params = http_build_query($req);
								$res = json_decode(file_get_contents('https://api.vk.com/method/photos.saveMessagesPhoto?'. $get_params));
								$req = array(
									'v' => '5.68',
									'peer_id' => $result->updates[$i][3],
									'attachment' => 'photo'.$res->response[0]->owner_id.'_'.$res->response[0]->id,
									'access_token' => $token,
									'message' => 'Тред: https://2ch.hk/'.$answ[2].'/res/'.$info->posts[0]->num.'.html<br>'.$chtext
								);
								$get_params = http_build_query($req);
								$opts = array('http' =>
									array(
										'method'  => 'POST',
										'header'  => 'Content-type: application/x-www-form-urlencoded',
										'content' => $get_params
									)
								);
								$context  = stream_context_create($opts);
								file_get_contents('https://api.vk.com/method/messages.send', false, $context);
								unlink('2ch/pic1.jpg');
								//apisayPOST(strip_tags($thread->threads[rand(0,$count-1)]->posts[0]->comment),$toho,$torep);
								//file_put_contents('tmp.txt',print_r($thread->threads,true));
							}else apisayPOST('Такой доски не существует',$toho,$torep);
						}else apisayPOST('Вы забыли указать доску',$toho,$torep);
			}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
				if (in_array($answ[0],$kb_name) and $answ[1] == 'f_ИДИТЕ НАХУЙ'){
						$info = '';
						$code = 'var q = "'.$kb_text.'"; var adultfull = API.video.search({ "q": q, "adult": 1, "count": 23 }); var adult = adultfull.items@.id; var noadult = API.video.search({ "q": q, "adult": 0,"count":30 }).items@.id; var i = 0; var result = []; var adultlength = adultfull.count; var noadultlength = noadult.length; if( adultlength > 20 ) adultlength = 20; if( noadultlength > 30 ) noadultlength = 30; while( i < adultlength ) { var j = 0; var videoid = adult[i]; var notexist = true; while(notexist && j<noadultlength) { if(noadult[j] == videoid) notexist = false; j = j + 1; } if(notexist) result.push(adultfull.items[i]); i = i + 1; } return result;';
						$req = array(
							'v' => '5.68',
							'code' => $code,
							'access_token' => $token
						);
						$get_params = http_build_query($req);
						$opts = array('http' =>
							array(
								'method'  => 'POST',
								'header'  => 'Content-type: application/x-www-form-urlencoded',
								'content' => $get_params
							)
						);
						$context  = stream_context_create($opts);
						$list = file_get_contents('https://api.vk.com/method/messages.send', false, $context);
						$list = json_decode($list);
						apisayPOST(print_r($list,true),$toho,$torep);
						if ($list->response->count != 0){
						for ($k=0; $k != count($list->response->items); $k++){
							$info .=  'video'.$list->response->items[$k]->owner_id.'_'.$list->response->items[$k]->id.',';
						}
						//print_r($list);
						$req = array(
							'v' => '5.68',
							'peer_id' => $result->updates[$i][3],
							'access_token' => $token,
							'forward_messages' => $result->updates[$i][1],
							'message' => 'Видео',
							'attachment' => $info
						);
						file_get_contents('https://api.vk.com/method/messages.send?'.http_build_query($req));
						}else{
						apisay('Видео по запросу "'.$vid.'" не найдены.',$toho,$torep);	
						}
						echo('Упоминание кб в '.$result->updates[$i][3].PHP_EOL);
					unset($info,$text,$answer,$vid,$req,$list);
					}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
			if (checkname($answ[0],$kb_name) and $answ[1]=='юз'){
				if ($result->updates[$i][6]->from == '354255965'){
				if (!empty($answ[2])){
					$stats = json_decode(file_get_contents('info.json'));
					$stats->info->tmp = 0;
					file_put_contents('info.json',json_encode($stats));
					apisayPOST('ok',$toho,$torep);
				}else{ $stats = stats('nope'); apisayPOST(print_r($stats,true),$toho,$torep);}
				}else{
						apisay('Убрал руки от моих статистик! Сука',$toho,$torep);	
					}
			}
//##############################################################################################################
//##############################################################################################################
//##############################################################################################################
			$check[]=$result->updates[$i][1];
			}}
			
		}}

/*
1 - номер сообщения
3 - номер беседы (-200000000)
5 - текст
6 - от кого
*/


