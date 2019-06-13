<?php

namespace app\Admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class Article extends Common
{
	public function index()
    {
        $name = $_SESSION['name'];
        $res = Db::table('admin_user')->where('name',$name)->select();
        $_SESSION['admin_id'] = $res[0]['admin_id'];
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
        
        if(count($order)>1){

            $order[1]['new_time']=date('Y-m-d H:i:s',$order[1]['new_time']);
            $this->assign('order',$order[1]);

        }else{
            $this->assign('order',$order[0]);

        }
        $title = Db::table('title')->where('admin_id',$res[0]['admin_id'])->paginate(3);
        $page = $title->render();
        $this->assign('page',$page);
    	return view('index',['data'=>$data,'order'=>$order,'title'=>$title]);
	}
	 public function add()
     {
        $name = $_SESSION['name'];
        $admin_id = $_SESSION['admin_id'];
        if($_POST){
            $title = input('title');
            $content = input('content');
            $describe = input('describe');
            $visibility = input('visibility');
            $titlepic = input('titlepic');
            
            // $keyword = Db::table('keyword')->select();
            // $sting = '';
            // foreach ($keyword as $key => $value) {
            //     foreach ($value as $key => $va) {
            //     $sting .= '|'.$va;
            //     }
            // }
            // $sting = substr($sting,1);
            // $stings = "/$sting/";
            // $content = preg_replace($stings, "0.0", $content);
            // echo $content;die;

            $arr = [
                'title' => $title,
                'content' => $content,
                'describe' => $describe,
                'visibility' => $visibility,
                'titlepic' => $titlepic,
                'admin_id' => $admin_id,
                'time' => time(),
            ];

            $validate = new Articlevalidate;
            $result = $validate->check($arr);

            if(!$result){
                // echo $validate->getError();die;
                $this->success($validate->getError(),'article/add');
            }

            $res = Db::table('title')->insert($arr);
            if($res){
                $this->success("文章添加成功" , 'article/index');
            }else{
                $this->success("文章添加失败" , 'index/add');

            }
        }else{
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
            // echo 1;die;
            $this->assign('data',$data);
            $this->assign('order',$order);
            return view();
         }
    }
    public function delete($id)
    {
        $id = explode(',',$id);
        
        $res = Db::table('title')->delete($id);
        if($res){
            return 0;
        }else{
            return 2;
        }
    }
    public function update($title_id)
    {
        // session_start();
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
        $res = Db::table('title')->where('title_id',$title_id)->select();
        // print_r($res);die;
        $this->assign('data',$data);
        $this->assign('res',$res);
        return view();
    }
    public function do_update()
    {
        $title_id = input('title_id');
        $title = input('title');
        $content = input('content');
        $describe = input('describe');
        $titlepic = input('titlepic');
        $data = [
            'title_id' =>$title_id,
            'title' =>$title,
            'content' =>$content,
            'describe' =>$describe,
            'titlepic' =>$titlepic,
        ];
        $res = Db::table('title')->where('title_id',$title_id)->update($data);
        if($res){
            $this->success('文章修改成功' , 'Article/index');
        }else{
            $this->error('文章修改失败' , 'Article/index');
        }
    }
    public function show($title_id){
        if($_POST){
            $cause = input('cause');
            $content = input('content');
            $data = input('submit');


            $res = Db::table('title')->where('title_id',$title_id)->update(['status'=>$data]);
                
            if($res){
                if($data==0){
                    $contents = Db::table('title')->where('title_id',$title_id)->update(['content'=>$content]);
                    $this->success('文章发布成功' , 'Article/index');
                }else{
                    $res = Db::table('title')->where('title_id',$title_id)->update(['cause'=>$cause]);
                    $this->success('此文章不符合发布要求' , 'Article/index');
                }
            }else{
                $this->error('文章发布失败' , 'Article/index');
            }
        }else{
            $reviewed = Db::table('title')->where('title_id',$title_id)->select();
            if($reviewed[0]['status']==0){
                $this->success('文章已审核，并且已通过' , 'Article/index');
            }else if($reviewed[0]['status']==1){
                $this->success($reviewed[0]['cause'].'，'."审核未能通过" , 'Article/index');
            }else{
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
                $res = Db::table('title')->where('title_id',$title_id)->select();

                $this->assign('data',$data);
                $res = Db::table('title')->where('title_id',$title_id)->select();
                $this->assign('res',$res);
                return view();
            }
           
        }
        
    }
}