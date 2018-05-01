<?php

//连接swoole的tcp服务
$client = new swoole_client(SWOOLE_SOCK_TCP);

if (!$client->connect('127.0.0.1',9501)) {
	echo "连接失败";
	die();
}

//php cli常量
fwrite(STDOUT, '请输入消息:');
$msg = trim(fgets(STDIN));//获取用户输入的消息

//发送消息给tcp服务器
$client->send($msg); //成功则返回true

//接收来自server的数据
$res = $client->recv();
echo $res;
