<?php
/**
 * 前台基础控制器
 * @date:  2018/6/7 23:09
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\home\controller;

use app\admin\model\Link as LinkModel;
use app\admin\model\Cat as CatModel;
use app\admin\model\Article as ArticleModel;
use app\admin\model\Mood as MoodModel;
use app\home\model\Comment as CommentModel;
use app\common\controller\BaseController;
use think\Request;
use think\Db;
class HomeBase extends BaseController {
    public function __construct(Request $request = null) {
        //$request = Request::instance();
        // $data['ip'] = $request->ip();
        // $data['url'] = $request->url();
        // $data['time'] = date('Y-m-d H:i:s', time());
        // Db::name('log')->insert($data);
        parent::__construct($request);
    }

    /**
     * 检查登录
     */
    protected function isLogin() {
        if (!session('user_id')) {
            $this->error('请先登录');
        }
    }

    /**
     * 获取个人标签
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function mood() {
        $cat_m = new MoodModel();
        $cat_list = $cat_m->getMoodList();
        $this->assign(['mood_list' => $cat_list]);
    }

    /**
     * 获取导航栏数据
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function nav() {
        $cat_m = new CatModel();
        $cat_list = $cat_m->getCatList();
        $this->assign(['cat_list' => $cat_list]);
    }

    /**
     * 获取友情链接数据
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function link() {
        $link_m = new LinkModel();
        $link_list = $link_m->getLinkList();
        $this->assign(['link_list' => $link_list]);
    }

    /**
     * 获取推荐文章
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function hotArt() {
        $art_m = new ArticleModel();
        $hot_list = $art_m->getHotArt();
        $this->assign(['hot_list' => $hot_list]);
    }

    /**
     * 获取文章列表
     * @param int $page
     * @param int $list_row
     * @param null $cat_id
     * @param null $label_id
     * @param null $title
     * @throws \think\exception\DbException
     */
    protected function getArtList($page = 1, $list_row = 5, $cat_id = null, $label_id = null, $title = null) {
        $art_m = new ArticleModel();
        $where = ['a.status' => 1];
        if (isset($cat_id)) {
            $child = (new CatModel())->where('pid', $cat_id)->column('id');
            $child[] = $cat_id;
            $where['cat_id'] = ['in', $child];
        }
        isset($label_id) ? $where['label_id'] = $label_id : null;
        isset($title) ? $where['title'] = ['like', "%$title%"] : null;
        $art_list = $art_m->getArtList($page, $list_row, $where);
        foreach($art_list as $key => $value) {
            $size = (isMobile()) ? 30 : 100;
            preg_match('/<img((?!src).)*src[\s]*=[\s]*[\'"](?<src>[^\'"]*)[\'"]/i', $value['content'], $match);
            // @$v['src'] = $match['src'];
            @$art_list[$key]['val'] = preg_replace('/&nbsp;/is', '', mb_substr(strip_tags($value['content']), 0, $size)).'...';
            $art_list[$key]['marks'] = str2arr($value['marks'] ?? '');
        }
    
        $count = $art_m->getArtTotal($where);
        $this->assign([
            'art_list' => $art_list,
            'page'     => $page,
            'list_row' => $list_row,
            'count'    => $count,
            'is_index' => (isset($cat_id) || isset($label_id) || isset($title)) ? 0 : 1,
            'url'      => url('article/artList', ['cat_id' => $cat_id, 'label_id' => $label_id, 'title' => $title]),
        ]);
    }

    /**
     * 获取评论列表
     * @param int $page
     * @param int $list_row
     * @param int $art_id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function getCommentList($page = 1, $list_row = 5, $art_id = 0) {
        $comment_m = new CommentModel();
        $comment_list = $comment_m->getCommentList($page, $list_row, ['art_id' => $art_id]);
        $count = $comment_m->getCommentTotal(['art_id' => $art_id]);
        $this->assign([
            'comment_list' => $comment_list,
            'page'         => $page,
            'list_row'     => $list_row,
            'count'        => $count,
            'url'          => url('comment/commentList', ['art_id' => $art_id]),
        ]);
    }
}