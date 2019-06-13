<?php
namespace app\index\controller;
use app\index\model\User;
use think\Controller;
use think\Request;
use think\Db;
class detailpages extends Controller
{
	public function index(){
		session_start();
		if($_POST){
			$comment = input('comment');
			$submit = input('submit');

			$admin_id = $_SESSION['admin_id'];
			$title_id = $_SESSION['title_id'];
			// echo $title_id;die;
			$data = [
				'admin_id' =>$admin_id,
				'comment' =>$comment,
				'title_id' =>$title_id,
			];
			$comment = Db::table('comment')->insert($data);
			if($comment){
				$this->redirect('detailpages/index');
			}
		}else{
			$title_id = input('title_id');
			$_SESSION['title_id'] = $title_id;
			//浏览量
			$comments = Db::table('title')->where('title_id',$title_id)->setInc('comment'); 

			// 文章详情页
			$title = Db::table('title')->join('admin_user','title.admin_id=admin_user.admin_id')->where('title_id',$title_id)->select();
			$this->assign('title',$title);

			// 热门推荐
	   		$hot = Db::table('title')->where('status',0)->order('comment','desc')->limit(10)->select();
	   		$this->assign('hot',$hot);

			// 评论区
			$comment = Db::table('comment')->join('admin_user','comment.admin_id=admin_user.admin_id')->where('title_id',$title_id)->select();
			$this->assign('comment',$comment);

			// 博主信息
			$bloger = Db::table('title')->join('admin_user','title.admin_id=admin_user.admin_id')->where('title_id',$title_id)->select();
			$bloger_comment  =Db::table('concem')->where('admin_id',$bloger[0]['admin_id'])->select();

			$etc = Db::table('etc')->where('bloger',$bloger[0]['admin_id'])->select();
			// echo "<pre>";
			// var_dump($etc);die;	
			$this->assign('etc',$etc);
			$this->assign('bloger',$bloger);
			$this->assign('bloger_comment',$bloger_comment);
			return view();
		}
	}
	public function concem($title_id){
		session_start();
		if(!isset($_SESSION['admin_id'])){
			return "您还没有登录不能关注，请先登录";
		}
		$concem = Db::table('concem')->where('title_id',$_SESSION['title_id'])->select();
		if($concem){
			return "您已关注";
		}
		$admin_id = $_SESSION['admin_id'];
		$data = [
			'title_id'=>$title_id,
			'admin_id'=>$admin_id
		];
		$res = Db::table('concem')->insert($data);
		if($res){
			return '关注成功';
		}else{
			return '关注失败';
		}
	}
	public function comment(){
		session_start();
		if(!isset($_SESSION['admin_id'])){
			return 3;
		}
		$admin_id = $_SESSION['admin_id'];
		$title_id = $_POST['title_id'];
		$comment = $_POST['comment'];
		if($comment==''){
			return 0;
		}
		$data = [
			'admin_id' =>$admin_id,
			'title_id' =>$title_id,
			'comment'=>$comment,
			'time' =>time()
		];	
		$data = Db::table('comment')->insert($data);
		if($data){
			return 1;
		}else{
			return 2;
		}
	}
	public function bloger($bloger){
		session_start();
		$admin_id = $_SESSION['admin_id'];
		$data = Db::table('etc')->where('admin_id',$admin_id)->select();
		if($data){
			echo 0;return ;
		}

		$res = Db::table('etc')->insert(['bloger'=>$bloger,'admin_id'=>$admin_id]);
		if($res){
			return 1;
		}else{
			return 2;
		}
	}
	public function collect($title_id){
		session_start();
		$admin_id = $_SESSION['admin_id'];
		$data = Db::table('collect')->where(['title_id'=>$title_id,'admin_id'=>$admin_id])->select();
		if($data){
			echo 0;return ;
		}

		$res = Db::table('collect')->insert(['title_id'=>$title_id,'admin_id'=>$admin_id]);
		if($res){
			return 1;
		}else{
			return 2;
		}
	}
	public function click(){
		session_start();
		if(!isset($_SESSION['admin_id'])){
			return "您还没有登录不能点赞，请先登录";
		}
		$title_id = input('title_id');
		$admin_id = $_SESSION['admin_id'];
		$data = Db::table('click')->where(['title_id'=>$title_id,'admin_id'=>$admin_id])->select();
		// var_dump($data);die;
		if($data){
			return 1;
		}else{
			Db::table('click')->insert(['title_id'=>$title_id,'admin_id'=>$admin_id]);
			$res = Db::table('title')->where('title_id',$title_id)->setInc('visibility');
			return 0;
		}
	}
}
