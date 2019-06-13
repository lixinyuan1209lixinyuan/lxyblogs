<?php

namespace app\Admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class Comment extends Controller
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
    	for ($i=0; $i < count($data); $i++) { 
    		if($data[$i]['status']==0){
	    		$data[$i]['status']='成功';
	    	}else{
	    		$data[$i]['status']='失败';
	    	}
	    	$data[$i]['new_time']=date('Y-m-d H:i:s',$data[$i]['new_time']);
    	}

    	$order = Db::table('admin_log')->where('admin_id',$admin_id)->order('new_time','desc')->limit(2)->select();
    	
    	if(count($order)>1){

            $order[1]['new_time']=date('Y-m-d H:i:s',$order[1]['new_time']);
            $this->assign('order',$order[1]);

        }else{
            $this->assign('order',$order[0]);

        }
        
        $title = Db::table('title')->where('admin_id',$admin_id)->select();
        // var_dump($title);die;
        $this->assign('data',$data);
    	$this->assign('title',$title);
        return view('index');
    }
    public function show($title_id){
        session_start(); 
        $_SESSION['title_id'] = $title_id;
        if(!isset($_SESSION['name'])){
            $this->error("您还没有登录，请先去登录" , 'index/login');
        }
        $name = $_SESSION['name'];
        $admin_id = $_SESSION['admin_id'];
        $data = Db::table('admin_log')->query("select * from admin_user join admin_log on admin_user.admin_id=admin_log.admin_id where admin_user.admin_id");
        for ($i=0; $i < count($data); $i++) { 
            if($data[$i]['status']==0){
                $data[$i]['status']='成功';
            }else{
                $data[$i]['status']='失败';
            }
            $data[$i]['new_time']=date('Y-m-d H:i:s',$data[$i]['new_time']);
        }
        $admin_id = $_SESSION['admin_id'];
        $res = Db::table('comment')->where(["title_id"=>$title_id,'admin_id'=>$admin_id])->select();
        for ($i=0; $i < count($res); $i++) { 
            if($res[$i]['status']==$i){
                $res[$i]['status']='发布';
            }else{
                $res[$i]['status']='不发布';
            }
        }
        $this->assign('data',$data);
        $this->assign("res",$res);
        return view();
    }
    public function ajax($comment_id){
        $res = Db::table('comment')->where('comment_id',$comment_id)->select();
        // var_dump($res);die;
        if($res[0]['status']==0){
            $res = Db::table('comment')->where('comment_id',$comment_id)->update(['status'=>1]);
            return 1;
        }else{
            $res = Db::table('comment')->where('comment_id',$comment_id)->update(['status'=>0]);
            return 0;
        }
    }
    public function delete($title_id){
        $res = Db::table('title')->where('title_id',$title_id)->delete();
        if($res){
            $data = Db::table('comment')->where('title_id',$title_id)->delete();
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
    public function del($comment_id){
        $res = Db::table('comment')->where('comment_id',$comment_id)->delete();
        if($res){
            $this->success('删除成功' , 'comment/show');
        }else{
            $this->error('删除失败' , 'comment/show');
        }
    }
    public function update($comment_id){

    }
}