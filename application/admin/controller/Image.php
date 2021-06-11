<?php
/**
 * 图库控制器
 * @date:  2018/6/30 9:09
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\admin\controller;

use app\admin\model\Image as ImageModel;

class Image extends AdminBase {
    /**
     * 图片列表
     * @param string $img_name [搜索图片名]
     * @param int $page [当前页数]
     * @param int $list_row [每页条数]
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function imgList($img_name = '', $page = 1, $list_row = 10) {
        $img_m = new ImageModel();
        $img_list = $img_m->getImgList($page, $list_row, $img_name);
        $count = $img_m->getImgTotal($img_name);
        $this->assign([
            'img_list' => $img_list,
            'count'    => $count,
            'page'     => $page,
            'list_row' => $list_row,
            'img_name' => $img_name,
            'url'      => url('image/imgList', ['img_name' => $img_name]),
        ]);
        return view();
    }


    /**
     * 上传图片
     * @return \think\response\View
     */
    public function imgAdd() {
        if (request()->isPost()) {
            $img_m = new ImageModel();
            $res = $img_m->imgAdd();
            if ($res === true) {
                $this->success('图片上传成功');
            } else {
                $this->error($res);
            }
        } else {
            return view();
        }
    }

    /**
     * 图片信息修改
     * @param $img_id [图片id]
     * @throws \think\exception\DbException
     */
    public function imgEdit($img_id) {
        if (request()->isPost()) {
            $img_m = new ImageModel();
            $res = $img_m->imgEdit($img_id, request()->post());
            if ($res === true) {
                $this->success('修改成功');
            } else {
                $this->error($res);
            }
        }
    }

    /**
     * 图片展示
     * @param $img_id [图片id]
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function imgShow($img_id) {
        $img_m = new ImageModel();
        $image = $img_m->getImgById($img_id);
        $this->assign('image', $image);
        return view();
    }

    /**
     * 图片删除
     * @param $img_id [图片id]
     */
    public function imgDel($img_id) {
        $img_m = new ImageModel();
        $img_m->imgDel($img_id) ? $this->success('图片删除成功') : $this->error('图片删除失败');
    }
}