<?php

namespace app\Admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class Notice extends Controller
{
	public function index()
	{
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

    	$res = Db::table('notice')->select();

    	$this->assign('res',$res);
    	$this->assign('data',$data);
		return view();
	}
	public function add()
	{
		if($_POST){
			$title = input('title');
			$titlepic = input('titlepic');
			$time = input('time');
			$arr = [
				'title'=>$title,
				'titlepic'=>$titlepic,
				'time'=>$time,
			];
			$res = Db::table('notice')->insert($arr);
			if($res){
				$this->success("添加成功" , 'notice/index');
			}else{
				$this->success("添加成功" , 'notice/add');
			}
		}else{
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
	    	$this->assign('data',$data);
			return view();
		}
	}
	public function delete($id){
		$res = Db::table('notice')->where('id',$id)->delete();
		if($res){
			return 1;
		}else{
			return 0;
		}
	}	
	public function update($id){
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
			$res = Db::table('notice')->where('id',$id)->select();
			$this->assign('data',$data);
			return view('update',['res'=>$res]);
	}
	public function do_update(){
		$id = input('id');
		$title = input('title');
		$titlepic = input('titlepic');
		// echo $id;die;
		$data = Db::table('notice')->where('id',$id)->update(['title'=>$title,'titlepic'=>$titlepic]);
		// var_dump($data);die;
		if($data){
			$this->success("修改成功" , 'notice/index');
		}else{
			$this->error("修改失败" , 'notice/index');
		}
	}
}