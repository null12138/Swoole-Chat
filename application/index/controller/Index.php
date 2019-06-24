<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\facade\Request;
use think\facade\Config;
class Index extends Controller{
	public function phpinfo() {
		phpinfo();
	}
	
	public function index() {
		$this->assign('ws_host',Config::get('app.ws_host'));
		return $this->fetch();
	}
	
	public function exists() {
		$name = Request::post('name');
		$res = Db::name('temp')->where('name',$name)->find();
		if ($res) {
			return json(['code' => 1,'msg' => '昵称已被使用']);
		} else {
			return json(['code' => 0,'msg' => '成功']);
		}
	}
}
