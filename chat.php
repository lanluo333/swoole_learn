<?php
/**
 * @Author: Rnb-3Ds
 * @Date:   2018-04-02 16:15:18
 * @Last Modified by:   Rnb-3Ds
 * @Last Modified time: 2018-04-02 16:21:56
 */

$ws_server = new swoole_websocket_server('0.0.0.0', 9502);

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
//$redis->flushAll();exit;

$ws_server->on('open', function ($ws, $request) use ($redis) {
    $redis->sAdd('fd', $request->fd);
});

$ws_server->on('message', function ($ws, $frame) use ($redis) {
    global $redis;
    $fds = $redis->sMembers('fd');
    foreach ($fds as $fd){
        $ws->push($fd,$frame->fd.'--'.$frame->data);
        //发送二进制数据：
        // $ws->push($fd,file_get_contents('http://imgsrc.baidu.com/imgad/pic/item/267f9e2f07082838b5168c32b299a9014c08f1f9.jpg'),WEBSOCKET_OPCODE_BINARY);
    }
});

//监听WebSocket连接关闭事件
$ws_server->on('close', function ($ws, $fd) use ($redis) {
    $redis->sRem('fd',$fd);
});

$ws_server->start();


