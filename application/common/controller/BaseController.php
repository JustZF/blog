<?php
/**
 * 公共基础控制器类
 * @date:  2018/6/24 20:35
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\common\controller;

use think\captcha\Captcha;
use think\Controller;
use think\Request;

class BaseController extends Controller {
    public function __construct(Request $request = null) {
        parent::__construct($request);
    }

    /**
     * 验证码验证
     * @param $code [验证码]
     * @return boolean
     */
    protected function checkCaptcha($code) {
        $captcha = new Captcha();
        if (!$captcha->check($code)) {
            return false;
        } else {
            return true;
        }
    }
}