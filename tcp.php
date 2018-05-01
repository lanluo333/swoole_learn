<?php
$serv = new swoole_server("127.0.0.1", 9501);

//监听连接进入事件
//$fd是客户端的唯一标识
//$reactor_id是线程id
$serv->on('connect', function ($serv, $fd,$reactor_id) {
    echo "Client:{$reactor_id} - {$fd} -  Connect.\n";
});

$serv->set([
   'worker_num' => 8, //work进程数,一般建议是我们cpu核数的1-4倍
   'max_request' => 10000
]);

//监听数据发送事件
//$fd是客户端连接的唯一标识
$serv->on('receive', function ($serv, $fd, $reactor_id, $data) {
    $serv->send($fd, "Server: ".$data);
});

//监听连接关闭事件
$serv->on('close', function ($serv, $fd) {
    echo "Client: Close.\n";
});

//启动服务器
$serv->start();
