<?php

namespace app\Admin\controller;

use app\Admin\validate;
use think\Controller;
use think\Request;
use think\Db;

class Index extends Controller
{
    public function index()
    {
    	session_start(); 
        if(!isset($_SESSION['name'])){
            $this->error("您还没有登录，请先去登录" , 'index/login');
        }
    	$name = $_SESSION['name'];

    	$admin_id = $_SESSION['admin_id'];
        $data = Db::table('admin_log')->query("select * from admin_user join admin_log on admin_user.admin_id=admin_log.admin_id where admin_user.admin_id");
        // var_dump($data);die;
    	for ($i=0; $i < count($data); $i++) { 
    		if($data[$i]['status']==0){
	    		$data[$i]['status']='成功';
	    	}else{
	    		$data[$i]['status']='失败';
	    	}
	    	$data[$i]['new_time']=date('Y-m-d H:i:s',$data[$i]['new_time']);
    	}

    	$order = Db::table('admin_log')->where('admin_id',$admin_id)->order('new_time','desc')->limit(2)->select();
    	$title = Db::table('title')->select();
        $this->assign('title',$title);
    	if(count($order)>1){

            $order[1]['new_time']=date('Y-m-d H:i:s',$order[1]['new_time']);
            $this->assign('order',$order[1]);
        }else{
            $this->assign('order',$order[0]);

        }
    	$this->assign('data',$data);


        return view('index');
    }
    public function login(){
    	if($_POST){
    		session_start();
    		$name = input('name');
    		$password = input('password');
    		$res = Db::table('admin_user')->where(['name'=>$name,'password'=>$password])->select();
    		if($res){
                $_SESSION['name']=$name;
    			$_SESSION['admin_id']=$res[0]['admin_id'];
    			$data = [
    				'admin_id' =>$res[0]['admin_id'],
    				'new_time'=>time(),
    				'status'=>0,
    			];
    			Db::table("admin_log")->insert($data);
    			$this->success("登录成功" , 'index/index');
    		}else{
    			$data = [
    				'admin_id' =>$res[0]['admin_id'],
    				'new_time'=>time(),
    				'status'=>1,
    			];
    			Db::table("admin_log")->insert($data);
    			$this->success('登录失败' , 'index/login');
    		} 
    	}else{
    	  return view('login');
    	}
    }
  
   
    


}
