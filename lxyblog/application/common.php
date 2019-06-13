<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use app\REST;

 function sendTemplateSMS($to,$datas,$tempId)
{
      $accountSid= '8a216da8679d0e9d0167aa60a0240834';

      //主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
      $accountToken= '45e12a45a5dc4e64830e065c7a90510e';

      //应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
      //在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
      $appId='8a216da8679d0e9d0167aa60a071083a';

      //请求地址
      //沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
      //生产环境（用户应用上线使用）：app.cloopen.com
      $serverIP='sandboxapp.cloopen.com';


      //请求端口，生产环境和沙盒环境一致
      $serverPort='8883';

      //REST版本号，在官网文档REST介绍中获得。
      $softVersion='2013-12-26';
     // 初始化REST SDK
     $rest = new REST($serverIP,$serverPort,$softVersion);
     $rest->setAccount($accountSid,$accountToken);
     $rest->setAppId($appId);
    
     // 发送模板短信
     echo "Sending TemplateSMS to $to <br/>";
     $result = $rest->sendTemplateSMS($to,$datas,$tempId);
     if($result == NULL ) {
         echo "result error!";
         return false;
     }
     if($result->statusCode!=0) {
         echo "error code :" . $result->statusCode . "<br>";
         echo "error msg :" . $result->statusMsg . "<br>";
         //TODO 添加错误处理逻辑
         return false;
     }else{
         echo "Sendind TemplateSMS success!<br/>";
         // 获取返回信息
         return false;
         $smsmessage = $result->TemplateSMS;
         echo "dateCreated:".$smsmessage->dateCreated."<br/>";
         echo "smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
         //TODO 添加成功处理逻辑
     }
}

	function message($code='200',$msg='success',$data=[]){
		echo json_encode(['code'=>$code,'msg'=>$msg,'data'=>$data],JSON_UNESCAPED_UNICODE);
	}

	