<?php
/**
 * @date:  2018/7/9 21:21
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\admin\validate;

use app\common\validate\BaseValidate;

class Link extends BaseValidate {
    protected $rule = [
        'link_name' => 'require|mb_length:1,10,UTF-8',
        'link_url'  => 'require|max:100|url',
        'order'     => 'number|between:0,250',
        'is_show'   => 'in:0,1',
        'deleted'    => 'in:0,1',
    ];

    protected $message = [
        'link_name.require'   => '链接名不能为空',
        'link_name.mb_length' => '链接名不能超过10个字符',
        'link_url.require'    => '链接地址不能为空',
        'link_url.max'        => '链接不能超过100个字符',
        'link_url.url'        => '链接格式错误',
        'order'               => '排序必须是0-250之间的整数',
        'is_show'             => '显示设置出错',
        'deleted'              => '删除操作失败',
    ];
}