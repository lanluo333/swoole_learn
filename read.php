<?php
/**
 * @Author: Rnb-3Ds
 * @Date:   2018-03-30 16:01:42
 * @Last Modified by:   Rnb-3Ds
 * @Last Modified time: 2018-03-30 16:17:20
 */


//swoole_async_readfile会将文件内容全部复制到内存，所以不能用于大文件的读取
//如果要读取超大文件，请使用swoole_async_read函数
//swoole_async_readfile最大可读取4M的文件
swoole_async_readfile(__DIR__.'/1.txt',function($filename,$content){
	echo 'filename:'.$filename.PHP_EOL;
	echo "content:".$content.PHP_EOL;
});

//这种情况会先输出下面的内容，然后再输出上面的，因为是异步，文件读取相对慢些
echo "start",PHP_EOL;

