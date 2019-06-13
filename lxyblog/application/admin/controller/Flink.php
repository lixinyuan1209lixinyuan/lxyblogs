<?php

namespace app\Admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class Flink extends Controller
{
	public function index(){
		session_start(); 
        if(!isset($_SESSION['name'])){
            $this->error("您还没有登录，请先去登录" , 'index/login');
        }
    	$name = $_SESSION['name'];

    	$data = Db::table('admin_log')->query("select * from admin_user join admin_log on admin_user.admin_id=admin_log.admin_id where admin_user.admin_id");
    	for ($i=0; $i < count($data); $i++) { 
    		if($data[$i]['status']==0){
	    		$data[$i]['status']='成功';
	    	}else{
	    		$data[$i]['status']='失败';
	    	}
	    	$data[$i]['new_time']=date('Y-m-d H:i:s',$data[$i]['new_time']);
    	}

    	$flink = Db::table('flink')->select();
    	$this->assign('flink',$flink);

    	$this->assign('data',$data);
		return view();
	}
	public function add(){
		session_start();
		if($_POST){
		// echo 1;die;
			$name = input('name');
			$url = input('url');
			// echo $url;
			$arr = [
				'name'=>$name,
				'url'=>$url,
			];
			$res = Db::table('flink')->insert($arr);
			if($res){
				$this->success('登陆成功' , 'flink/index');
			}else{
				$this->error("登录失败" , 'flink/add');
			}
		}else{
			if(!isset($_SESSION['name'])){
            $this->error("您还没有登录，请先去登录" , 'index/login');
        }
    	$name = $_SESSION['name'];

    	$data = Db::table('admin_log')->query("select * from admin_user join admin_log on admin_user.admin_id=admin_log.admin_id where admin_user.admin_id");
    	for ($i=0; $i < count($data); $i++) { 
    		if($data[$i]['status']==0){
	    		$data[$i]['status']='成功';
	    	}else{
	    		$data[$i]['status']='失败';
	    	}
	    	$data[$i]['new_time']=date('Y-m-d H:i:s',$data[$i]['new_time']);
    	}
    	$this->assign('data',$data);
		return view();
		}
	}
}