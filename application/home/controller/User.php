<?php
/**
 * @date:  2018/8/7 13:31
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\home\controller;

//use app\common\model\AliYun;
use app\common\model\Common;
use app\home\model\User as UserModel;
use app\home\validate\User as UserValidate;

class User extends HomeBase {
    /**
     * 重置密码
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function forgetByEmail() {
        if (request()->isPost()) {
            $user_m = new UserModel();
            $res = $user_m->loginByEmail(request()->post('email'), request()->post('email_captcha'), 1);
            if ($res['status']) {
                $this->success($res['msg']);
            } else {
                $this->error($res['msg']);
            }
        } else {
            return view();
        }
    }


    /**
     * 邮箱登录
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function loginByEmail() {
        if (request()->isPost()) {
            $user_m = new UserModel();
            $res = $user_m->loginByEmail(request()->post('email'), request()->post('email_captcha'), 0);
            if (true === $res['status']) {
                $this->success($res['msg']);
            } else {
                $this->error($res['msg']);
            }
        } else {
            return view();
        }
    }

    /**
     * 密码登录
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function loginByPwd() {
        if (request()->isPost()) {
            if (!$this->checkCaptcha(request()->post('captcha'))) {
                $this->error('验证码错误');
            }
            $user_m = new UserModel();
            $res = $user_m->loginByPwd(request()->post('username'), request()->post('password'));
            if (true === $res) {
                $this->success('登录成功');
            } else {
                $this->error($res);
            }
        } else {
            return view();
        }
    }

    /**
     * GitHub账户登录
     */
    public function loginByGithub() {
        session('github_state', (new Common())->getRandomNum(8));
        $url = 'https://github.com/login/oauth/authorize?client_id=' . config('github.client_id') . '&redirect_uri=' . urlencode(config('github.callback_url')) . '&scope=&state=' . session('github_state');
        echo "<script>document.location='$url'</script>";
    }

    /**
     * QQ登录
     */
    public function loginByQQ() {
        session('qq_state', (new Common())->getRandomNum(8));
        $url = 'https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=' . config('qq.app_id') . '&redirect_uri=' . urlencode(config('qq.callback_url')) . '&state=' . session('qq_state');
        echo "<script>document.location='$url'</script>";
    }

    /**
     * 注销登录
     */
    public function logout() {
        cookie(null);
        session('user_id', null);
        session('nickname', null);
        session('head_img', null);
        $this->success('注销成功', '/');
    }

    /**
     * 获取邮件验证码
     */
    public function getEmailCode() {
        $user_v = new UserValidate();
        if (!$user_v->check(request()->post())) {   //验证用户提交数据是否合规
            $this->error($user_v->getError());
        } else if (!$this->checkCaptcha(request()->post('captcha'))) {  //验证验证码是否哦填写正确
            $this->error('验证码错误');
        } else {
            $com = new Common();
            //获取随机数字验证码
            $captcha = $com->getRandomNum();
            $html_body = '您的验证码为：' . $captcha . '，5分钟内有效';
            //发送邮件验证码
            $res = sendMail('Just博客', request()->post('email'), '验证码', $html_body);
            if (true === $res) {
                cookie('login_email', $com->cookieEncrypt(request()->post('email')), 300);
                //加密注册邮件验证码并存储到cookie
                cookie('login_code', $com->cookieEncrypt($captcha), 300);
                $this->success('验证码发送成功');
            } else {
                $this->error('验证码发送失败，请重试');
            }
        }
    }
}