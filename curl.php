<?php
/**
 * @Author: Rnb-3Ds
 * @Date:   2018-03-31 15:14:36
 * @Last Modified by:   Rnb-3Ds
 * @Last Modified time: 2018-03-31 15:29:34
 */

echo "process-start-time:".date('Y-m-d H:i:s'),PHP_EOL;
$workers = array();

$urls = [
	'http://www.baidu.com',
	'http://sina.com.cn',
	'http://www.baidu.com?search=handsome',
	'http://www.baidu.com?search=handsomerui',
	'http://www.baidu.com?search=handsomerui2',
];

for ($i=0; $i < count($urls) ; $i++) {
	//子进程
	$process = new swoole_process(function(swoole_process $pro) use($i,$urls){
		$content = curlData($urls[$i]).PHP_EOL;
		// echo $content;
		$pro->write($content);//把内容写进管道
	},true);
	$pid = $process->start();
	$workers[$pid] = $process;
}

foreach ($workers as $work) {
	echo $work->read();
}

/**
 * 模拟请求url的内容
 * @param  [type] $url [description]
 * @return [type]      [description]
 */
function curlData($url){
	sleep(1);
	return $url.'success'.PHP_EOL;
}
echo "process-end-time:".date('Y-m-d H:i:s');
