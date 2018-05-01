<?php
/**
 * @Author: Rnb-3Ds
 * @Date:   2018-03-30 09:39:38
 * @Last Modified by:   Rnb-3Ds
 * @Last Modified time: 2018-03-30 10:17:35
 */

$server = new swoole_websocket_server("0.0.0.0", 9503);

// $server->on('open', function (swoole_websocket_server $server, $request) {
//     echo "server: handshake success with fd{$request->fd}\n";
// });

//监听websocket连接事件

$server->set([
	'enable_static_handler' =>true,
	'document_root' =>'/usr/share/nginx/html',
]);

$server->on('open','onOpen');
function onOpen($server,$request){
	print_r($request->fd);
}

//监听websocket消息事件
$server->on('message', function (swoole_websocket_server $server, $frame) {
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    $server->push($frame->fd, "this is server");
});

$server->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});

$server->start();

