<?php
/**
 * @date:  2018/8/17 23:06
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\home\validate;

use app\common\validate\BaseValidate;

class Comment extends BaseValidate {
    protected $rule = [
        'user_id'    => 'require|number',
        'to_user_id' => 'number',
        'parent_id'  => 'number',
        'art_id'     => 'number',
        'content'    => 'require|max:250',
    ];

    protected $message = [
        'user_id'         => '请先登录',
        'to_user_id'      => '回复人错误',
        'parent_id'       => '数据出错1001',
        'art_id'          => '数据出错1002',
        'content.require' => '评论不能为空',
        'content.max'     => '评论内容过长',
    ];
}