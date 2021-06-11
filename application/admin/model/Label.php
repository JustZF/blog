<?php
/**
 * @date:  2018/6/28 0:09
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\admin\model;

use \app\admin\validate\Label as LabelValidate;
use app\common\model\BaseModel;

class Label extends BaseModel {
    /**
     * 获取标签列表
     * @param int $page [当前页]
     * @param int $list_row [每页条数]
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getLabelList($page = null, $list_row = null) {
        $label_m = $this;
        if (isset($page) && isset($list_row)) {
            $label_m = $label_m->limit(($page - 1) * $list_row, $list_row);
        }
        return $label_m->where(['deleted' => 0])->order('order,id desc')->select();
    }

    /**
     * 获取标签总数
     * @return int|string
     */
    public function getLabelTotal() {
        return $this->where(['deleted' => 0])->count(1);
    }

    /**
     * 通过id获取标签信息
     * @param $label_id [标签id]
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getLabelById($label_id) {
        return $this->find($label_id);
    }

    /**
     * 添加标签
     * @param array $data [标签数据]
     * @return array|bool|string
     */
    public function labelAdd($data) {
        $label_v = new LabelValidate();
        if (!$label_v->check($data)) {
            return $label_v->getError();
        }
        return $this->save($data) ? true : '添加失败';
    }

    /**
     * 标签修改
     * @param int $label_id [标签id]
     * @param array $data [修改数据]
     * @return array|bool|string
     */
    public function labelEdit($label_id, $data) {
        $label_v = new LabelValidate();
        if (!$label_v->check($data)) {
            return $label_v->getError();
        }
        return $this->where(['id' => $label_id])->update($data) ? true : '修改失败，可能您未进行任何修改';
    }

    public function labelDel($label_id) {
        return $this->where(['id' => $label_id])->update(['deleted' => 1]) ? true : '删除失败';
    }
}