<?php

public function login(){

       $post = input('post.');

       if(empty($post['user_name']) || !$post['user_name'] || empty($post['password']) || !$post['password']){
            getMsg('406', '用户名和密码不可为空!');
       }

       $password = md5($post['password']);

       $condition = [];

       $condition[] = ['user_name', '=', $post['user_name']];

       $condition[] = ['password', '=', $password];

       $user = U::where($condition)->find();

       if(!$user){

            getMsg('407','登陆失败，此用户不存在!');
       }

       if($user['is_login']!=1){
            getMsg('408','账户未激活，请前往邮箱激活!');
       }

       ksort($post);

       $str = http_build_query($post).time();

       $token = md5($str);
        
       $loginUser = UserToken::where('user_id', $user['id'])->find();

       $tokenData = ['user_id' => $user['id'], 'token' => $token, 'create_time' => time()];

       $tokenUser = new UserToken;

       if($loginUser){

        $tokenUser->allowField(true)->save($tokenData, ['user_id' => $user['id']]);

       }else{
         // 过滤post数组中的非数据表字段数据
        $tokenUser->allowField(true)->save($tokenData);

       }
       
       getMsg('456', '登陆成功!', ['token' => $token]);
    }

    public function checkToken()
    {   
        
        $data = input('request.');
        
        if(empty($data['token']) || !$data['token']){
            getMsg('401','无效访问，请输入token.');
        }

        $tokenTimes = Db::name('user_token')->where('token', $data['token'])->value('create_time');

        if(!$tokenTimes){
            getMsg('407','无效token!');
        }

        $nowTime = time();

        if(($tokenTimes + 3600*2) <= $nowTime){
            getMsg('402', 'token已过期!');
        }
       

    }
