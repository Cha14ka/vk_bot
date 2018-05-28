<?php
$plug_cmds = array('двач', 'харкач', 'сосач', 'тиреч', '2ch', '2ч');
$plug_smalldescription = 'Выводит случайный пост из раздела переданного аргументом';
$plug_mainfunc = function ($kb_msg, $kb_cmds){
		if (explode(' ', $kb_msg[5])[2]){
					$board = explode(' ', $kb_msg[5])[2];
							if (file_get_contents('http://2ch.hk/'.$board.'/index.json')){
								$thread = json_decode(file_get_contents('http://2ch.hk/'.$board.'/index.json'));
								$count = count($thread->threads);
								$randt = rand(0,$count-1);
								$info = $thread->threads[$randt];
								$req = array(
									'v' => '5.68',
									'access_token' => KB_TOKEN,
								);
								$get_params = http_build_query($req);
								$url = json_decode(file_get_contents('https://api.vk.com/method/photos.getMessagesUploadServer?'. $get_params));
								$ch = curl_init();curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
								$infcount = count($info->posts[0]->files);
								$parameters='';
								if ($infcount > 5)
									$infcount=5;
									file_put_contents('./plugins/2ch/pic1.jpg',file_get_contents('http://2ch.hk'.$info->posts[0]->files[0]->path));
									$parameters = array(
										'file1' => new CURLFile('./plugins/2ch/pic1.jpg')
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
									'access_token' => KB_TOKEN,
								);
								$get_params = http_build_query($req);
								$res = json_decode(file_get_contents('https://api.vk.com/method/photos.saveMessagesPhoto?'. $get_params));
								$req = array(
									'v' => '5.68',
									'peer_id' => $kb_msg[3],
									'attachment' => 'photo'.$res->response[0]->owner_id.'_'.$res->response[0]->id,
									'access_token' => KB_TOKEN,
									'message' => 'Тред: https://2ch.hk/'.$board.'/res/'.$info->posts[0]->num.'.html<br>'.$chtext
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
								unlink('./plugins/2ch/pic1.jpg');
								//apisayPOST(strip_tags($thread->threads[rand(0,$count-1)]->posts[0]->comment),$toho,$torep);
								//file_put_contents('tmp.txt',print_r($thread->threads,true));
							}else apisay('Такой доски не существует',$kb_msg[3],$kb_msg[1]);
						}else apisay('Вы забыли указать доску',$kb_msg[3],$kb_msg[1]);
	};

register_command(array(
		'cmds' => $plug_cmds,
		'cmdfunc' => $plug_mainfunc,
		'smalldescription' => $plug_smalldescription
));