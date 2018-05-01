<?php
class Imsock
{
    private $_user_list = array();
    private $_server = null;

    public function __construct($ip_address='0.0.0.0', $ip_port=9501)
    {
        $this->_server = new swoole_websocket_server($ip_address, $ip_port);

	$this->_server->on('open', function($server, $request)
	{
	    $this->_sopen( $server, $request );
	});

	$this->_server->on('message', function($server, $frame)
	{
	    $this->_sendmsg( $server, $frame );
	});

	$this->_server->on('close', function($server, $fd)
	{
	    $this->_sclose( $server, $fd );
	});

	$this->_server->start();
    }

    private function _sopen($server, $request)
    {
	$this->_user_list[ $request->fd ] = 0;
    }

    // 给客户端发送信息
    private function _sendmsg(swoole_websocket_server $server, $frame)
    {
	$server->push($frame->fd, "hello");
    }

    private function _sclose( swoole_websocket_server $server, $fd )
    {
        unset( $this->_user_list[$fd] );
    }
}

$Imsock = new Imsock();