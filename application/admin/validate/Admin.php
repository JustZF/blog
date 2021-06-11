<?php
/**
 * @date:  2018/8/21 14:31
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\admin\validate;

use app\common\validate\BaseValidate;

class Admin extends BaseValidate {
    protected $rule = [
        'name'     => 'max:20',
        'username' => 'max:20',
        'password' => 'length:32',
        'salt'     => 'max:6',
        'email'    => 'email|max:50',
    ];

    protected $message = [
        'name.max'        => '姓名过长',
        'username.max'    => '用户名过长',
        'password.length' => '密码数据错误',
        'salt.max'        => '数据错误1001',
        'email.email'     => '邮箱格式错误',
        'email.max'       => '邮箱地址过长',
    ];
}