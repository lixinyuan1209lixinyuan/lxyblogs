<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

// 登录
Route::get('login','api/login/login');

// 注册
Route::post('register','api/login/index');

// 评论
Route::get('comment_index','api/comment/index');
Route::post('comment_add','api/comment/add');

//广告
Route::post('ad','api/index/ad');
Route::post('flink','api/index/flink');

//文章 增 改 查
Route::post('title_add','api/index/add');
Route::get('title_index','api/index/index');
Route::put('title_update','api/index/update');

//静态页
Route::post('detailpages_index','api/detailpages/index');
Route::post('detailpages_concem','api/detailpages/concem');
Route::post('detailpages_click','api/detailpages/click');
Route::post('detailpages_bloger','api/detailpages/bloger');
Route::post('detailpages_views','api/detailpages/views');
Route::post('detailpages_hot','api/detailpages/hot');

//个人信息
Route::get('demo','api/demo/demo');
Route::put('demo_update','api/demo/update');
Route::put('demo_comment','api/demo/comment');
Route::put('demo_bloger','api/demo/blogers');


return [

];
