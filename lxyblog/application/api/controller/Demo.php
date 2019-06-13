<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
class Demo extends Controller
{
	public function demo(){
		$admin_id = input('admin_id');
		$res = Db::table('admin_user')->where('admin_id',$admin_id)->find();
		if($res){
			message('200','这是您的信息',$res);
		}else{
			message('1029','您还没有登录');
		}
	}
	public function update(){
		$admin_id = input('admin_id');
		$arr = input();
		$data = Db::table('admin_user')->where('admin_id',$admin_id)->update($arr);
		if($data){
			message('200','修改信息成功');
		}else{
			message('1030','修改信息失败');
		}
	}
	public function comment(){
		$admin_id = input('admin_id');
		$res = Db::table('comment')->join('title','comment.title_id=title.title_id')->where('comment.admin_id',$admin_id)->select();
		if($res){
			message('200','这是您评论过的文章',$data);
		}
	}
	public function blogers(){
		$admin_id = input('admin_id');

		$bloger = Db::table('etc')->join('admin_user','etc.bloger=admin_user.admin_id')->where('etc.admin_id',$admin_id)->select();
		if($bloger){
			message('200','这是您关注过的博主',$bloger);
		}
	}
}