<?php
namespace app\index\controller;

use think\Controller;
use think\facade\Request;
use think\facade\Session;
use think\Db;
class Index extends Controller{
	public function index() {
		return $this->fetch();
	}
}
