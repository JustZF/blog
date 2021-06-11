<?php
/**
 * @date:  2018/8/15 20:05
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\admin\controller;

use app\home\model\User as UserModel;

class User extends AdminBase {
    public function userList($page = 1, $list_row = 10) {
        $user_m = new UserModel();
        $user_list = $user_m->getUserList($page, $list_row);
        $count = $user_m->getUserTotal();
        $this->assign([
            'user_list' => $user_list,
            'page'      => $page,
            'list_row'  => $list_row,
            'count'     => $count,
        ]);
        return view();
    }
}