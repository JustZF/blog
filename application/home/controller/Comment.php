<?php
/**
 * @date:  2018/8/17 22:36
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\home\controller;

use app\home\model\Comment as CommentModel;

class Comment extends HomeBase {
    protected $beforeActionList = [
        'nav'            => ['only' => 'leaveword'],
        'link'           => ['only' => 'leaveword'],
        'hotArt'         => ['only' => 'leaveword'],
        'getCommentList' => ['only' => 'leaveword'],
        'isLogin'        => ['only' => 'commentadd'],
    ];

    /**
     * 留言页
     * @return \think\response\View
     */
    public function leaveWord() {
        return view();
    }

    /**
     * 添加留言
     */
    public function commentAdd() {
        if (request()->isPost()) {
            $data = request()->post(null, null, 'htmlspecialchars');
            $data['user_id'] = session('user_id');
            $comment_m = new CommentModel();
            $res = $comment_m->commentAdd($data);
            if (true === $res) {
                $this->success('留言成功');
            } else {
                $this->error($res);
            }
        }
    }

    /**
     * 获取评论列表
     * @param int $page
     * @param int $list_row
     * @param int $art_id
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function commentList($page = 1, $list_row = 5, $art_id = 0) {
        $this->getCommentList($page, $list_row, $art_id);
        return view();
    }
}