<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use \phpmailer\PHPMailer;
use \phpmailer\SMTP;
class Login extends Controller
{
        
   
    public function login()
    {
        $name = input('name');
        $password = input('password');
        $login = Db::table('admin_user')->where(['name'=>$name,'password'=>$password])->find();
        if($login){
            $data = ['name'=>$name,'password'=>$password];

            $token = md5($password.time());
            $res = Db::table('token')->insert(['token'=>$token,'admin_id'=>$login['admin_id'],'time'=>time()]);
            message('200','登陆成功',$token);
            
        }else{
            $login = Db::table('admin_user')->where('name',$name)->select();
            if($login){
                message('1000','密码错误');
            }else{
                $login = Db::table('admin_user')->where('password',$password)->select();
                if($login){
                    message('1001','账号错误');
                }else{
                    message('1002','账号和密码不存在');
                }
            }
        }

    }
    public function index(){
        // echo 1;die;
        $name = input('name');
        if($name==null){
            message('1003','账号不能为空');
            return;
        }
        $password = input('password');
        if($password==null){
            message('1004','密码不能为空');
            return;
        }
        $email = input('email');
        if($email==null){
            message('1005','邮箱不能为空');
            return;
        }
        $phone = input('phone');
        if($phone==null){
            message('1006','手机号不能为空');
            return;
        }
       
        $data = [
            'name'=>$name,
            'password'=>$password,
            'email'=>$email,
            'phone'=>$phone,
            'register_time'=>time()
        ];
            $res = Db::table('admin_user')->insert($data);
            if($res){
                // echo $email;die;
                $this->sendEmail([['user_email'=>$email,'content'=>'content']]);
                message('200','注册成功',$data);
            }
        }
          function sendEmail($data = []) {

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
                $mail->Subject = 'lixinyuan'; //邮件标题  
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
   
}

