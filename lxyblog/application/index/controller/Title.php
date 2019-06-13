<?php
namespace app\index\controller;
use app\index\model\User;
use think\Controller;
use think\Request;
use think\Db;
class Title extends Controller
{
	public function index(){
		session_start();
		
		$id = input('id');
		$title = Db::table('title')->join('admin_user','title.admin_id=admin_user.admin_id')->where('title.admin_id',$id)->paginate(1);
		$page = $title->render();
		$this->assign('page', $page);
		$this->assign('title',$title);

		return view();
		
	}
}