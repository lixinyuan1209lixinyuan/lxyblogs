<?php
namespace app\index\controller;
use app\index\model\User;
use think\Controller;
use think\Request;
use think\Db;
// use app\index\validate\Personal as Articlevalidate;
class Personal extends Controller
{
	public function index(){
		session_start();
			// 收藏
			if(!isset($_SESSION['admin_id'])){
				$this->error('您还没有的登录');
				return;
			}
			$admin_id = $_SESSION['admin_id'];
			// echo $admin_id;die;
			$collect = Db::table('collect')->join('title','title.title_id=collect.title_id')->where("collect.admin_id",$admin_id)->select();
			if(count($collect)>0){
				$sum = Db::table('collect')->where('title_id','in',$collect[0]['title_id'])->select();
				$this->assign('sum',$sum);
			}
			$this->assign('collect',$collect);

			// 我的评论
			$comment = Db::table('comment')->join('title','comment.title_id=title.title_id')->where('comment.admin_id',$admin_id)->select();
			$this->assign('comment',$comment);

			// 关注的博主
			$bloger = Db::table('etc')->join('admin_user','etc.bloger=admin_user.admin_id')->where('etc.admin_id',$admin_id)->select();
			$this->assign('bloger',$bloger);

			//个人信息
			$info = Db::table('admin_user')->where('admin_id',$admin_id)->find();
			$this->assign('info',$info);

			return view();
	}
	public function add(){
		session_start();
		if($_POST){
			$data = input();
			var_dump($data);die;
			$data = Db::table('admin_user')->where('admin_id',$_SESSION['admin_id'])->update($data);
			if($data){
				$this->redirect('personal/index');
			}

		}
	}
	public function show(){
		session_start();
		 if($_POST){
            $title = input('title');
            $content = input('content');
            $describe = input('describe');
            $visibility = input('visibility');
            $titlepic = input('titlepic');
            $admin_id = $_SESSION['admin_id'];
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

            

            $res = Db::table('title')->insert($arr);
            if($res){
                $this->success("文章添加成功" , 'personal/index');
            }else{
                $this->success("文章添加失败" , 'personal/index');

            }
        }else{
			return view();
        }
	}
}
   