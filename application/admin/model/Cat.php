<?php
/**
 * @date:  2018/6/27 23:35
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\admin\model;

use \app\admin\validate\Cat as CatValidate;
use app\common\model\BaseModel;

class Cat extends BaseModel {

    protected $table = 'blog_article_cate';
    /**
     * 获取栏目列表
     * @param int page [当前页数]
     * @param int $list_row [每页条数]
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getCatList($page = null, $list_row = null) {
        if (isset($page) && isset($list_row)) {
            $this->limit(($page - 1) * $list_row, $list_row);
        }
        $cat_list = $this->where(['deleted' => 0, 'pid' => 0])->order('sort,id desc')->select();
        foreach ($cat_list as $k => $v) {
            $cat_list[$k]['child'] = $this->getChildList($v['id']);
        }
        return $cat_list;
    }

    /**
     * 存储过程方式获取栏目列表
     * @param int $page [当前页]
     * @param int $list_row [每页条数]
     * @return array|false
     */
//    public function getCatListByPro($page, $list_row) {
//        $res = $this->call('CALL sp_getCatTreeList(?,?)', [($page - 1) * $list_row, $list_row]);
//        $cat_list = [];
//        foreach ($res as $k => $v) {
//            $v = (array)$v;
//            if ($v['level'] == 1) {
//                $cat_list[$v['id']] = $v;
//            } else {
//                $cat_list[$v['parent_id']]['child'][] = $v;
//            }
//        }
//        return $cat_list;
//    }

    /**
     * 通过栏目id获取子栏目
     * @param $cat_id [父级栏目id]
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getChildList($cat_id) {
        return $this->where(['deleted' => 0, 'pid' => $cat_id])->order('sort,id desc')->select();
    }

    /**
     * 获取栏目总数
     * @return int|string
     */
    public function getCatTotal() {
        return $this->where(['deleted' => 0])->count(1);
    }

    /**
     * 根据栏目id获取信息
     * @param $cat_id [栏目id]
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getCatById($cat_id) {
        return $this->where(['deleted' => 0])->find($cat_id);
    }

    /**
     * 添加栏目
     * @param $data [栏目数据]
     * @return array|bool|string
     */
    public function catAdd($data) {
        $cat_v = new CatValidate();
        if (!$cat_v->check($data)) {
            return $cat_v->getError();
        } else {
            return $this->save($data) ? true : '添加失败';
        }
    }

    /**
     * 栏目修改
     * @param $cat_id [栏目id]
     * @param $data [修改数据]
     * @return array|bool|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function catEdit($cat_id, $data) {
        $cat_v = new CatValidate();
        if (!$cat_v->check($data)) {
            return $cat_v->getError();
        }
        if ($data['parent_id'] != 0 && $this->getChildList($cat_id)) {
            return '当前栏目下有二级栏目，无法设置为二级栏目';
        }
        return $this->where(['id' => $cat_id, 'deleted' => 0,])->update($data) ? true : '保存失败，可能未进行任何修改!';
    }

    /**
     * 栏目删除
     * @param $cat_id [栏目id]
     * @return bool|string
     */
    public function catDel($cat_id) {
        return $this->where(['id' => $cat_id])->update(['deleted' => 1]) ? true : '删除失败';
    }
}