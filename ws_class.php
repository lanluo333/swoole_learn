<?php
/**
 * @Author: Rnb-3Ds
 * @Date:   2018-03-30 10:24:09
 * @Last Modified by:   Rnb-3Ds
 * @Last Modified time: 2018-03-30 10:39:56
 */

/**
 * websocket基础类
 */
class WS{
	const HOST = '0.0.0.0';
	const PORT = 9503;

	public $ws = null;

	public function __construct(){
		$this->ws = new swoole_websocket_server(self::HOST, self::PORT);

		$this->ws->on('open',[$this,'onOpen']);
		$this->ws->on('open',[$this,'onMessage']);
		$this->ws->on('open',[$this,'onClose']);

		$this->ws->start();
	}

	/**
	 * [onOpen 监听ws连接事件]
	 * @param  [type] $ws      [description]
	 * @param  [type] $request [description]
	 */
	public function onOpen($ws,$request){
		var_dump($request->fd);
	}

	/**
	 * [onMessage 监听ws消息事件]
	 * @param  [type] $ws    [description]
	 * @param  [type] $frame [description]
	 */
	public function onMessage($ws,$frame){
		echo "server-push-message:{$frame->data}\n";
		$ws->push($frame->fd,'server-push:'.date('Y-m-d H:i:s'));
	}

	public function onClose($ws,$fd){
		echo "clientId:$fd \n";
	}
}

$obj = new WS();


