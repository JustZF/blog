<?php
/**
 * @date:  2018/8/7 14:15
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\common\model;

use Dm\Request\V20151123 as Dm;
use think\Log;

include_once EXTEND_PATH . 'aliyun-php-sdk-core/Config.php';

class AliYun {
    //阿里云用户的accessKeyId
    private $access_key_id = 'LTAI2Iacjefx9fxn';
    //阿里云用户的accessSecret
    private $access_secret = 'O5WernXSVdO9N6KA166vUMXJK4fnNV';
    private $email_config = [
        //控制台创建的发信地址
        'account_name' => 'hi@blog.shunxin66.com',
        //控制台创建的标签
        'tag_name'     => 'blog',
    ];

    /**
     * 阿里云发送邮件
     * @param $form_alias [发件人昵称]
     * @param $to_address [目标地址]
     * @param $subject [邮件主题]
     * @param $html_body [邮件正文]
     * @return boolean
     */
    public function sendEmail($form_alias, $to_address, $subject, $html_body) {
        //需要设置对应的region名称，如华东1（杭州）设为cn-hangzhou，新加坡Region设为ap-southeast-1，澳洲Region设为ap-southeast-2。
        $iClientProfile = \DefaultProfile::getProfile("cn-hangzhou", $this->access_key_id, $this->access_secret);
        //新加坡或澳洲region需要设置服务器地址，华东1（杭州）不需要设置。
        //$iClientProfile::addEndpoint("ap-southeast-1","ap-southeast-1","Dm","dm.ap-southeast-1.aliyuncs.com");
        //$iClientProfile::addEndpoint("ap-southeast-2","ap-southeast-2","Dm","dm.ap-southeast-2.aliyuncs.com");
        $client = new \DefaultAcsClient($iClientProfile);
        $request = new Dm\SingleSendMailRequest();
        //新加坡或澳洲region需要设置SDK的版本，华东1（杭州）不需要设置。
        //$request->setVersion("2017-06-22");
        //控制台创建的发信地址
        $request->setAccountName($this->email_config['account_name']);
        //发信人昵称
        $request->setFromAlias($form_alias);
        $request->setAddressType(1);
        //控制台创建的标签
        $request->setTagName($this->email_config['tag_name']);
        $request->setReplyToAddress("true");
        //目标地址
        $request->setToAddress($to_address);
        //邮件主题
        $request->setSubject($subject);
        //邮件正文
        $request->setHtmlBody($html_body);
        try {
            $response = json_encode($client->getAcsResponse($request));
            Log::write("邮件发送成功\n地址：$to_address\n主题：$subject\n内容：$html_body\n接口返回：$response\n", 'log');
            return true;
        } catch (\ClientException  $e) {
            $msg = $e->getErrorCode() . '-';
            $msg .= $e->getErrorMessage();
            Log::write("邮件发送失败\n地址：$to_address\n主题：$subject\n内容：$html_body\n错误信息：$msg\n", 'log');
        } catch (\ServerException  $e) {
            $msg = $e->getErrorCode() . '-';
            $msg .= $e->getErrorMessage();
            Log::write("邮件发送失败\n地址：$to_address\n主题：$subject\n内容：$html_body\n错误信息：$msg\n", 'log');
        }
        return false;
    }
}