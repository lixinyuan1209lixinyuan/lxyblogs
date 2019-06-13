<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
class Detailpages extends Controller
{
	protected $beforeActionList = [
             'token' =>  ['except'=>'concem,bloger,click,views,hot'],
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
			message('1018','没有title_id');
		}
		$pages = Db::table('title')->where('title_id',$title_id)->find(); 
		if($pages){
			message('200','展示成功',$pages);
		}
	}
	public function concem(){
		$admin_id = input('admin_id');
		$title_id = input('title_id');

		$arr = [
			'title_id'=>$title_id,
			'admin_id'=>$admin_id
		];
		$data = Db::table('concem')->where($arr)->find();
		if($data){
			message('1020','您已收藏过此文章');
			return;
		}
		$res = Db::table('concem')->insert($arr);
		if($res){
			message('200','收藏成功');
		}else{
			message('1019','收藏失败');
		}
	}
	public function click(){
		$admin_id = input('admin_id');
		$title_id = input('title_id');

		$arr = [
			'title_id'=>$title_id,
			'admin_id'=>$admin_id
		];
		$data = Db::table('click')->where($arr)->find();
		if($data){
			message('1022','您已为此文章点过赞');
			return;
		}
		$res = Db::table('click')->insert($arr);
		if($res){
			message('200','点赞成功');
		}else{
			message('1021','点赞失败');
		}
	}
	public function bloger(){
		$admin_id = input('admin_id');
		$bloger = input('bloger');
		$data = Db::table('etc')->where(['admin_id'=>$admin_id,'bloger'=>$bloger])->select();
		if($data){
			message('1026','您已关注此博主');
			return;
		}
		$res = Db::table('etc')->insert(['admin_id'=>$admin_id,'bloger'=>$bloger]);
		if($res){
			message('200','关注博主成功');
		}else{
			message('1025','关注博主失败');
		}
	}
	public function views(){
		$title_id = input('title_id');
		$data = Db::table('title')->where('title_id',$title_id)->setInc('comment');
		if($data){
			message('200','浏览加1');
		}else{
			message('1027','浏览失败');
		}
	}
	public function hot(){
		$hot = Db::table('title')->where('status',0)->order('comment','desc')->limit(10)->select();
		if($hot){
			message('200','这是热门信息',$hot);
		}else{
			message('1028','没有热门信息');
		}
	}
}