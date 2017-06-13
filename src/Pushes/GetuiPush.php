<?php
/**
 * Created by PhpStorm.
 * User: lilhorse
 * Date: 2017/6/10
 * Time: 下午11:42
 */

namespace Loopeer\EasyPush\Pushes;


use DictionaryAlertMsg;
use IGeTui;
use IGtAPNPayload;
use IGtAppMessage;
use IGtListMessage;
use IGtNotificationTemplate;
use IGtSingleMessage;
use IGtTarget;
use IGtTransmissionTemplate;
use Loopeer\EasyPush\Services\Helpers;
use Loopeer\QuickCms\Services\Utils\LogUtil;
use RequestException;
use SimpleAlertMsg;

class GetuiPush
{
    const HOST = 'http://sdk.open.api.igexin.com/apiex.htm';

    protected $appId;
    protected $appKey;
    protected $appSecret;
    protected $masterSecret;
    protected $igt;

    public function __construct()
    {
        $this->appId = config('easypush.getui.app_id');
        $this->appKey = config('easypush.getui.app_key');
        $this->appSecret = config('easypush.getui.app_secret');
        $this->masterSecret = config('easypush.getui.master_secret');
        $this->igt = new IGeTui(self::HOST, $this->appKey, $this->masterSecret);
    }

    /**
     * 对单个用户推送消息
     * @param $clientId
     * @param $template
     */
    public function pushMessageToSingle($clientId, $template)
    {
        $message = new IGtSingleMessage();
        if (config('easypush.getui.is_offline')) {
            $message->set_isOffline(true);//是否离线
            $message->set_offlineExpireTime(config('easypush.getui.offline_expire_time', 720) * 60 * 1000);//离线时间
        }
        $message->set_data($template);//设置推送消息类型
        //$message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，2为4G/3G/2G，1为wifi推送，0为不限制推送
        //接收方
        $target = new IGtTarget();
        $target->set_appId($this->appId);
        $target->set_clientId($clientId);

        try {
            $resp = $this->igt->pushMessageToSingle($message, $target);
            Helpers::printResult($resp, 'gt');
        } catch (RequestException $e){
            $requestId = $e->getRequestId();
            //失败时重发
            $resp = $this->igt->pushMessageToSingle($message, $target, $requestId);
            Helpers::printResult($resp, 'gt');
        }
    }

    /**
     * 对指定列表用户推送消息
     * @param $clientIds
     * @param $template
     */
    public function pushMessageToList($clientIds, $template)
    {
        //定义"ListMessage"信息体
        $message = new IGtListMessage();
        if (config('easypush.getui.is_offline')) {
            $message->set_isOffline(true);//是否离线
            $message->set_offlineExpireTime(config('easypush.getui.offline_expire_time', 720) * 60 * 1000);//离线时间
        }
        $message->set_data($template);//设置推送消息类型
        $contentId = $this->igt->getContentId($message);
        $targetList = collect($clientIds)->map(function ($clientId) {
            $target = new IGtTarget();
            $target->set_appId($this->appId);
            $target->set_clientId($clientId);
            return $target;
        })->toArray();
        $resp = $this->igt->pushMessageToList($contentId, $targetList);
        Helpers::printResult($resp, 'gt');
    }


    /**
     * 对所有用户推送消息
     * @param $template
     */
    public function pushMessageToAll($template)
    {
        $message = new IGtAppMessage();
        if (config('easypush.getui.is_offline')) {
            $message->set_isOffline(true);//是否离线
            $message->set_offlineExpireTime(config('easypush.getui.offline_expire_time', 720) * 60 * 1000);//离线时间
        }
        $message->set_data($template);//设置推送消息类型
        $appIdList = array($this->appId);
        $message->set_appIdList($appIdList);
        $resp = $this->igt->pushMessageToApp($message);
        Helpers::printResult($resp, 'gt');
    }

    public function getTemplate($title, $content, $transmissionContent = '', $platform = 'android')
    {
        if ($platform == 'ios') {
            $template = $this->setIGtTransmissionTemplate($title, $content, $transmissionContent);
        } else {
            $template = $this->setIGtNotificationTemplate($title, $content, $transmissionContent);
        }
        return $template;
    }

    protected function setIGtNotificationTemplate($title, $content, $transmissionContent = '')
    {
        $template =  new IGtNotificationTemplate();
        $template ->set_appId($this->appId);                      //应用appid
        $template ->set_appkey($this->appKey);                    //应用appkey
        $template->set_transmissionType(2);            //透传消息类型
        $template->set_transmissionContent($transmissionContent);//透传内容
        $template->set_title($title);                  //通知栏标题
        $template->set_text($content);     //通知栏内容
        $template->set_logo('');                       //通知栏logo
        $template->set_logoURL('');                    //通知栏logo链接

        return $template;
    }

    protected function setIGtTransmissionTemplate($title, $content, $transmissionContent = '')
    {
        $template =  new IGtTransmissionTemplate();
        $template ->set_appId($this->appId);                      //应用appid
        $template ->set_appkey($this->appKey);                    //应用appkey
        $template->set_transmissionContent($transmissionContent);//透传内容
        $apn = new IGtAPNPayload();
        $alertMsg = new DictionaryAlertMsg();
        $alertMsg->title = $title;
        $alertMsg->body = $content;
        $apn->alertMsg = $alertMsg;
        $template->set_apnInfo($apn);

        return $template;
    }
}