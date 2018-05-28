<?php
$plug_cmds = array('скачай', 'dl');
$plug_smalldescription = 'Скачивает файл на компьютер';
$plug_mainfunc = function ($kb_msg, $kb_cmds){
		if (in_array($kb_msg[6]->from, KB_ADMINS)){
				$req = array(
					'v' => '5.68',
					'message_ids' => $kb_msg[1],
					'access_token' => KB_SETTINGS['token'],
				);
				$get_params = http_build_query($req);
				$res = json_decode(file_get_contents('https://api.vk.com/method/messages.getById?'. $get_params));
				$url = $res->response->items[0]->attachments[0]->doc->url;
				if (file_put_contents('./downloads/'.$res->response->items[0]->attachments[0]->doc->title,file_get_contents($url))){
					apisay('Загрузка файла '.$res->response->items[0]->attachments[0]->doc->title.' завершена.',$kb_msg[3], $kb_msg[1]);
				}
				else{
					apisay('В чём то произошла ошибка. Жаль я хз в чём.',$kb_msg[3], $kb_msg[1]);
				}
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