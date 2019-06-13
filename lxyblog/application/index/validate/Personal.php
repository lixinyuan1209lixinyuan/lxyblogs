<?php
namespace app\admin\validate;

use think\Validate;

class Personal extends Validate
{
    protected $rule = [
      'title'  => 'require',
      'content'   => 'min:20',
      'titlepic' => 'require',
      'describe' => 'require',
    ];
	protected $message = [
		'title.require' => '名称必须',
		'content.min'     => '文章内容不能少于20',
		'titlepic.require'   => '图片不能为空',
		'describe.require'  => '描述不能为空'
	];
}
