<?php
/**
 * 博客前台首页控制器
 * @date:  2018/6/7 23:09
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\home\controller;

class Index extends HomeBase {

    protected $beforeActionList = [
        'nav'        => ['only' => 'index,cat'],
        'link'       => ['only' => 'index,cat'],
        'hotArt'     => ['only' => 'index,cat'],
        'getArtList' => ['only' => 'index'],
        'mood'       => ['only' => 'index,cat'],
    ];

    /**
     * 首页
     * @return \think\response\View
     * @throws \think\exception\DbException
     */
    public function index() {
        $this->getArtList();
        $this->assign(['cat_id' => 0]);
       return view();
    }

    /**
     * 根据栏目获取文章
     * @param $cat_id
     * @return \think\response\View
     * @throws \think\exception\DbException
     */
    public function cat($cat_id) {
        $this->getArtList(1, 5, $cat_id);
        $this->assign(['cat_id' => $cat_id]);
        return view();
    }
}
