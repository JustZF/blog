<?php
/**
 * @date:  2018/8/7 14:27
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\common\model;

class Common {
    /**
     * 获取一个唯一字符串
     * @return string
     */
    public function uniqueStr() {
        return time() . $this->getRandomStr(6);
    }

    /**
     * 获取随机字符串
     * @param int $length [字符串长度]
     * @return bool|string
     */
    public function getRandomStr($length = 6) {
        $str = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM_';
        return substr(str_shuffle($str), 0, $length);
    }

    /**
     * 判断文件夹路径是否存在，不存在则创建
     * @param string $dir [文件夹路径]
     * @return bool
     */
    public function isDir($dir) {
        if (is_dir($dir)) {
            return true;
        } else {
            if (mkdir($dir, '0777', true)) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * curl 进行POST 提交
     * @param string $url [post 提交地址]
     * @param string $cookie [cookie 存储文件路径]
     * @param array $data [post 提交数据]
     * @param boolean $is_json [是否提交json格式]
     * @return boolean|mixed
     */
    public function postByCurl($url, $cookie, $data, $is_json = false) {
        $ch = curl_init(); //初始化curl模块
        curl_setopt($ch, CURLOPT_URL, $url); //登录提交的地址
        curl_setopt($ch, CURLOPT_HEADER, 0); //是否显示头信息
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //是否自动显示返回的信息
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); //获取cookie信息
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie); //设置cookie信息保存在指定的文件夹中
        curl_setopt($ch, CURLOPT_POST, 1); //以POST方式提交
        if (strchr($url, 'https://')) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//https
        }
        if ($is_json) {
            $data = json_encode($data);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length:' . strlen($data),
            ));
        } else {
            $data = http_build_query($data);//要执行的信息
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//要执行的信息
        $res = curl_exec($ch);    //执行CURL
        curl_close($ch);
        return $res;
    }

    /**
     * curl 获取链接内容
     * @param string $url [获取链接]
     * @param string $cookie_input [cookie 输入文件]
     * @param string $cookie_output [cookie 保存文件]
     * @return mixed [链接内容]
     */
    public function getByCurl($url, $cookie_input = null, $cookie_output = null) {
        $ch = curl_init(); //初始化curl模块
        curl_setopt($ch, CURLOPT_URL, $url); //获取信息地址
        curl_setopt($ch, CURLOPT_HEADER, 0); //是否显示头信息
        curl_setopt($ch, CURLOPT_USERAGENT, 'User-Agent：Awesome-Octocat-App');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //是否自动显示返回的信息
        isset($cookie_input) ? curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_input) : null;//设置cookie信息保存在指定的文件夹中
        isset($cookie_output) ? curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_output) : null;//设置cookie信息保存在指定的文件夹中
        if (strchr($url, 'https://')) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//https
        }
        $res = curl_exec($ch);    //执行curl转去页面内容
        curl_close($ch);
        return $res; //返回链接内容
    }

    /**
     * 获取随机数字验证码
     * @param int $length [验证码长度]
     * @return bool|string [指定长度验证码]
     */
    public function getRandomNum($length = 4) {
        $num_str = '';
        while ($length--) {
            $num_str .= rand(0, 9);
        }
        return $num_str;
    }

    /**
     * 字符串加密算法
     * @param string $str [加密前字符串]
     * @param string $salt [加密盐]
     * @return string [加密后字符串]
     */
    public function encrypt($str, $salt = '') {
        return md5($str . $salt);
    }

    /**
     * cookie加密
     * @param string $str
     * @return string
     */
    public function cookieEncrypt($str) {
        return $this->encrypt($str, config('cookie_salt'));
    }
}