<?php
namespace app\index\controller;

use think\Controller;
use think\facade\Config;
use org\Redis;
class Index extends Controller{
	public function test() {
		$fd = 5;
		Redis::getInstance()->hset('chat-member','1',json_encode(['fd' => 1,'name' => '武哥']));
		Redis::getInstance()->hset('chat-member','2',json_encode(['fd' => 2,'name' => '武少']));
		Redis::getInstance()->hset('chat-member',$fd,json_encode(['fd' => $fd,'name' => '武爷']));
		//$res = Redis::getInstance()->hVals('chat-member');
		
		//dump($res);
	}
	public function phpinfo() {
		phpinfo();
	}
	public function index() {
		$this->assign('ws_host',Config::get('app.ws_host'));
		return $this->fetch();
	}
}
