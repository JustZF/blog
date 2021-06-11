<?php
/**
 * @date:  2018/7/4 22:14
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\admin\controller;

use app\admin\model\Cat as CatModel;

class Cat extends AdminBase {
    /**
     * 栏目列表
     * @param int $page [当前页数]
     * @param int $list_row [每页条数]
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function catList($page = 1, $list_row = 10) {
        $cat_m = new CatModel();
        $cat_list = $cat_m->getCatList($page, $list_row);
        $count = $cat_m->getCatTotal();
        $this->assign([
            'cat_list' => $cat_list,
            'count'    => $count,
            'page'     => $page,
            'list_row' => $list_row,
        ]);
        return view();
    }

    /**
     * 添加栏目
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function catAdd() {
        $cat_m = new CatModel();
        if (request()->isPost()) {
            $res = $cat_m->catAdd(request()->post());
            if (true === $res) {
                $this->success('添加成功');
            } else {
                $this->error($res);
            }
        } else {
            $cat_list = $cat_m->getCatList();
            $this->assign(['cat_list' => $cat_list]);
            return view();
        }
    }

    /**
     * 栏目修改
     * @param $cat_id [栏目id]
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function catEdit($cat_id) {
        $cat_m = new CatModel();
        if (request()->isPost()) {
            $res = $cat_m->catEdit($cat_id, request()->post());
            if (true === $res) {
                $this->success('修改成功');
            } else {
                $this->error($res);
            }
        } else {
            $cat = $cat_m->getCatById($cat_id);
            $cat_list = $cat_m->getCatList();
            $this->assign(['cat' => $cat, 'cat_list' => $cat_list]);
            return view();
        }
    }

    /**
     * 栏目删除
     * @param $cat_id [栏目id]
     */
    public function catDel($cat_id) {
        $cat_m = new CatModel();
        $res = $cat_m->catDel($cat_id);
        if (true === $res) {
            $this->success('删除成功');
        } else {
            $this->error($res);
        }
    }
}