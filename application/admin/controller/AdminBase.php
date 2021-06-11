<?php
/**
 * 后台基础控制器
 * @date:  2018/6/7 23:30
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\admin\controller;

use app\common\controller\BaseController;
use think\Request;

class AdminBase extends BaseController {

    //无需检查管理员是否登录的方法名
    protected $no_check_login = [
        'admin/login',
    ];

    public function __construct(Request $request = null) {
        parent::__construct($request);
        if ($this->isNeedCheckLogin() && !$this->isLogin()) {
            $this->error('请先登录', url('admin/login'));
        }
    }

    /**
     * 当前访问action是否需要登录验证
     * @return bool
     */
    protected function isNeedCheckLogin() {
        $controller = strtolower(request()->controller());
        $action = strtolower(request()->action());
        if (in_array("$controller/$action", $this->no_check_login)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 检查管理员是否登录
     * @return boolean
     */
    protected function isLogin() {
        if (session('admin')) {
            return true;
        } else {
            return false;
        }
    }

}