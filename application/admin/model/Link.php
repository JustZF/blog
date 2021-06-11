<?php
/**
 * @date:  2018/7/9 21:21
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\admin\model;

use \app\admin\validate\Link as LinkValidate;
use app\common\model\BaseModel;

class Link extends BaseModel {
    /**
     * 获取友链列表
     * @param $page [当前页]
     * @param $list_row [每页条数]
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getLinkList($page = null, $list_row = null) {
        if (isset($page) && isset($list_row)) {
            $this->limit(($page - 1) * $list_row, $list_row);
        }
        return $this->where(['is_del' => 0])->order('order asc')->select();
    }

    /**
     * 友链添加
     * @param $data [数据数组]
     * @return array|bool|string
     */
    public function linkAdd($data) {
        $link_v = new LinkValidate();
        if (!$link_v->check($data)) {
            return $link_v->getError();
        }
        return $this->save($data) ? true : '数据保存失败';
    }

    /**
     * 友链修改
     * @param $link_id [友链id]
     * @param $data [数据数组]
     * @return array|bool|string
     */
    public function linkEdit($link_id, $data) {
        $link_v = new LinkValidate();
        if (!$link_v->check($data)) {
            return $link_v->getError();
        }
        return $this->where(['deleted' => 0, 'id' => $link_id])->update($data) ? true : '修改失败！';
    }

    /**
     * 友链删除
     * @param $link_id [友链id]
     * @return bool|string
     */
    public function linkDel($link_id) {
        return $this->where(['id' => $link_id])->update(['deleted' => 1]) ? true : '删除失败！';
    }

    /**
     * 获取友链总数
     * @return int|string
     */
    public function getLinkTotal() {
        return $this->where(['deleted' => 0])->count(1);
    }
}