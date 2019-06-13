<?php

namespace app\Admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class common extends Controller
{
	public function __construct()
	{
		parent::__construct();
		session_start(); 
		$admin_id = $_SESSION['admin_id'];
		if(empty($admin_id)){
			$this->error('您还没有登录，请先登录' , 'index/login');
		}
		$controller = Request()->controller();
		$action = Request()->action();
		$power = $controller.'/'.$action;
		// echo $power;die;
		if($power=='Index/index'){
			return true;
		}
		$res = Db::table('user_role')->where('user_id',$admin_id)->select();
		$roleids ='';
		foreach ($res as $key => $value) {
		 	$roleids .= ','.$value['role_id'];
		 }
		 $roleids = substr($roleids,1); 
		 // var_dump($roleids);
		 $data = Db::table("node")->join('role_node','node.node_id = role_node.node_id')->where('role_id','in',$roleids)->select();
		 $powers = [];
		 foreach ($data as $key => $value) {

		 	$value['action'] = strtolower($value['action']);
		 	$powers[] = $value['controller'].'/'.$value['action'];
		 }
		 // var_dump($powers);
		 $powers[] = 'Index/index'; 
		 $powers[] = 'Index/login'; 
		 // var_dump($power);
		 // echo "<pre>";
		 // var_dump($powers);die;
		 if(!in_array($power, $powers)){
		 	$this->error('您没有权限，可以去联系管理员' , 'index/index');
		 }
		
	}	
}