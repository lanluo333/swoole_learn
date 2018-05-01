<?php
/**
 * @Author: Rnb-3Ds
 * @Date:   2018-03-30 08:41:50
 * @Last Modified by:   Rnb-3Ds
 * @Last Modified time: 2018-03-30 09:25:57
 */

//0.0.0.0表示监听所有的端口
$http = new swoole_http_server('0.0.0.0',8811);

//以下设置是如果访问的是静态资源，就直接呈现给浏览器，而不继续走下面的逻辑
$http->set([
	'enable_static_handler' =>true,
	'document_root' =>'/usr/share/nginx/html',
]);

$http->on('request',function($request,$response){
	$response->end("<h1>http_server</h1>");
	//end操作后将向客户端浏览器发送HTML内容
});

$http->start();

