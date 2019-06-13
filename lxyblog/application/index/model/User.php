<?php
namespace app\index\model;
use think\Model;
use think\Request;
class User extends Model
{
	protected $table = 'user';
	public function addUser()
	{
		$user = new User;
		$data = input('post.');
		$user->data = $data;
		if($user->save()){
			return true;
		}else{
			return false;
		}
	}
}


