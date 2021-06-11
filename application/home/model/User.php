<?php
/**
 * @date:  2018/8/7 13:44
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\home\model;

use app\common\model\AliYun;
use app\common\model\BaseModel;
use app\common\model\Common;
use \app\home\validate\User as UserValidate;
use think\exception\DbException;

class User extends BaseModel {
    private $head_img_path = '/static/images/head';

    /**
     * 邮箱登录
     * @param $email [邮箱]
     * @param $captcha [邮件验证码]
     * @return array|bool|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function loginByEmail($email, $captcha, $forget) {
        $com = new Common();
        if (!cookie('login_email') || !cookie('login_code')) {
            return '邮件验证码已过期，请重新获取';
        } else if (cookie('login_email') != $com->cookieEncrypt($email)) {
            return '邮箱错误';
        } else if (cookie('login_code') != $com->cookieEncrypt($captcha)) {
            return '邮件验证码错误';
        } else {
            $user = $this->where('email', $email)->find();
            if($forget) {
                if($user) {
                    //重置密码
                    $password = $com->getRandomNum(6);  //明文密码
                    $data['salt'] = $com->getRandomStr(4);
                    $data['password'] = $com->encrypt($password, $data['salt']);
                    $res = $this->where('email', $email)->update($data);
                    if ($res) {
                        sendMail('重置密码', $email, '博客重置密码', '您的博客新密码为：' . $password);
                    }
                    return ['status' => $res,  'msg' => '新密码已发送到您的邮箱'];
                }else{
                    return ['status' => false, 'msg' => '邮箱错误'];
                }
                
            }else{
                if (!$user) { //不存在，则注册
                    $password = $com->getRandomNum(6);  //明文密码
                    $data['email'] = $email;
                    $data['salt'] = $com->getRandomStr(4);
                    $data['password'] = $com->encrypt($password, $data['salt']);
                    $data['head_img'] = $this->head_img_path . '/' . rand(1, 10) . '.svg';
                    $data['nickname'] = $com->getRandomStr(3) . $com->getRandomNum(5);
                    $res = $this->userAdd($data);
                    if (true === $res) {
                        cookie(null, 'login_');
                        sendMail('初始密码', $email, '博客初始密码', '您的博客初始密码为：' . $password);
                        session('user_id', $this->id);
                        session('nickname', $this->nickname);
                        session('head_img', $this->head_img);
                    }
                    return ['status' => $res,  'msg' => '登录成功，初始密码已发送到您的邮箱'];
                } else {
                    session('user_id', $user['id']);
                    session('nickname', $user['nickname']);
                    session('head_img', $user['head_img']);
                    return ['status' => true,  'msg' => '登录成功'];
                }
            }
        }
    }

    /**
     * 密码登录
     * @param $username [用户名或邮箱]
     * @param $password [密码]
     * @return bool|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function loginByPwd($username, $password) {
        $user = $this->where('username', $username)->whereOr('email', $username)->find();
        if (!$user) {
            return '用户名或邮箱不存在';
        } else if ($user->password != (new Common())->encrypt($password, $user->salt)) {
            return '密码错误';
        } else {
            session('user_id', $user->id);
            session('nickname', $user->nickname);
            session('head_img', $user->head_img);
            return true;
        }
    }

    /**
     * github账号登录
     * @param $github_data
     * @return array|bool|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function loginByGithub($github_data) {
        $user = $this->where(['github_id' => $github_data['github_id']])->find();
        if (!$user) { //不存在则自动注册
            $github_data['nickname'] = $github_data['github_name'];
            $github_data['head_img'] = $github_data['github_avatar_url'];
            $res = $this->userAdd($github_data);
            if (true === $res) {
                session('user_id', $this->id);
                session('nickname', $this->nickname);
                session('head_img', $this->head_img);
            }
            return $res;
        } else {
            session('user_id', $user['id']);
            session('nickname', $user['nickname']);
            session('head_img', $user['head_img']);
            return true;
        }
    }

    /**
     * qq账号登录
     * @param array $qq_data
     * @return array|bool|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function loginByQQ($qq_data) {
        $user = $this->where(['qq_openid' => $qq_data['qq_openid']])->find();
        if (!$user) { //不存在则自动注册
            $qq_data['nickname'] = $qq_data['qq_nickname'];
            $qq_data['head_img'] = $qq_data['qq_figureurl'];
            $res = $this->userAdd($qq_data);
            if (true === $res) {
                session('user_id', $this->id);
                session('nickname', $this->nickname);
                session('head_img', $this->head_img);
            }
            return $res;
        } else {
            session('user_id', $user['id']);
            session('nickname', $user['nickname']);
            session('head_img', $user['head_img']);
            return true;
        }
    }

    /**
     * 绑定账号
     * @param int $user_id [用户id]
     * @param array $data [修改数据]
     * @return false|int|string
     */
    public function userEdit($user_id, $data) {
        try {
            return $this->where('id', $user_id)->data($data)->save();
        } catch (DbException $e) {
            return false;
        }
    }

    /**
     * 获取用户列表
     * @param $page [当前页]
     * @param $list_row [每页条数]
     * @return false|\PDOStatement|string|\think\Collection
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserList($page, $list_row) {
        return $this->order('id desc')->limit(($page - 1) * $list_row, $list_row)->select();
    }

    /**
     * 获取用户总数
     * @return int|string
     */
    public function getUserTotal() {
        return $this->count(1);
    }

    /**
     * 添加用户
     * @param $data
     * @return array|bool|string
     */
    public function userAdd($data) {
        $user_v = new UserValidate();
        if (!$user_v->check($data)) {
            return $user_v->getError();
        } else {
            return $this->save($data) ? true : '添加失败';
        }
    }
}