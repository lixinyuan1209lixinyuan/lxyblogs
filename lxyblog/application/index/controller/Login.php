<?php

namespace app\index\controller;

use app\index\model\User;
use think\Controller;
use think\Request;
use think\Db;
use \phpmailer\PHPMailer;
use \phpmailer\SMTP;


class Login extends Controller
{
    public function index()
    {
        session_start();
        if($_POST){
            $name = input('name');
            $phone = input('phone');
            $email = input('email');
            $Verification = input('Verification');
            $password = md5(input('password'));
            if($_SESSION['code']!=$Verification){
                $this->error('login/index','验证码错误');
                return;
            }
            $arr = [
                'name' =>$name,
                'phone' =>$phone,
                'email' =>$email,
                'password' =>$password,
                'register_time' =>time(),
            ];
            $res = Db::table('admin_user')->insert($arr);
            if($res){
                $this->sendEmail([['user_email'=>$email,'content'=>"您已注册李新元博客"]]);
                Db::table('user')->insert(['username'=>$name,'userpwd'=>$password]);
                $this->success('注册成功，前去登录' , 'login/login');
            }else{
                $this->error('注册失败，重新注册' , 'login/login');
            }
        }else{
            return view();
        }
    }
    public function login()
    {
    	session_start();
    	if($_POST){
            $name = input('name');
    		$password = md5(input('password'));
            // var_dump($data);die;
    		$_SESSION['name'] = $name;
    		$res = Db::table('admin_user')->where(['name'=>$name,'password'=>$password])->find();
            // var_dump($res);die;
            $_SESSION['admin_id'] = $res['admin_id'];
            if($res){
    			echo 1;
    		}else{
    			echo 2;
    		}
    	}else{
    		return view();
    	}
    }
    public function unset(){
		session_start();
		unset($_SESSION['name']);
	}
    public function send(){
        session_start();
        $phone = input('phone');
        $code = rand(1000,9999);
        $_SESSION['code'] = $code;
       
        $data = sendTemplateSMS($phone,array($code,'5'),'1');
    }
    public function sendEmail($data = []) {
          $mail = new PHPMailer(); //实例化
          $mail->IsSMTP(); // 启用SMTP
          $mail->Host = 'smtp.163.com'; //SMTP服务器 以163邮箱为例子 
          $mail->Port = 465;  //邮件发送端口
          $mail->SMTPAuth = true;  //启用SMTP认证
          $mail->SMTPSecure = "ssl";   // 设置安全验证方式为ssl
          $mail->CharSet = "UTF-8"; //字符集
          $mail->Encoding = "base64"; //编码方式
          $mail->Username = '13051093544@163.com';  //你的邮箱 
          $mail->Password = 'lixinyuan1209';  //你的smtp密码 
          $mail->Subject = '资源鸟系统提示'; //邮件标题  
          $mail->From = '13051093544@163.com';  //发件人地址（也就是你的邮箱）
          $mail->FromName = '李新元';  //发件人姓名
          if($data && is_array($data)){
            foreach ($data as $k=>$v){
                    $mail->AddAddress($v['user_email'], "亲"); //添加收件人（地址，昵称）
                    $mail->IsHTML(true); //支持html格式内容
                    $mail->Body = $v['content']; //邮件主体内容
                    //发送成功就删除
                   
            }
        }
    } 
    public function update(){
        return view();
    }
     
}
// 13051093544