<?php
/**
 * 文章-标签对应关系模型
 * @date:  2018/7/25 23:19
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\admin\model;

use app\common\model\BaseModel;
use think\exception\DbException;

class ArticleLabel extends BaseModel {
    protected $autoWriteTimestamp = false;

    /**
     * 通过文章id获取对应标签id
     * @param $art_id [文章id]
     * @return array
     */
    public function getLabelIdsByArtId($art_id) {
        return $this->where(['art_id' => $art_id])->column('label_id');
    }

    /**
     * 通过文章id获取对应的标签id和标签名
     * @param int $art_id [文章id]
     * @return false|\PDOStatement|string|\think\Collection
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getLabelNamesByArtId($art_id) {
        return $this->field('label_id,label_name')->alias('a')->join('label b', 'a.label_id=b.id', 'LEFT')->where(['art_id' => $art_id])->order('order')->select();
    }

    /**
     * 修改文章标签关联关系
     * @param int $art_id [文章id]
     * @param array $label_ids [标签id数组]
     * @return bool
     */
    public function artLabelEdit($art_id, $label_ids) {
        $res_del = $this->where('art_id', $art_id)->whereNotIn('label_id', $label_ids)->delete();
        $res_add = $this->artLabelsAdd($art_id, $label_ids);
        return $res_del || $res_add;
    }

    /**
     * 文章标签对应关系添加
     * @param int $art_id [文章id]
     * @param array $label_ids [标签id数组]
     * @return bool
     */
    public function artLabelsAdd($art_id, $label_ids) {
        $res_add = false;
        foreach ($label_ids as $label_id) {
            try {
                $this->insert(['art_id' => $art_id, 'label_id' => $label_id]);
                $res_add = true;
            } catch (DbException $e) {
                //echo $e->getMessage();
            }
        }
        return $res_add;
    }
}