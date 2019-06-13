<?php
namespace app\index\controller;
use app\index\model\User;
use think\Controller;
use think\Request;
use think\Db;
use think\facade\Cache;
class Index extends Controller
{
    public function index()
    {
    	session_start();
   		// 广告轮播图
   		$notice = Db::table("notice")->select();
   		$this->assign('notice',$notice);

   		// 最新发布
   		$title_newtime = Db::table('title')->join('admin_user','title.admin_id=admin_user.admin_id')->where('title.status',0)->order('title.time','desc')->paginate(10);
   		$page = $title_newtime->render();
   		$this->assign('page', $page);
   		$this->assign('title_newtime',$title_newtime);

   		// 博主信息


   		// 热门推荐
   		$hot = Db::table('title')->where('status',0)->order('comment','desc')->limit(10)->select();
   		$this->assign('hot',$hot);

   		// 点击量
   		$visibility = Db::table('title')->where('status',0)->order('visibility','desc')->limit(10)->select();
   		$this->assign('visibility',$visibility);

   		// 友情链接
   		$flink = Db::table('flink')->select();
   		$this->assign('flink',$flink);
        return view();
    }
    public function res(){
      Cache::store('redis')->set('name','value');
      echo Cache::get('name');

    }
}

