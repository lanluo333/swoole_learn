<?php
/**
 * @Author: Rnb-3Ds
 * @Date:   2018-03-30 10:24:09
 * @Last Modified by:   Rnb-3Ds
 * @Last Modified time: 2018-03-30 15:42:47
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

		$this->ws->set([
			'worker_num' =>2,
			'task_worker_num' => 2
		]);
		$this->ws->on('open',[$this,'onOpen']);
		$this->ws->on('message',[$this,'onMessage']);
		$this->ws->on('task',[$this,'onTask']);
		$this->ws->on('finish',[$this,'onFinish']);
		$this->ws->on('close',[$this,'onClose']);

		$this->ws->start();
	}

	/**
	 * [onOpen 监听ws连接事件]
	 * @param  [type] $ws      [description]
	 * @param  [type] $request [description]
	 */
	public function onOpen($ws,$request){
		var_dump($request->fd);
		if ($request->fd == 1) {
			//每2秒执行一次
			swoole_timer_tick(2000,function($timer_id){
				var_dump('2s:timer_id:'.$timer_id.'\n');
			});
		}
	}

	/**
	 * [onMessage 监听ws消息事件]
	 * @param  [type] $ws    [description]
	 * @param  [type] $frame [description]
	 */
	public function onMessage($ws,$frame){
		echo "server-push-message:{$frame->data}\n";
		// todo 10s
		$data = [
			'task' => 1,
			'fd' => $frame->fd
		];
		swoole_timer_after(5000,function() use($ws,$frame){
			//5s后执行
			echo "5s-after\n";
			$ws->push($frame->fd,'server time after\n');
		});
		$ws->task($data);//投递任务
		$ws->push($frame->fd,'server-push:'.date('Y-m-d H:i:s'));
	}

	/**
	 * [onTask 任务投递，处理耗时的任务]
	 * @param  [type] $serve    [description]
	 * @param  [type] $taskId   [description]
	 * @param  [type] $workerId [description]
	 * @param  [type] $data     [description]           [description]
	 */
	public function onTask($serve,$taskId,$workerId,$data){
		print_r($data);
		//耗时事件10s
		sleep(10);
		return 'onTask finish';
	}

	/**
	 * [onFinish Task投递任务必须的结束，否则会报错]
	 * @param  [type] $serve  [description]
	 * @param  [type] $taskId [description]
	 * @param  [type] $data   onTask那里return回来的内容
	 */
	public function onFinish($serve,$taskId,$data){
		echo "taskId:{$taskId}\n";
		echo "finish-data-success:{$data}\n";
	}

	public function onClose($ws,$fd){
		echo "clientId:$fd \n";
	}
}

$obj = new WS();


