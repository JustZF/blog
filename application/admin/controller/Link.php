<?php
/**
 * 后台友情链接管理
 * @date:  2018/7/9 21:19
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\admin\controller;

use \app\admin\model\Link as LinkModel;

class Link extends AdminBase {
    /**
     * 友链列表
     * @param int $page [当前页数]
     * @param int $list_row [每页条数]
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function linkList($page = 1, $list_row = 10) {
        $link_m = new LinkModel();
        $link_list = $link_m->getLinkList($page, $list_row);
        $count = $link_m->getLinkTotal();
        $this->assign([
            'link_list' => $link_list,
            'count'     => $count,
            'page'      => $page,
            'list_row'  => $list_row,
        ]);
        return view();
    }

    /**
     * 友链添加
     * @return \think\response\View
     */
    public function linkAdd() {
        if (request()->isPost()) {
            $link_m = new LinkModel();
            $res = $link_m->linkAdd(request()->post());
            if (true === $res) {
                $this->success('添加成功！');
            } else {
                $this->error($res);
            }
        } else {
            return view();
        }
    }

    /**
     * 友链修改
     * @param $link_id [友链id]
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function linkEdit($link_id) {
        $link_m = new LinkModel();
        if (request()->isPost()) {
            $res = $link_m->linkEdit($link_id, request()->post());
            if (true === $res) {
                $this->success('修改成功！');
            } else {
                $this->error($res);
            }
        } else {
            $link = $link_m->find($link_id);
            $this->assign(['link' => $link]);
            return view();
        }
    }

    /**
     * 友链删除
     * @param $link_id [友链id]
     */
    public function linkDel($link_id) {
        $link_m = new LinkModel();
        $res = $link_m->linkDel($link_id);
        if (true === $res) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
}