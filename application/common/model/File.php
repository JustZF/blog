<?php
/**
 * @date:  2018/10/20 19:59
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\common\model;

use think\exception\ErrorException;

class File {

    /**
     * 获取文件夹下内容
     * @param string $path [文件夹路径]
     * @param array $ext [需要显示的文件后缀名,null:显示所有文件和文件夹]
     * @param bool $isShowUpperDir [是否显示上一级文件夹]
     * @return array [文件夹下内容]
     */
    public function getPathContent($path, $ext = null, $isShowUpperDir = true) {
        $path_content = [];
        if (is_dir($path)) {
            $dir = opendir($path);
            while ($item = readdir($dir)) {
                $arr = explode('.', $item);
                if (is_file($path . $item) && (!isset($ext) || in_array('.' . end($arr), $ext))) {
                    $finfo = finfo_open(FILEINFO_MIME);
                    $mimetype = finfo_file($finfo, $path . $item);
                    finfo_close($finfo);
                    $path_content[] = [
                        'name' => $item,
                        'path' => strchr($path . $item, '/static/'),
                        'time' => date('Y-m-d H:i:s', filemtime($path . $item)),
                        'type' => $mimetype,
                    ];
                } else if (is_dir($path . $item) && $item != '.') {
                    if (!$isShowUpperDir && $item == '..') {
                        continue;
                    }
                    $path_content[] = [
                        'name' => $item,
                        'path' => strchr($item == '..' ? dirname($path) . '/' : "$path$item/", '/static/'),
                        'time' => date('Y-m-d H:i:s', filemtime($path . $item)),
                        'type' => 'dir',
                    ];
                }
            }
            closedir($dir);
        }
        return $path_content;
    }

    /**
     * 在上传路径下新建文件夹
     * @param $folder_name [相对于upload文件夹的文件夹名]
     * @return true|string [创建成功|失败信息]
     */
    public function mkFolder($folder_name) {
        $full_folder_name = ROOT_PATH . 'public/static/upload/' . ltrim($folder_name, '/\\');
        if (is_dir($full_folder_name)) {
            return '该文件夹已存在，无法重复创建';
        } else {
            try {
                return mkdir($full_folder_name, 0777, true) ? true : '新建文件夹失败';
            } catch (ErrorException $e) {
                return $e->getMessage();
            }
        }
    }

    /**
     * 删除上传目录下的文件或文件夹
     * @param $path [相对于upload文件夹的路径名]
     * @return true|string [删除成功|失败信息]
     */
    public function rmPath($path) {
        $full_path_name = ROOT_PATH . 'public/static/upload/' . ltrim($path, '/\\');
        if (is_dir($full_path_name)) {
            try {
                return rmdir($full_path_name) ? true : '删除文件夹失败';
            } catch (ErrorException $e) {
                return '删除失败，可能该文件夹不为空';
            }
        } else if (is_file($full_path_name)) {
            try {
                return unlink($full_path_name) ? true : '删除文件失败';
            } catch (ErrorException $e) {
                return $e->getMessage();
            }
        } else {
            return '所选路径不存在，请刷新后重试！';
        }
    }
}