<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
class Comment extends Controller
{
	protected $beforeActionList = [

         'token' =>  ['except'=>'index'],
    ];
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
	public function index(){
		$title_id = input('title_id');
		if(isset($title_id)){
			message('1015','文章title_id不能为空');
			return;
		}
		$comment = Db::table('comment')->where('title_id',$title_id)->select();
		if($comment){
			message('200','评论显示成功');
		}
	}
	public function add(){
		$title_id = input('title_id');
		$admin_id = input('admin_id');
		if(empty($admin_id)){
			message('1016','您还没有登录');
		}
		$comment = input('comment');
		if(empty($comment)){
			message('1017','您还没有写评论，请填写');
		}
		$arr = [
			'title_id' => $title_id,
			'admin_id' => $admin_id,
			'comment' => $comment,
			'time' => time(),
		];
		$res = Db::table('comment')->insert($arr);
		if($res){
			message('200','评论添加成功');
		}
	}
}