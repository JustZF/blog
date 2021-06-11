<?php
/**
 * @date:  2018/8/7 13:47
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\home\validate;

use app\common\validate\BaseValidate;

class User extends BaseValidate {
    protected $rule = [
        'username'          => 'unique:user',
        'password'          => 'length:32|alphaDash',
        'email'             => 'email',
        'nickname'          => 'length:1,20',
        'head_img'          => 'max:100',
        'github_id'         => 'unique:user|number',
        'github_login'      => 'max:50',
        'github_name'       => 'max:30',
        'github_avatar_url' => 'max:100',
    ];

    protected $message = [
        'username.unique'  => '用户名已存在',
        'password'         => '密码由字母和数字，下划线_ 及破折号-组成',
        'email.unique'     => '该邮箱已被使用',
        'email.email'      => '邮箱格式不正确',
        'nickname'         => '昵称错误',
        'github_id.unique' => '该GitHub账号已被绑定',
    ];
}