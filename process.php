<?php
/**
 * @Author: Rnb-3Ds
 * @Date:   2018-03-31 14:51:21
 * @Last Modified by:   Rnb-3Ds
 * @Last Modified time: 2018-03-31 14:57:14
 */

$process = new swoole_process(function(swoole_process $pro){
	//开启一个子进程，在子进程中调用另一个文件
	$pro->exec('/usr/bin/php',[__DIR__.'/http_server.php']);
},true);

$pid = $process->start();
echo $pid,PHP_EOL;

swoole_process::wait();//等子进程结束任务之后,进行回收

