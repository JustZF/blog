<?php
/**
 * @date:  2018/8/15 13:23
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\home\controller;

use app\common\model\Common;
use app\home\model\User as UserModel;

class Callback extends HomeBase {
    /**
     * github授权回调
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function github() {
        if (request()->get('code') && request()->get('state') && request()->get('state') == session('github_state')) {
            session('github_state', null);
            $url_access_token = 'https://github.com/login/oauth/access_token';
            $data = [
                'client_id'     => config('github.client_id'),
                'client_secret' => config('github.client_secret'),
                'code'          => request()->get('code'),
                'redirect_uri'  => config('github.callback_url'),
                'state'         => request()->get('state'),
            ];
            $com = new Common();
            $res = $com->postByCurl($url_access_token, null, $data);
            parse_str($res, $res);
            if (isset($res['error'])) {
                $this->error('请求授权失败：' . $res['error']);
            } else {
                $url_user = 'https://api.github.com/user?access_token=' . $res['access_token'];
                $res = $com->getByCurl($url_user);
                $res = json_decode($res, true);
                $data = [
                    'github_id'         => $res['id'],
                    'github_login'      => $res['login'],
                    'github_name'       => $res['name'],
                    'github_avatar_url' => $res['avatar_url'],
                ];
                $user_m = new UserModel();
                if (!session('user_id')) {  //登录
                    $r = $user_m->loginByGithub($data);
                    if (true === $r) {
                        $this->success('登录成功', cookie('back_url'));
                    } else {
                        $this->error($r);
                    }
                } else {    //绑定账号
                    $user_m->userEdit(session('user_id'), $data) ? $this->success('绑定成功') : $this->error('绑定失败');
                }
            }
        }
        $this->error('授权登录失败，请重试');
    }

    /**
     * qq登录授权回调
     */
    public function qq() {
        if (request()->get('code') && request()->get('state') && request()->get('state') == session('qq_state')) {
            session('qq_state', null);
            $url_access_token = 'https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&client_id=' . config('qq.app_id') . '&client_secret=' . config('qq.app_key') . '&code=' . request()->get('code') . '&redirect_uri=' . urlencode(config('qq.callback_url'));
            //code换取access_token
            $res = (new Common())->getByCurl($url_access_token);
            parse_str($res, $res);
            if (isset($res['msg'])) {
                //获取access_token失败
                $this->error('请求授权失败：' . $res['msg']);
            } else {
                //access_token换取openid
                $openid_arr = $this->getOpenIdQQ($res['access_token']);
                if (is_string($openid_arr)) {
                    $this->error($openid_arr);
                } else {
                    //access_token和openid换取用户信息
                    $info = $this->getUserInfoQQ($res['access_token'], $openid_arr['openid']);
                    $data = [
                        'qq_openid'    => $openid_arr['openid'],
                        'qq_nickname'  => $info['nickname'],
                        'qq_figureurl' => $info['figureurl_qq_1'],
                    ];
                    $user_m = new UserModel();
                    if (!session('user_id')) {  //登录
                        $r = $user_m->loginByQQ($data);
                        if (true === $r) {
                            $this->success('登录成功', cookie('back_url'));
                        } else {
                            $this->error($r);
                        }
                    } else {    //绑定账号
                        $user_m->userEdit(session('user_id'), $data) ? $this->success('绑定成功') : $this->error('绑定失败');
                    }
                }
            }
        }
        $this->error('授权登录失败，请重试');
    }

    /**
     * QQ access_token换取openid
     * @param $access_token
     * @return mixed
     */
    private function getOpenIdQQ($access_token) {
        $url_user = 'https://graph.qq.com/oauth2.0/me?access_token=' . $access_token;
        $res = (new Common())->getByCurl($url_user);
        parse_str($res, $arr);
        if (isset($arr['msg'])) {
            //获取openid失败
            return '请求授权失败：' . $arr['msg'];
        } else {
            preg_match('/{.*}/', $res, $json);
            $res = json_decode($json[0], true);
            return $res;
        }
    }

    /**
     * QQ access_token和openid换取用户信息
     * @param $access_token
     * @param $openid
     * @return mixed
     */
    private function getUserInfoQQ($access_token, $openid) {
        $user_info_url = 'https://graph.qq.com/user/get_user_info?access_token=' . $access_token . '&oauth_consumer_key=' . config('qq.app_id') . '&openid=' . $openid;
        $res = (new Common())->getByCurl($user_info_url);
        return json_decode($res, true);
    }
}