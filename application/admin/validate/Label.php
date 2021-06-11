<?php
/**
 * @date:  2018/7/7 13:29
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\admin\validate;
use app\common\validate\BaseValidate;

class Label extends BaseValidate {
    protected $rule = [
        'label_name' => 'require|mb_length:1,10,UTF-8',
        'order'      => 'number|between:0,250',
        'is_show'    => 'in:0,1',
    ];

    protected $message = [
        'label_name.require'   => '标签名必须填',
        'label_name.mb_length' => '标签名不能超过10个字符',
        'order'                => '排序必须是0-250的整数',
        'is_show'              => '显示状态错误',
    ];
}