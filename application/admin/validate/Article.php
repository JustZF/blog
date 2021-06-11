<?php
/**
 * @date:  2018/6/30 8:32
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\admin\validate;

use app\common\validate\BaseValidate;

class Article extends BaseValidate {
    protected $rule = [
        'title'     => 'require|mb_length:1,100,UTF-8',
        'author'    => 'require|mb_length:1,10,UTF-8',
        'cat_id'    => 'require|number',
        'label_ids' => 'require|array',
        'img_url'   => 'require|length:1,150',
        'is_hot'    => 'in:0,1',
        'is_show'   => 'in:0,1',
        'deleted'    => 'in:0,1',
        'content'   => 'require',
    ];

    protected $message = [
        'title.require'    => '文章标题必须填',
        'title.mb_length'  => '文章标题不能超过100个字符',
        'author.require'   => '作者必须填',
        'author.mb_length' => '作者不能超过10个字符',
        'cat_id'           => '栏目设置出错',
        'label_ids'        => '标签设置出错',
        'img_url.require'  => '图片链接不能为空',
        'img_url.length'   => '图片链接不能超过150个字符',
        'is_hot'           => '热门设置失败',
        'is_show'          => '显示设置失败',
        'deleted'           => '删除设置失败',
        'content'          => '文章内容必须填',
    ];
}