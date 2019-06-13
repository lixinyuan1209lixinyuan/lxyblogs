<?php

namespace app\Admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class Readset extends Controller
{
	public function index(){
		session_start(); 
        if(!isset($_SESSION['name'])){
            $this->error("您还没有登录，请先去登录" , 'index/login');
        }
        $name = $_SESSION['name'];
    	$admin_id = $_SESSION['admin_id'];
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
    	   $this->assign('data',$data);


        return view('index');
	}
	public function add(){
		if($_POST){
			$username = input('username');
			$userpwd = input('userpwd');
			$arr = [
				'username'=>$username,
				'userpwd'=>$userpwd,
			];
			$res = Db::table('user')->insert($arr);
			if($res){
				$this->success('添加成功' , 'readset/show');
			}else{
				$this->error('添加成功' , 'readset/add');
			}
		}
	}
	public function show(){
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

        $res =  Db::table('user')->select();


    	$this->assign('res',$res);
    	$this->assign('data',$data);
		return view();
	}
	public function role(){
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
		$this->assign('data',$data);
		$res =  Db::table('role')->select();
		$this->assign('res',$res);
		return view();
	}
    public function role_add(){
        session_start(); 
        if($_POST){
            $role_name = input('role_name');
            $res = Db::table('role')->insert(['role_name'=>$role_name]);
            if($res){
                $this->success('添加成功' , 'readset/role');
            }else{
                $this->error('添加成功' , 'readset/role_add');
            }
        }else{
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
        $this->assign('data',$data);

        
        return view();
    }
    }
    public function node_update(){
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
        $this->assign('data',$data);

        $res = Db::table('node')->select();
        $this->assign('res',$res);
        $role_id = input('role_id');
        $_SESSION['role_id'] = $role_id;
        // echo $role_id;die;
        return view();
        
    }
   
    public function role_doadd(){
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
        $this->assign('data',$data);
        return view();
    }
    public function role_update(){
        session_start(); 
        if($_POST){
            $user_id = $_SESSION['user_id'];
            // print_r($user_id);die;
            $role_id = input('role_id');
            $data = Db::table('user_role')->where('user_id',$user_id)->select();
            if($data){
               $res = Db::name('user_role')->where('user_id',$user_id)->update(['role_id' => $role_id]);

                // var_dump($res);die;
                if($res){
                    $this->success('修改角色成功' , 'readset/show');
                }else{
                    $this->success('修改角色失败' , 'readset/show');
                }

            }else{
                $res = Db::table('user_role')->insert(['user_id'=>$user_id,'role_id'=>$role_id]);
                if($res){
                    $this->success('分配角色成功' , 'readset/show');
                }else{
                    $this->success('分配角色失败' , 'readset/show');
                }
            }
            
        }else{

         
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
        $user_id = input('user_id');
        // echo $user_id;die;
        // $_SESSION['user_id'] = $user_id;
        
        $res = Db::table('role')->select();
        $user_res = Db::query("select * from user join user_role on user.user_id=user_role.user_id join role on user_role.role_id=role.role_id where user.user_id=$user_id");

        // print_r($user_res);die;        
        $role_id = [];
        foreach ($user_res as $key => $v) {
            $role_id[] = $v['role_id'];
        }
        foreach ($res as $key => $va) {
            if(in_array($va['role_id'],$role_id)){
                $res[$key]['flag']=1;
            }else{
                $res[$key]['flag']=0;
            }
        }
        $this->assign('data',$data);
        $this->assign('res',$res);
        return view();
        }
        
    }
    public function node_doadd(){
        session_start();
       if($_POST){
            
            $data = input();
            $role_id = $_SESSION['role_id'];
            // echo $user_id;die;
            
            $arr = [];
            foreach ($data as $key => $value) {
               $arr[$key]['node_id']=$value;
               $arr[$key]['role_id']=$_SESSION['role_id'];
            }
            Db::table('role_node')->where('role_id',$role_id)->delete();
            $res = Db::table('role_node')->insertAll($arr);
            if($res){
                    $this->success('分配权限成功' , 'readset/show');
                }else{
                    $this->success('分配权限失败' , 'readset/show');
                }
           
        }else{

        }
    }
     public function node_add($role_id)
     {

        session_start(); 
        // print_r($role_id);die;
        if(!isset($_SESSION['name'])){
            $this->error("您还没有登录，请先去登录" , 'index/login');
        } 
        $name = $_SESSION['name'];
        $_SESSION['role_id'] = $role_id;
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

        $res = Db::table('node')->select();
        $node_res = Db::query("select * from role join role_node on role.role_id=role_node.role_id join node on role_node.node_id=node.node_id where role.role_id=$role_id");
        $node_id = [];
        foreach ($node_res as $key => $v) {
            $node_id[] = $v['node_id'];
        }
        foreach ($res as $key => $va) {
            if(in_array($va['node_id'],$node_id)){
                $res[$key]['flag']=1;
            }else{
                $res[$key]['flag']=0;
            }
        }
        $this->assign('res',$res);
        $this->assign('data',$data);
        return view();
    }
}