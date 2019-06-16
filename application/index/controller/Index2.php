<?php
namespace app\index\controller;

use think\facade\Request;
use think\facade\Session;
use think\Db;
class Index2 extends Base{
	public function index() {
		return $this->fetch();
	}

	public function login() {
		if (Request::isAjax()) {
			$param = Request::post();
			if ($param['name'] == '') {
				return json(['code' => 1,'msg' => '请输入昵称']);
			}
			if ($param['pwd'] == '') {
				return json(['code' => 1,'msg' => '请输入密码']);
			}
			$user = Db::name('user')->where('name',$param['name'])->find();
			$date = date('Y-m-d H:i:s');
			if ($user) {
				//如果数据库中已经有了该用户，判断密码是否正确
				if ($param['pwd'] == $user['pwd']) {
					Session::set('id',$user['id']);
					Db::name('user')->where('name',$param['name'])->update(['lasttime' => $date,'sessionid' => session_id()]);
					return json(['code' => 0,'msg' => '登录成功','url' => '/']);
				} else {
					//密码错误
					return json(['code' => 1,'msg' => '密码错误']);
				}
			} else {
				//如果数据库中没有该用户，执行注册并登录
				Session::set('id',$user['id']);
				Db::name('user')->insert(['name' => $param['name'],'pwd' => $param['pwd'],'createtime' => $date,'lasttime' => $date,'sessionid' => session_id()]);
				return json(['code' => 0,'msg' => '登录成功','url' => '/']);
			}
		} else {
			return $this->fetch();
		}
	}

	public function logout() {
		Session::delete('id');
		$this->redirect('index/Index/login');
	}
}
