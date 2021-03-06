<?php
namespace app\server;

use think\swoole\Server;
use think\Db;
use org\Redis;
class Swoole extends Server
{
	protected $host = '0.0.0.0';
	protected $port = 9501;
	protected $serverType = 'socket';
	protected $mode = SWOOLE_PROCESS;
	protected $sockType = SWOOLE_SOCK_TCP;
	protected $option = [ 
		'worker_num'	=> 1,
		'daemonize'		=> false,
		'backlog'		=> 128,
	];
	// protected $sockType = SWOOLE_SOCK_TCP | SWOOLE_SSL;
	// protected $option = [ 
	// 	'worker_num'	=> 1,
	// 	'daemonize'		=> true,
	// 	'backlog'		=> 128,
	// 	'ssl_cert_file'	=> '/etc/nginx/ssl/1_chat.xuyiwu.com_bundle.crt',
	// 	'ssl_key_file'	=> '/etc/nginx/ssl/2_chat.xuyiwu.com.key'
	// ];
	protected $users = [];

	public function onOpen($server,$request) {
		$data['type'] = 0;
		$data['total'] = count($server->connections);
		foreach ($server->connections as $fd) {
			//统计当前在线用户数量
			$server->push($fd,json_encode($data));
		}
	}

	public function onMessage($server,$frame) {
		$data = json_decode($frame->data,true); 
		switch ($data['type']) {
			case 1:
				# code...
				break;
			case 2:
				$data['type'] = 1;
				$data['fd'] = $frame->fd;
				foreach ($server->connections as $fd) {
					//给每个用户发送新用户连接提醒(排除自身)
					if ($fd != $frame->fd) {
						$server->push($fd,json_encode($data));
					}
				}
				// //查询出所有用户
				// $users = Redis::getInstance()->hGetAll('chat-member');
				// if ($users) {
				// 	//获取新连接用户的列表数据
				// 	foreach ($users as $key => $value) {
				// 		$users[$key] = json_decode($value,true);
				// 	}
				// 	$res['type'] = 2;
				// 	$res['list'] = json_encode($users);
				// 	$server->push($frame->fd,json_encode($res));
				// }
				// //将新用户数据保存
				// Redis::getInstance()->hSet('chat-member',$frame->fd,json_encode(['fd' => $frame->fd,'name' => $data['name']]));
				
				//查询出所有用户
				$users = Db::name('temp')->field('fd,name')->select();
				if ($users) {
					$res['type'] = 2;
					$res['list'] = json_encode($users);
					$server->push($frame->fd,json_encode($res));
				}
				//将新用户数据保存
				Db::name('temp')->insert(['fd' => $frame->fd,'name' => $data['name']]);
				break;
			case 3:
				# code...
				break;
			case 4:
				//收到用户发送的消息
				$res = array(
					'type'		=> 4,
					'fd'		=> $frame->fd,
					'name'		=> $data['name'],
					'text'		=> $data['text'],
					'time'		=> $data['time'],
					'target'	=> $data['target']
				);
				if ($data['target'] == 0) {
					//群聊消息，推送给所有用户(排除自己)
					foreach ($server->connections as $fd) {
						if ($fd != $frame->fd) {
							$server->push($fd,json_encode($res));
						}
					}
				} else {
					//1v1消息，推送给目标用户
					$server->push($data['target'],json_encode($res));
				}
				break;
			case 5:
				//接收图片消息
				$res = array(
					'type'		=> 5,
					'fd'		=> $frame->fd,
					'name'		=> $data['name'],
					'text'		=> $data['text'],
					'time'		=> $data['time'],
					'target'	=> $data['target']
				);
				if ($data['target'] == 0) {
					//群聊消息，推送给所有用户(排除自己)
					foreach ($server->connections as $fd) {
						if ($fd != $frame->fd) {
							$server->push($fd,json_encode($res));
						}
					}
				} else {
					//1v1消息，推送给目标用户
					$server->push($data['target'],json_encode($res));
				}
				break;
			case 6:
				//正在输入
				$res = array(
					'type'		=> 6,
					'fd'		=> $frame->fd
				);
				$server->push($data['target'],json_encode($res));
				break;
			case 7:
				//接收音频消息
				$res = array(
					'type'		=> 7,
					'fd'		=> $frame->fd,
					'name'		=> $data['name'],
					'text'		=> $data['text'],
					'time'		=> $data['time'],
					'target'	=> $data['target']
				);
				if ($data['target'] == 0) {
					//群聊消息，推送给所有用户(排除自己)
					foreach ($server->connections as $fd) {
						if ($fd != $frame->fd) {
							$server->push($fd,json_encode($res));
						}
					}
				} else {
					//1v1消息，推送给目标用户
					$server->push($data['target'],json_encode($res));
				}
				break;
		}
	}

	public function onClose($server,$fd) {
		$user = Db::name('temp')->where('fd',$fd)->find();
		//$user = json_decode(Redis::getInstance()->hGet('chat-member',$fd),true);
		$data = array(
			'type'	=> 3,
			'name'	=> $user['name'],
			'fd'	=> $fd
		);
		$i = 0;
		foreach ($server->connections as $fds) {
			//通知所有用户有用户退出(排除自身)
			if ($fds != $fd) {
				$i++;
				$server->push($fds,json_encode($data));
			}
		}
		//删除用户
		Db::name('temp')->where('fd',$fd)->delete();
		//Redis::getInstance()->hdel('chat-member',$fd);
		$data['type'] = 0;
		$data['total'] = $i;
		unset($data['fd']);
		foreach ($server->connections as $fds) {
			//统计当前在线用户数量
			if ($fds != $fd) {
				$server->push($fds,json_encode($data));
			}
		}
	}
}