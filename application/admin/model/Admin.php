<?php
/**
 * @date:  2018/6/24 20:48
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\admin\model;

use app\common\model\BaseModel;
use app\admin\validate\Admin as AdminValidate;
use app\common\model\Common;

class Admin extends BaseModel {

    /**
     * 管理员登录验证
     * @param $username [用户名]
     * @param $password [密码]
     * @return bool|string [true|错误信息]
     * @throws \think\exception\DbException
     */
    public function login($username) {
        if ($admin = Admin::get(['username' => $username])) {
            // if ((new Common)->encrypt($password, $admin->salt) == $admin->password) {
            //     session('admin', $admin);
            //     return true;
            // } else {
            //     return '密码错误';
            // }
            session('admin', $admin);
            return true;
        } else {
            return '用户名不存在';
        }
    }

    /**
     * 密码修改
     * @param $admin_id [管理员id]
     * @param $password [旧密码]
     * @param $password_new [新密码]
     * @param $password_confirm [确认密码]
     * @return array|bool|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function pwdEdit($admin_id, $password, $password_new, $password_confirm) {
        if ($password_new != $password_confirm) {
            return '两次密码输入不一致';
        } else if (!isset($password_new{5})) {
            return '密码不能少于6位';
        }
        $admin = $this->find($admin_id);
        if (!$admin) {
            return '获取信息失败，请重试';
        }
        $com = new Common();
        if ($com->encrypt($password, $admin['salt']) != $admin['password']) {
            return '密码错误';
        } else {
            $salt = $com->getRandomStr();
            return $this->adminEdit($admin_id, ['password' => $com->encrypt($password_new, $salt), 'salt' => $salt]);
        }
    }

    /**
     * 管理员数据修改
     * @param int $admin_id [管理员id]
     * @param array $data [修改数据]
     * @return array|bool|string
     */
    public function adminEdit($admin_id, $data) {
        $admin_v = new AdminValidate();
        if (!$admin_v->check($data)) {
            return $admin_v->getError();
        }
        return $this->save($data,['id'=>$admin_id]) ? true : '数据保存失败';
    }

    /**
     * 通过id获取管理员信息
     * @param $admin_id [管理员id]
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAdminInfoById($admin_id) {
        return $this->field('a.*,b.name as role_name')->alias('a')->join('role b', 'a.role_id=b.id', 'LEFT')->find($admin_id);
    }
}