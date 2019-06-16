<?php
namespace app\index\controller;

use think\Controller;
use think\facade\Request;
use think\facade\Session;
use think\Db;
class Base extends Controller{
	public function initialize() {
		if (Request::module().'/'.Request::controller().'/'.Request::action() != 'index2/Index/login') {
			if (!Session::has('id')) {
				$this->redirect('index2/Index/login');
			} else {
				//重复登录踢下线
				$sessionid = Db::name('user')->where('id',Session::get('id'))->value('sessionid');
				if ($sessionid != session_id()) {
					Session::delete('id');
					$this->redirect('index2/Index/login');
				}
			}
		}

		if (Request::module().'/'.Request::controller().'/'.Request::action() == 'index2/Index/login' && Session::has('id')) {
			$this->redirect('index2/Index/index');
		}
	}
}
