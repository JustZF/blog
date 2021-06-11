<?php
/**
 * @date:  2018/7/9 21:21
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\admin\model;

use app\common\model\BaseModel;

class Mood extends BaseModel {

    public function getMoodList($page = null, $list_row = null) {
        // if (isset($page) && isset($list_row)) {
        //     $this->limit(($page - 1) * $list_row, $list_row);
        // }
        return $this->where(['deleted' => 0])->order('sort asc')->select();
    }
}