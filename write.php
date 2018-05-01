<?php
/**
 * @Author: Rnb-3Ds
 * @Date:   2018-03-30 16:19:42
 * @Last Modified by:   Rnb-3Ds
 * @Last Modified time: 2018-03-30 16:26:45
 */

$content = date('Y-m-d H:i:s').PHP_EOL;
swoole_async_writefile(__DIR__.'/1.log',$content,function($filename,$content){
	if ($filename) {
		echo "success",PHP_EOL;
	}
},FILE_APPEND);

echo "start",PHP_EOL;

