<?php
/**
 * 公共基础验证器
 * @date:  2018/6/30 8:36
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\common\validate;

use think\Validate;

class BaseValidate extends Validate {
    /**
     * 字符串长度验证
     * @param $value [验证值]
     * @param $rule [验证规则,例: (长度区间和编码:"1,5,UTF-8"), (指定长度和编码:"15,UTF-8")]
     * @return bool
     */
    protected function mb_length($value, $rule) {
        $rule = explode(',', $rule);
        $len = mb_strlen($value, end($rule));
        if (2 == count($rule)) {
            return $len == $rule[0];
        } else if (3 == count($rule)) {
            return $len >= $rule[0] && $len <= $rule[1];
        } else {
            return false;
        }
    }
}