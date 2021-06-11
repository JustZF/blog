<?php
/**
 * @date:  2018/6/25 23:38
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\admin\controller;

use app\admin\model\Article as ArticleModel;
use app\admin\model\ArticleLabel;
use app\admin\model\Cat as CatModel;
use app\admin\model\Label as LabelModel;

class Article extends AdminBase {
    /**
     * 文章列表
     * @param string $title [通过文章标题筛选]
     * @param int $page [当前页]
     * @param int $list_row [每页条数]
     * @return \think\response\View
     * @throws \think\exception\DbException
     */
    public function artList($title = null, $page = 1, $list_row = 10) {
        $art_m = new ArticleModel();
        $where = !empty($title) ? ['title' => ['like', "%$title%"]] : null;
        $art_list = $art_m->getArtList($page, $list_row, $where);
        $count = $art_m->getArtTotal($where);
        $this->assign([
            'art_list' => $art_list,
            'count'    => $count,
            'page'     => $page,
            'list_row' => $list_row,
            'title'    => $title,
            'url'      => url('article/artList', ['title' => $title]),
        ]);
        return view();
    }

    /**
     * 文章修改
     * @param $art_id [文章id]
     * @return \think\response\View
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function artEdit($art_id) {
        $art_m = new ArticleModel();
        if (request()->isPost()) {
            $res = $art_m->artEdit($art_id, request()->post());
            if (true === $res) {
                $this->success('修改成功!', '');
            } else {
                $this->error($res);
            }
        } else {
            $art = $art_m->getArtById($art_id);
            $cat_m = new CatModel();
            $cat_list = $cat_m->getCatList();
            $label_m = new LabelModel();
            $label_list = $label_m->getLabelList();
            $art_label_m = new ArticleLabel();
            $art_label_ids = $art_label_m->getLabelIdsByArtId($art_id);
            $this->assign([
                'art'           => $art,
                'cat_list'      => $cat_list,
                'label_list'    => $label_list,
                'art_label_ids' => $art_label_ids,
            ]);
            return view();
        }
    }

    /**
     * 添加文章
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function artAdd() {
        if (request()->isPost()) {
            $art_m = new ArticleModel();
            $res = $art_m->artAdd(request()->post());
            if (true === $res) {
                $this->success('添加文章成功');
            } else {
                $this->error($res);
            }
        } else {
            $cat_m = new CatModel();
            $cat_list = $cat_m->getCatList();
            $label_m = new LabelModel();
            $label_list = $label_m->getLabelList();
            $this->assign([
                'cat_list'   => $cat_list,
                'label_list' => $label_list,
            ]);
            return view();
        }
    }

    public function artDel($art_id) {
        $art_m = new ArticleModel();
        $art_m->where('id', $art_id)->update(['deleted' => 1]) ? $this->success('删除成功') : $this->error('删除失败');
    }
}