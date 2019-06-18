<?php
namespace app\index\controller;

use think\Controller;
use think\facade\Config;
class Index extends Controller{
	public function phpinfo() {
		phpinfo();
	}
	public function index() {
		$this->assign('ws_host',Config::get('app.ws_host'));
		return $this->fetch();
	}
}
