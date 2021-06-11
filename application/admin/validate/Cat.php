<?php
/**
 * @date:  2018/7/5 23:17
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\admin\validate;
use app\common\validate\BaseValidate;

class Cat extends BaseValidate {
    protected $rule = [
        'parent_id' => 'require|number',
        'cat_name'  => 'require|mb_length:1,10,UTF-8',
        'content'   => 'require|mb_length:1,500,UTF-8',
        'order'     => 'number|between:0,250',
        'is_show'   => 'in:0,1',
        'deleted'    => 'in:0,1',
    ];

    protected $message = [
        'parent_id'          => '父级栏目设置出错',
        'cat_name.require'   => '栏目名不能为空',
        'cat_name.mb_length' => '栏目名不能超过10个字符',
        'content.require'    => '栏目介绍不能为空',
        'content.mb_length'  => '栏目介绍不能超过50个字符',
        'order'              => '排序只能是0~250之间的整数',
        'is_show.in'         => '显示状态设置错误',
        'deleted.in'          => '删除状态设置错误',
    ];
}