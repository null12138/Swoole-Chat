<?php
namespace app\http;
use think\swoole\Server;
use think\Db;
class Swoole2 extends Server
{
	protected $host = '0.0.0.0';
	protected $port = 9509;
	protected $serverType = 'socket';
	protected $mode = SWOOLE_PROCESS;
	protected $sockType = SWOOLE_SOCK_TCP;
	protected $option = [ 
		'worker_num'=> 2,
		'daemonize'	=> false,
		'backlog'	=> 128
	];

	public function onOpen($server,$request) {
		// foreach($server->connections as $fd) {
			
		// };
		//print_r($server->connections);
		//print_r($request);
		//echo 'fd: '.$request->fd;
	}

	public function onMessage($server,$frame) {
		//echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
		//$server->push($frame->fd,'hello');
	}

	public function onClose($server,$fd) {
		echo "client {$fd} cloed\n";
	}
}