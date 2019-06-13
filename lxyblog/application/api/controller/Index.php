<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
class Index extends Controller
{
	protected $beforeActionList = [
                 'token' =>  ['except'=>'index,ad,flink'],
            ];
    public function index()
    {
        $p = input('p', 1);
        $size = innput('size',10);
        $offset = ($p-1)*$size;

        $title = Db::table("title")->where('time','desc')->limit($size,$offset)->select();
        if($title){
        	message('200','文章展示成功',$title);
        }else{
            message('1017','文章添加失败');
        }
    }
    public function add(){
        // echo  1;die;
    	$title = input('title');
    	$content = input('content');
    	$describe = input('describe');
    	$amdin_id = input('amdin_id');
    	$file = request()->file('titlepic');
    	$info = $file->move( '../../../Uploads/image');
    	if($info){
    		 $titlepic = $info->getSaveName();
    	}
    	$arr = [
    		'title' => $title,
    		'content' => $content,
    		'describe' => $describe,
    		'titlepic' => $titlepic,
    		'amdin_id' => $amdin_id,
    		'time' => time(),
    	];
    	$res = Db::table('title')->insert($arr);
    	if($res){
    		message('200','文章添加成功');
    	}else{
    		message('1010','文章添加失败');
    	}
    } 
    public function update(){
    	$title_id = input('title_id');
    	$title = input('title');
    	$content = input('content');
    	$describe = input('describe');
    	$amdin_id = input('amdin_id');
    	$file = request()->file('titlepic');
    	$info = $file->move( '../../../Uploads/image');
    	if($info){
        ]
    		 $titlepic = $info->getSaveName();
    	}
    	$arr = [
    		'title' => $title,
    		'content' => $content,
    		'describe' => $describe,
    		'titlepic' => $titlepic,
    	];
    	$res = Db::name('title')
			    ->where('title_id', $title_id)
			    ->data($arr)
			    ->update();
		if($res){
			message('200','文章修改成功');
		}else{
			message('1011','文章修改失败');
		}
    }
    public function ad(){
        $data = Db::table('notice')->select();
        if($data){
            message('200','广告展示成功',$data);
        }else{
            message('1023','广告展示失败');
        }
    }
    public function flink(){
        $data = Db::table('flink')->select();
        if($data){
            message('200','友情链接展示成功',$data);
        }else{
            message('1024','友情链接展示失败');
        }
    }
     public function token(){
        $data = input();
        // var_dump($data);die;
        if(empty($data['token']) || !$data['token']){
            message('1012','无token，请重新输入');
        }
        $token_time = Db::table('token')->where('token',$data['token'])->value('time');
        if(!$token_time){
            message('1013','token无效');
        }
        if(($token_time + 2*3600) <=time()){
            message('1014','token已过期，请重新登录');
        }
    }

}

