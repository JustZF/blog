<?php
/**
 * 图库模型
 * @date:  2018/6/30 9:10
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\admin\model;

use app\common\model\BaseModel;
use app\common\model\Common;
use \think\Image as ImageTool;

class Image extends BaseModel {
    //图片上传水印文字
    private $watermark = 'www.shunxin66.com';
    //字体文件路径
    private $font = '/static/fonts/consolai.ttf';
    //上传图片保存路径
    private $img_path = '/static/upload/image/';

    /**
     * 获取图片列表，可根据图片名筛选
     * @param $page
     * @param $list_row
     * @param null $img_name
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getImgList($page, $list_row, $img_name = null) {
        $img_m = $this;
        if (!empty($img_name)) {
            $img_m = $img_m->where('name', 'like', "%$img_name%");
        }
        $img_list = $img_m->where('deleted="0"')->order('id desc')->limit(($page - 1) * $list_row, $list_row)->select();
        return $img_list;
    }

    /**
     * 获取图片总数
     * @param $img_name
     * @return int|string
     */
    public function getImgTotal($img_name = null) {
        $img_m = $this;
        if (!empty($img_name)) {
            $img_m = $img_m->where('name', 'like', "%$img_name%");
        }
        return $img_m->where('deleted="0"')->count(1);
    }

    /**
     * 新增图片
     * @return array|bool|string
     */
    public function imgAdd() {
        $data = $this->imgUpload();
        if ($data) {
            $res = $this->validate(true)->save($data);
            if ($res) {
                return true;
            } else {
                return $this->getError();
            }
        } else {
            return '图片上传失败，请重试!';
        }
    }

    /**
     * @param $img_id [图片记录id]
     * @param $data [修改的数据]
     * @return array|bool|string
     * @throws \think\exception\DbException
     */
    public function imgEdit($img_id, $data) {
        if ($image = self::get($img_id)) {
            if ($image->validate(true)->save($data)) {
                return true;
            } else {
                return $image->getError();
            }
        } else {
            return '修改失败';
        }
    }

    /**
     * 通过图片id获取图片信息
     * @param $img_id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getImgById($img_id) {
        return $this->find($img_id);
    }

    public function imgDel($img_id) {
        return $this->where(['id' => $img_id])->update(['deleted' => 1]);
    }

    /**
     * 图片上传
     * @return array|bool
     */
    public function imgUpload() {
        $root_path = request()->server('DOCUMENT_ROOT');
        $font_path = $root_path . $this->font;
        $com = new Common();
        $unique = $com->uniqueStr();
        $image = ImageTool::open(request()->file('file'));
        //原图保存相对路径
        //$original = $this->img_path . 'original/' . date('Y/m/') . $unique . '.' . $image->type();
        //原图保存绝对路径
        //$original_abs = $root_path . $original;
        //缩略图600*400相对路径
        //$middling = $this->img_path . '600x400/' . date('Y/m/') . $unique . '.' . $image->type();
        //缩略图600*400绝对路径
        //$middling_abs = $root_path . $middling;
        //缩略图300*200相对路径
        $small = $this->img_path . '300x200/' . date('Y/m/') . $unique . '.' . $image->type();
        //缩略图300*200绝对路径
        $small_abs = $root_path . $small;
        //$res1 = $res2 =
        $res3 = false;
        //if ($com->isDir(dirname($original_abs))) {
        //    $res1 = $image->text($this->watermark, $font_path, 15, '#faebd7')->save($original_abs);
        //}
        //if ($res1 && $com->isDir(dirname($middling_abs))) {
        //    $res2 = $image->thumb(600, 400, ImageTool::THUMB_CENTER)->text($this->watermark, $font_path, 15, '#faebd7')->save($middling_abs);
        //}
        if ($com->isDir(dirname($small_abs))) {
            $res3 = $image->thumb(300, 200, ImageTool::THUMB_CENTER)->text($this->watermark, $font_path, 15, '#faebd7')->save($small_abs);
        }
        if (/*$res1 && $res2 && */$res3) {
            $info = [
                'name'          => $unique,
                //'path'          => $original,
                //'middling_path' => $middling,
                'small_path'    => $small,
            ];
        } else {
            $info = false;
        }
        return $info;
    }
}