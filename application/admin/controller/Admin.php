<?php
/**
 * @date:  2018/6/7 23:32
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\admin\controller;

use app\admin\model\Admin as AdminModel;

class Admin extends AdminBase {
    /**
     * 管理员登录
     * @return \think\response\View
     * @throws \think\exception\DbException
     */
    public function login() {
        if (request()->isAjax()) {
            if ($this->checkCaptcha(request()->post('captcha'))) {
                $admin_m = new AdminModel();
                $res = $admin_m->login(request()->post('username'));
                if ($res === true) {
                    $this->success('登录成功', url('layout', null, true, true));
                } else {
                    $this->error($res);
                }
            } else {
                $this->error('验证码错误');
            }
        } else {
            return view();
        }
    }

    /**
     * 管理员注销登录
     */
    public function logout() {
        session('admin', null);
        $this->success('注销成功', url('login', null, true, true));
    }

    /**
     * 后台主框架
     * @return \think\response\View
     */
    public function layout() {
        return view();
    }

    /**
     * 后台首页
     * @return \think\response\View
     */
    public function index() {
        return view();
    }

    /**
     * 密码修改
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function pwdEdit() {
        if (request()->isPost()) {
            $res = (new AdminModel())->pwdEdit(session('admin.id'), request()->post('password'), request()->post('password_new'), request()->post('password_confirm'));
            if (true === $res) {
                $this->success('修改成功');
            } else {
                $this->error($res);
            }
        } else {
            return view();
        }
    }

    /**
     * 管理员信息修改
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function infoEdit() {
        $admin_m = new AdminModel();
        if (request()->isPost()) {
            $res = $admin_m->allowField(['name', 'email'])->adminEdit(session('admin.id'), request()->post());
            true === $res ? $this->success('修改成功') : $this->error($res);
        } else {
            $admin = $admin_m->getAdminInfoById(session('admin.id'));
            $this->assign(['admin' => $admin]);
            return view();
        }
    }
}