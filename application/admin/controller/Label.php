<?php
/**
 * @date:  2018/7/7 13:29
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\admin\controller;

use \app\admin\model\Label as LabelModel;

class Label extends AdminBase {
    public function labelList($page = 1, $list_row = 10) {
        $label_m = new LabelModel();
        $label_list = $label_m->getLabelList($page, $list_row);
        $count = $label_m->getLabelTotal();
        $this->assign([
            'label_list' => $label_list,
            'count'      => $count,
            'page'       => $page,
            'list_row'   => $list_row,
        ]);
        return view();
    }

    public function labelEdit($label_id) {
        $label_m = new LabelModel();
        if (request()->isPost()) {
            $res = $label_m->labelEdit($label_id, request()->post());
            if (true === $res) {
                $this->success('修改成功');
            } else {
                $this->error($res);
            }
        } else {
            $label = $label_m->getLabelById($label_id);
            $this->assign(['label' => $label]);
            return view();
        }
    }

    public function labelAdd() {
        if (request()->isPost()) {
            $label_m = new LabelModel();
            $res = $label_m->labelAdd(request()->post());
            if (true === $res) {
                $this->success('添加成功');
            } else {
                $this->error($res);
            }
        } else {
            return view();
        }
    }

    public function labelDel($label_id) {
        $label_m = new LabelModel();
        $res = $label_m->labelDel($label_id);
        if (true === $res) {
            $this->success('删除成功');
        } else {
            return $this->error($res);
        }
    }
}