<?php
namespace app\admin\validate;

use think\Validate;

class Flink extends Validate
{
	protected $url = [
		'mame' => 'require',
		'url' => 'require',
	];
	protected $message = [
		'name.require' => '链接名称不能空',
		'url.require' => '链接不能为空'
	];
}