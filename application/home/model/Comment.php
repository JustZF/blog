<?php
/**
 * @date:  2018/8/17 23:06
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\home\model;

use app\common\model\BaseModel;
use app\home\validate\Comment as CommentValidate;

class Comment extends BaseModel {
    protected $dateFormat = false;

    /**
     * 获取评论列表
     * @param $page
     * @param $list_row
     * @param $where
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getCommentList($page, $list_row, $where) {
        $comment_list = $this->field('a.*,b.nickname as nickname,b.head_img as head_img,c.nickname as to_nickname,c.head_img as to_head_img')->alias('a')->where('is_del', 0)->join('user b', 'a.user_id=b.id', 'left')->join('user c', 'a.to_user_id=c.id', 'left')->where('parent_id', 0)->where($where)->order('a.id desc')->limit(($page - 1) * $list_row, $list_row)->select();
        foreach ($comment_list as $k => $v) {
            $comment_list[$k]['child'] = $this->getChildList($v['id']);
        }
        return $comment_list;
    }

    /**
     * 获取回复列表
     * @param $comment_id
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getChildList($comment_id) {
        return $this->field('a.*,b.nickname as nickname,b.head_img as head_img,c.nickname as to_nickname,c.head_img as to_head_img')->alias('a')->join('user b', 'a.user_id=b.id', 'left')->join('user c', 'a.to_user_id=c.id', 'left')->where([
            'is_del'    => 0,
            'parent_id' => $comment_id,
        ])->order('id')->select();
    }

    /**
     * 获取评论总条数
     * @param $where
     * @return int|string
     */
    public function getCommentTotal($where) {
        return $this->where(['is_del' => 0, 'parent_id' => 0])->where($where)->count(1);
    }

    /**
     * 添加评论
     * @param $data
     * @return array|bool|string
     */
    public function commentAdd($data) {
        $comment_v = new CommentValidate();
        if (!$comment_v->check($data)) {
            return $comment_v->getError();
        }
        return $this->data($data)->save() ? true : '数据保存失败';
    }
}