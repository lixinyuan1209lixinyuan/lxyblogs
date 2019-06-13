<?php

namespace app\Admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class Manage extends Controller
{
	public function index(){
		session_start();
        $name = $_SESSION['name'];
		$admin_id = $_SESSION['admin_id'];
        // echo $name;die;
    	$data = Db::table('admin_log')->query("select * from admin_user join admin_log on admin_user.admin_id=admin_log.admin_id where admin_user.admin_id");
    	for ($i=0; $i < count($data); $i++) { 
    		if($data[$i]['status']==0){
	    		$data[$i]['status']='成功';
	    	}else{
	    		$data[$i]['status']='失败';
	    	}
	    	$data[$i]['new_time']=date('Y-m-d H:i:s',$data[$i]['new_time']);
    	}
    	// 上次登录的时间
    	$order = Db::table('admin_log')->where('admin_id',$admin_id)->order('new_time','desc')->limit(2)->select();
    	if(count($order)>1){

    		$order[1]['new_time']=date('Y-m-d H:i:s',$order[1]['new_time']);
    	}
    	// var_dump($order);die;
    	$user = Db::table('admin_user')->where('name',$name)->select();
    	$title = Db::table('title')->select();
        
    	$this->assign('data',$data);
    	$this->assign('title',$title);
    	$this->assign('user',$user);
    	$this->assign('order',$order);

		return view();
	}
    public function disable($admin_id){
        $res = Db::table('admin_user')->where('admin_id',$admin_id)->select();
        if($res[0]['status']==0){
            $data = Db::table('admin_user')->where('admin_id',$admin_id)->update(['status'=>1]);
            if($data){
                $this->success("已禁用成功",'manage/index');
            }else{
                $this->success("已禁用失败",'manage/index');
            }
        }
        if($res[0]['status']==1){
            $data = Db::table('admin_user')->where('admin_id',$admin_id)->update(['status'=>0]);
            if($data){
                $this->success("已关闭禁用成功",'manage/index');
            }else{
                $this->success("已关闭禁用失败",'manage/index');
            }
        }
    }
}