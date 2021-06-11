<?php
/**
 * @date:  2018/10/20 23:08
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\admin\controller;

use app\common\model\File as FileModel;
use think\exception\ErrorException;


class File extends AdminBase {

    /**
     * 图片路径内容展示
     * @param string $path [图片路径]
     * @return \think\response\View
     */
    public function showImgPath($path = null) {
        //拼接并去掉路径中的（../）
        $full_path = ROOT_PATH . 'public' . preg_replace('/\.\.\/.*\//', '', $path);
        $img_root_path = ROOT_PATH . 'public' . config('UEditor.imageManagerListPath');
        if (!isset($path) || !is_dir($full_path)) {
            //图片上传目录
            $full_path = $img_root_path;
        }
        //需展示的图片扩展名
        $ext = config('UEditor.imageManagerAllowFiles');
        $file_m = new FileModel();
        $path_items = $file_m->getPathContent($full_path, $ext, dirname($full_path) != dirname($img_root_path));
        $this->assign([
            'curr_path'  => ltrim(strchr($full_path, '/image/'), '/image/'),
            'path_items' => $path_items,
            'url'        => $_SERVER['REQUEST_URI'],
        ]);
        return view('show_img_path');
    }

    public function imgUpload() {

    }

    public function showAttachmentPath($path = null) {
        $full_path = ROOT_PATH . 'public' . preg_replace('/\.\.\/.*\//', '', $path);
        $img_root_path = ROOT_PATH . 'public' . config('UEditor.imageManagerListPath');
        if (!isset($path) || !is_dir($full_path)) {
            //图片上传目录
            $full_path = $img_root_path;
        }
        //需展示的图片扩展名
        $ext = config('UEditor.imageManagerAllowFiles');
        $file_m = new FileModel();
        $path_items = $file_m->getPathContent($full_path, $ext, dirname($full_path) != dirname($img_root_path));
        $this->assign([
            'curr_path'  => ltrim(strchr($full_path, '/image/'), '/image/'),
            'path_items' => $path_items,
            'url'        => $_SERVER['REQUEST_URI'],
        ]);
        return view('show_img_path');
    }

    /**
     * 新建文件夹
     */
    public function mkFolder() {
        $folder_name = request()->post('folder_name');
        if (strpos($folder_name, '.')) {
            $this->error('文件夹名不能包含"."');
        }
        $file_m = new FileModel();
        $res = $file_m->mkFolder($folder_name);
        true === $res ? $this->success('新建文件夹成功') : $this->error($res);
    }

    /**
     * 删除文件或文件夹
     */
    public function rmPath() {
        $path_name = request()->post('path');
        if (strpos($path_name, '.')) {
            $this->error('路径错误');
        }
        $res = (new FileModel())->rmPath($path_name);
        true === $res ? $this->success('删除成功') : $this->error($res);
    }
}